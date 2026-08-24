<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dotenv\Util\Regex;
use PharIo\Manifest\Email;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Mail\welcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendForget;

use Carbon\Carbon;

class AuthController extends Controller
{
    public function drive()
    {

        $driveData = DB::table('placement_drive')->where('is_active', 1)->latest()->get();

        return view('indexDrive', compact('driveData'));
    }

    public function home()
    {
        // Fetch recent announcements for homepage
        $recentAnnouncements = DB::table('announcements')
            ->where('type', 'announcement')
            ->where('is_active', 1)
            ->latest('id')
            ->limit(50)
            ->get();

        $recentNotices = DB::table('announcements')
            ->where('type', 'notice')
            ->where('is_active', 1)
            ->latest('id')
            ->limit(50)
            ->get();
      
        $sliders = DB::table('sliders')
        ->orderBy('order', 'asc')
        ->get();

        $placementUpdates = DB::table('placement_updates')
            ->where('is_active', 1)
            ->orderBy('order', 'asc')
            ->get();

        $testimonials = DB::table('testimonials')
            ->where('is_active', 1)
            ->orderBy('order', 'asc')
            ->get();


        return view('welcome', compact('recentAnnouncements', 'recentNotices', 'sliders', 'placementUpdates', 'testimonials'));
    }

    public function helpCenter()
    {
        return view('help_center');
    }

    public function driveDetail(Request $request)
    {
        $driveId = $request->route('id');
        $drive = DB::table('placement_drive')->where('id', $driveId)->first();

        if (!$drive) {
            abort(404, 'Drive not found');
        }

        // Decode JSON fields safely
        $drive->required_skills = $this->decodeJsonField($drive->required_skills);
        $drive->course_id = $this->decodeJsonField($drive->course_id);
        $drive->department_id = $this->decodeJsonField($drive->department_id);

        $drive->college = DB::table('college')->where('id', $drive->college_id)->value('name');
        $drive->courses = DB::table('courses')->whereIn('id', $drive->course_id)->pluck('name')->toArray();
        $drive->departments = DB::table('departments')->whereIn('id', $drive->department_id)->pluck('name')->toArray();

        return view('drive_detail', compact('drive'));
    }

    /**
     * Decode JSON field into array, fallback to comma-separated or null.
     */
    private function decodeJsonField($value)
    {
        if (empty($value)) return [];

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return array_map('trim', explode(',', $value));
    }



    public function notifications()
    {
        $notifications = DB::table('drive_notifications')
            ->where('user_id', Auth::id())
            ->join('placement_drive', 'drive_notifications.drive_id', '=', 'placement_drive.id')
            ->select(
                'drive_notifications.*',
                'placement_drive.company_name',
                'placement_drive.job_title',
                'placement_drive.drive_date',
                'placement_drive.description'
            )
            ->orderByDesc('drive_notifications.created_at')
            ->get();

        return view('notification', compact('notifications'));
    }

    public function markSeenNotification($id)
    {
        $notification = DB::table('drive_notifications')->where('id', $id)->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        if (!$notification->read_at) {
            $newValue = now();
            DB::table('drive_notifications')->where('id', $id)->update([
                'read_at' => $newValue,
                'updated_at' => now(),
            ]);
            return response()->json(['success' => true, 'read_at' => $newValue]);
        }

        return response()->json(['success' => true, 'read_at' => $notification->read_at]);
    }

   public function applyPlacement($drive_id)
{
    $studentId = Auth::id();
    $now = now();

    // 1. First, check if already applied (quick check)
    $already = DB::table('placement_applications')
        ->where('placement_drive_id', $drive_id)
        ->where('student_id', $studentId)
        ->first();

    if ($already) {
        return back()->with('error', 'You have already applied for this drive.');
    }

    // 2. Check profile
    $profile = DB::table('users_profile')->where('user_id', $studentId)->first();
    if (!$profile) {
        return back()->with('error', 'Please complete your profile before applying.');
    }

    // 3. Check drive exists
    $drive = DB::table('placement_drive')->where('id', $drive_id)->first();
    if (!$drive) {
        return back()->with('error', 'Placement drive not found.');
    }

    $deadline = \Carbon\Carbon::parse($drive->application_deadline);
$driveDate = \Carbon\Carbon::parse($drive->drive_date);
$now = now();

// 5. Check application deadline - allows until 11:59 PM on deadline day
if ($deadline->endOfDay()->lt($now)) {
    return back()->with('error', 'The application deadline for this drive has passed.');
}

// 4. Check drive date
if ($driveDate->startOfDay()->lt($now)) {
    return back()->with('error', 'You cannot apply for this drive as the date has passed.');
}

   

    // 6. Check reappear eligibility
    $user = DB::table('users')->where('id', $studentId)->first();
    if ($drive->is_reappear == 0 && $user->is_reappear == 1) {
        return back()->with('error', 'This drive is not open for reappear students.');
    }

    // 7. Academic Eligibility Checks
    $academicRecords = DB::table('academic_history')
        ->where('user_id', $studentId)
        ->where('is_active', true)
        ->get();

    $hasTenth = $academicRecords->where('education_level', '10th')->first();
    $hasTwelfth = $academicRecords->where('education_level', '12th')->first();
    $hasGraduation = $academicRecords->whereIn('education_level', ['bachelor', 'diploma'])->first();

    // 8. 10th Check
    if (!$hasTenth) {
        return back()->with('error', 'Please add your 10th details in your academic history.');
    }

    $tenthPercentage = $hasTenth->grade_type == 'gpa' ? $hasTenth->grade * 10 : $hasTenth->grade;
    if (!empty($drive->tenth_percentage) && (float)$tenthPercentage < (float)$drive->tenth_percentage) {
        return back()->with('error', 'Your 10th percentage does not meet the eligibility criteria.');
    }

    // 9. 12th Check
    if (!$hasTwelfth) {
        return back()->with('error', 'Please add your 12th details in your academic history.');
    }

    $twelfthPercentage = $hasTwelfth->grade_type == 'gpa' ? $hasTwelfth->grade * 10 : $hasTwelfth->grade;
    if (!empty($drive->twelfth_percentage) && (float)$twelfthPercentage < (float)$drive->twelfth_percentage) {
        return back()->with('error', 'Your 12th percentage does not meet the eligibility criteria.');
    }

 

     if (!$hasGraduation) {
            return back()->with('error', 'Please add your graduation or diploma details in your academic history.');
        }

        $graduationPercentage = $hasGraduation->grade_type == 'gpa' ? $hasGraduation->grade * 10 : $hasGraduation->grade;

        if (!empty($drive->graduation_percentage) && (float)$graduationPercentage < (float)$drive->graduation_percentage) {
            return back()->with('error', 'Your graduation percentage does not meet the eligibility criteria.');
        }

    // 10. Decode JSON fields
    $drivePassingYears = json_decode($drive->eligibility_passing_year, true) ?? [];
    $driveCourses = json_decode($drive->course_id, true) ?? [];
    $driveDepartments = json_decode($drive->department_id, true) ?? [];

    // 11. College check
    $studentCollegeId = (int)$profile->college;
    $driveCollegeIds = [];

    if (!empty($drive->college_id)) {
        $decoded = json_decode($drive->college_id, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $driveCollegeIds = $decoded;
        } else {
            $driveCollegeIds = [(int)$drive->college_id];
        }
    }

    $collegeMatch = false;
    foreach ($driveCollegeIds as $collegeId) {
        if ((int)$collegeId == $studentCollegeId) {
            $collegeMatch = true;
            break;
        }
    }

    if (!$collegeMatch) {
        return back()->with('error', 'You are not from the eligible college.');
    }

    // 12. Course check
    if (!in_array($profile->course, $driveCourses)) {
        return back()->with('error', 'Your course is not eligible for this drive.');
    }

    // 13. Department check
    if (!in_array($profile->department, $driveDepartments)) {
        return back()->with('error', 'Your department is not eligible for this drive.');
    }

    // 14. CGPA check
    if (!empty($drive->eligibility_cgpa) && $profile->cgpa < $drive->eligibility_cgpa) {
        return back()->with('error', 'Your CGPA does not meet the eligibility criteria.');
    }

    // 15. Passing year check
    if (!in_array($profile->passing_year, $drivePassingYears)) {
        return back()->with('error', 'Your Batch is not eligible for this drive.');
    }


       
    

    // 17. FINAL INSERT with transaction and duplicate protection
    DB::beginTransaction();

    try {
        // Double-check inside transaction (prevents race condition)
        $finalCheck = DB::table('placement_applications')
            ->where('placement_drive_id', $drive_id)
            ->where('student_id', $studentId)
            ->lockForUpdate() // Lock the row to prevent concurrent inserts
            ->first();

        if ($finalCheck) {
            DB::rollBack();
            return back()->with('error', 'You have already applied for this drive.');
        }

        // Prepare insert data WITHOUT any ID field
        $insertData = [
            'placement_drive_id' => $drive_id,
            'student_id' => $studentId,
            'application_status' => 'applied',
            'application_responses' => json_encode([
                'message' => 'Thank you for applying for this placement drive. We will review your application soon.'
            ]),
            'applied_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Insert the application
        $inserted = DB::table('placement_applications')->insert($insertData);

        if (!$inserted) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit application. Please try again.');
        }

        DB::commit();

        return back()->with('success', 'Application submitted successfully!');

    } catch (\Exception $e) {
        DB::rollBack();

        // Handle duplicate entry error specifically
        if (strpos($e->getMessage(), 'Duplicate entry') !== false || 
            strpos($e->getMessage(), '1062') !== false) {
            return back()->with('error', 'You have already applied for this placement drive.');
        }

        // Log the error for debugging
        \Log::error('Placement Application Error:');
        \Log::error('Student ID: ' . $studentId);
        \Log::error('Drive ID: ' . $drive_id);
        \Log::error('Error: ' . $e->getMessage());
        \Log::error('Trace: ' . $e->getTraceAsString());

        return back()->with('error', 'An error occurred while submitting your application. Please try again.');
    }
}

    public function appliedPlacements()
    {
        $studentId = Auth::id();

        $applications = DB::table('placement_applications')
            ->where('placement_applications.student_id', $studentId)
            ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
            ->get();

        return view('applied_all', compact('applications'));
    }

    public function applicationDetail($application_id)
    {
        $studentId = Auth::id();

        $application = DB::table('placement_applications')
            ->where('placement_applications.student_id', $studentId)
            ->where('placement_applications.placement_drive_id', $application_id)
            ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
            ->first();

        if (!$application) {
            return redirect()->back()->with('error', 'Application not found.');
        }

        return view('applied_placements', compact('application'));
    }

    public function toggleSave(Request $request)
    {
        $userId = Auth::id();
        $driveId = $request->drive_id;

        // Get current state or default to false
        $currentRecord = DB::table('saved_drives')
            ->where('user_id', $userId)
            ->where('placement_drive_id', $driveId)
            ->first();

        $currentFavorite = $currentRecord ? $currentRecord->is_favorite : false;
        $newFavorite = !$currentFavorite;

        // Update or insert the record
        if ($currentRecord) {
            // Update existing record
            DB::table('saved_drives')
                ->where('user_id', $userId)
                ->where('placement_drive_id', $driveId)
                ->update([
                    'is_favorite' => $newFavorite,
                    'updated_at' => now()
                ]);
        } else {
            // Insert new record
            DB::table('saved_drives')->insert([
                'user_id' => $userId,
                'placement_drive_id' => $driveId,
                'is_favorite' => $newFavorite,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Set flash message
        session()->flash(
            $newFavorite ? 'success' : 'info',
            $newFavorite ? 'Drive saved successfully!' : 'Drive removed from saved!'
        );

        return back();
    }

    public function savedDrives()
    {
        $userId = Auth::id();

        $savedDrives = DB::table('saved_drives')
            ->where('saved_drives.user_id', $userId)
            ->where('saved_drives.is_favorite', true)
            ->join('placement_drive', 'saved_drives.placement_drive_id', '=', 'placement_drive.id')
            ->select('placement_drive.*', 'saved_drives.created_at as saved_at')
            ->orderByDesc('saved_drives.created_at')
            ->get();

        return view('saved_drives', compact('savedDrives'));
    }




    public function checkProfileCompletion()
    {
        // 🔍 Get authenticated user ID
        $userId = Auth::id();

        if (!$userId) return 0;

        // 🔍 Fetch user basic info
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) return 0;

        // 🔍 Fetch extended profile
        $profile = DB::table('users_profile')->where('user_id', $userId)->first();

        /*
    |--------------------------------------------------------------------------
    | 🎯 Define Completion Sections and Weights
    |--------------------------------------------------------------------------
    | You can adjust the weights as needed, but they should sum up to 100%.
    */
        $sections = [
            'basic_info'       => 15,  // users table
            'profile_info'     => 15,  // users_profile
            'academic_history' => 10,  // academic_history table
            'skills'           => 15,
            'resume'           => 15,
            'projects'         => 15,
            'experience'       => 15,
        ];

        $completion = 0;

        /*
    |--------------------------------------------------------------------------
    | 1️⃣ Basic Info Check (users table)
    |--------------------------------------------------------------------------
    */
        $userRequiredFields = [
            'name',
            'email',
            'city',
            'state',
            'country',
            'profile_picture',
            'cover_photo',
        ];

        $userComplete = true;
        foreach ($userRequiredFields as $field) {
            if (empty($user->$field)) {
                $userComplete = false;
                break;
            }
        }

        if ($userComplete) {
            $completion += $sections['basic_info'];
        }

        /*
    |--------------------------------------------------------------------------
    | 2️⃣ Profile Info Check (users_profile table)
    |--------------------------------------------------------------------------
    */
        $profileComplete = false;

        if ($profile) {
            $profileRequiredFields = [
                'college',
                'course',
                'department',
                'gender',
                'roll_number',
                'date_of_birth',
            ];

            $profileComplete = true;
            foreach ($profileRequiredFields as $field) {
                if (empty($profile->$field)) {
                    $profileComplete = false;
                    break;
                }
            }

            if ($profileComplete) {
                $completion += $sections['profile_info'];
            }
        }

        /*
    |--------------------------------------------------------------------------
    | 3️⃣ Academic History
    |--------------------------------------------------------------------------
    */
        if (DB::table('academic_history')->where('user_id', $userId)->exists()) {
            $completion += $sections['academic_history'];
        }

        /*
    |--------------------------------------------------------------------------
    | 4️⃣ Other Sections (Skills, Resume, Projects, Experience)
    |--------------------------------------------------------------------------
    */
        if (DB::table('users_skills')->where('user_id', $userId)->exists()) {
            $completion += $sections['skills'];
        }

        if (DB::table('users_resume')->where('user_id', $userId)->exists()) {
            $completion += $sections['resume'];
        }

        if (DB::table('user_projects')->where('user_id', $userId)->exists()) {
            $completion += $sections['projects'];
        }

        if (DB::table('user_experience')->where('user_id', $userId)->exists()) {
            $completion += $sections['experience'];
        }

        /*
    |--------------------------------------------------------------------------
    | 🔄 Update or Insert Into user_track Table
    |--------------------------------------------------------------------------
    */
        $this->updateUserTrack($userId, $completion);

        return $completion;
    }

    private function updateUserTrack($userId, $completionPercent)
    {
        DB::table('user_profile_track')->updateOrInsert(
            ['user_id' => $userId],
            [
                'completion_percent' => $completionPercent,
                'last_checked_at'    => now(),
                'updated_at'         => now(),
            ]
        );
    }






    public function otpRequest(Request $request)
    {
        return view('auth.otp-request');
    }

    public function otpVerify(Request $request)
    {
        return view('auth.otp-verify');
    }


    public function otpRegistered(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $otp = rand(000000, 999999);
        $message = 'Greetings! Your OTP for registering with PTU Placement is provided below. Please use this OTP to complete your registration within the next 10 minutes. Welcome to the PTU placement portal !';
        $subject = 'Your Registration OTP';


        try {
            $toEmail = $request->input('email');

            session(['otp' => $otp, 'email' => $toEmail]);

            Mail::to($toEmail)->send(new welcomeEmail($message, $subject, $otp));


            return redirect()->route('otp.verify')->with('success', 'OTP sent successfully to ' . $toEmail);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());

            return back()->withErrors(['email' => 'Error sending email: ' . $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        return view('auth.login');
    }



    public function otpVerifed(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'phone' => 'required|numeric|digits_between:1,13',
            'password' => 'required|min:4',
        ]);

        $sessionOtp = session('otp');
        $sessionEmail = session('email');

        if ($request->input('otp') == $sessionOtp) {
            DB::table('users')->insert([
                'name' => $request->input('name'),
                'email' => $sessionEmail,
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password')),
                'is_active' => 1,
                'is_verified' => 0
            ]);

            session()->forget(['otp', 'email']);

            return redirect()->route('login')->with('success', 'User registered successfully!');
        } else {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }
    }

    public function logined(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();

            if (!$user || !isset($user->role)) {
                auth()->logout();
                return back()->withErrors(['email' => 'User role is not defined. Please contact the administrator.']);
            }

            // Check activation and verification status
            if ($user->role === 'user') {
                if (!$user->is_verified) {
                    auth()->logout();
                    return back()->withErrors(['email' => 'Your profile is not verified yet. Please contact the administrator.']);
                }
                if (!$user->is_active) {
                    auth()->logout();
                    return back()->withErrors(['email' => 'Your account is inactive. Please contact the administrator.']);
                }
            } elseif ($user->role === 'placement_officer') {
                if (!$user->is_active) {
                    auth()->logout();
                    return back()->withErrors(['email' => 'Your account is inactive. Please contact the administrator.']);
                }
            }

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('adminDashboard.index')->with('success', 'Welcome to the Admin Dashboard!');
                case 'placement_officer':
                    return redirect()->route('placement.officer.dashboard')->with('success', 'Welcome to the Placement Officer Dashboard!');
                case 'company':
                    return redirect()->route('company.welcome')->with('success', 'Welcome to the Company Dashboard!');
                case 'user':
                    return redirect()->route('index')->with('success', 'Welcome to the Home Page!');
                default:
                    auth()->logout();
                    return back()->withErrors(['message' => 'Unauthorized access. Please contact the administrator.']);
            }
        } else {
            return back()->withErrors(['email' => 'Invalid email or password. Please try again.']);
        }
    }

    public function showForgotPasswordForm()
    {
        return view('auth.request-forget-otp');
    }


    public function forgetPassworded(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Get active user
        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('is_active', 1)
            ->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'No active account found with this email.')
                ->withInput();
        }

        $otp = rand(100000, 999999);
        $toEmail = $request->input('email');
        $name = $user->name;

        // Store OTP
        DB::table('users')
            ->where('email', $toEmail)
            ->update([
                'remember_token' => Hash::make($otp),
                'email_verified_at' => now(),
            ]);

        try {
            // Store in session like your working code
            session(['otp' => $otp, 'email' => $toEmail]);

            // Send email using the same pattern
            Mail::to($toEmail)->send(new SendForget($otp, $name, $toEmail));

            return redirect()->route('forget.otp.verify.form')->with('success', 'OTP sent successfully to ' . $toEmail);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());

            return back()->withErrors(['email' => 'Error sending email: ' . $e->getMessage()]);
        }
    }






    public function forgetOtpVerifyForm()
    {
        if (!session('otp')) {
            return redirect()->route('password.request')->with('error', 'Please request OTP first.');
        }

        return view('auth.verify-forget-otp');
    }

    public function forgetOtpVerify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $user = DB::table('users')
            ->where('email', $request->email)
            ->where('is_active', 1)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found')->withInput();
        }

        // Check if OTP exists and is not expired (10 minutes)
        if (!$user->remember_token || !$user->email_verified_at || now()->diffInMinutes($user->email_verified_at) > 10) {
            return redirect()->back()->with('error', 'OTP has expired. Please request a new one.')->withInput();
        }

        // Verify OTP
        if (!Hash::check($request->otp, $user->remember_token)) {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.')->withInput();
        }

        // Store verification in session for password reset
        $request->session()->put('otp_verified', true);
        $request->session()->put('verified_email', $request->email);

        return redirect()->route('password.reset.form')->with('success', 'OTP verified successfully. Now set your new password.');
    }

    public function showResetPasswordForm()
    {
        if (!session('otp_verified')) {
            return redirect()->route('forget.otp.verify.form')->with('error', 'Please verify OTP first');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!session('otp_verified')) {
            return redirect()->route('forget.otp.verify.form')->with('error', 'Please verify OTP first');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = session('verified_email');

        $updatePassword = DB::table('users')
            ->where('email', $email)
            ->update([
                'password' => Hash::make($request->password),
                'remember_token' => null,
                'email_verified_at' => null,
            ]);

        if ($updatePassword) {
            $request->session()->forget(['otp_verified', 'verified_email', 'otp_verify_email']);
            return redirect()->route('login')->with('success', 'Password reset successfully. Please login with your new password.');
        } else {
            return redirect()->back()->with('error', 'Failed to reset password. Please try again.');
        }
    }



    public function accounts()
    {
        return view('account');
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    public function profile()
    {
        $skills = DB::table('users_skills')
            ->where('user_id', Auth::id())
            ->get();

        $user = Auth::user();

        $academicHistory = DB::table('academic_history')
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        $resumePath = DB::table('users_resume')
            ->where('user_id', Auth::id())
            ->where('is_active', 1)
            ->exists();

        $projects = DB::table('user_projects')
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        $experiences = DB::table('user_experience')
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        $userProfile = DB::table('users_profile')
            ->where('user_id', Auth::id())
            ->first();

        $completionPercent = $this->checkProfileCompletion();

        return view('profile', compact('user', 'skills', 'academicHistory', 'resumePath', 'projects', 'experiences', 'userProfile', 'completionPercent'));
    }

    public function updateProfilePic(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('profile_picture')) {
                // Delete old file if exists
                if ($user->profile_picture) {
                    $oldFilePath = public_path($user->profile_picture);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // Upload new file to temp directory
                $file = $request->file('profile_picture');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $tempPath = 'temp_profile_pic/' . $fileName;
                $finalPath = 'profile_pictures/' . $fileName;

                // Move to temp directory
                $file->move(public_path('temp_profile_pic'), $fileName);

                // Copy from temp to final directory
                $tempFullPath = public_path($tempPath);
                $finalFullPath = public_path($finalPath);

                // Ensure the profile_pictures directory exists
                if (!file_exists(public_path('profile_pictures'))) {
                    mkdir(public_path('profile_pictures'), 0755, true);
                }

                // Copy the file
                copy($tempFullPath, $finalFullPath);

                // Optional: Delete the temp file after copying
                if (file_exists($tempFullPath)) {
                    unlink($tempFullPath);
                }

                // Save new path to database
                DB::table('users')->where('id', $user->id)->update(['profile_picture' => $finalPath]);
            }

            return back()->with('success', 'Profile picture updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile picture.');
        }
    }

    public function updateCoverPic(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->hasFile('cover_photo')) {
                // Delete old file if exists
                if ($user->cover_photo) {
                    $oldFilePath = public_path($user->cover_photo);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                // Upload new file to temp directory
                $file = $request->file('cover_photo');
                $fileName = time() . '.' . $file->getClientOriginalExtension();

                // Move to temp directory first
                $tempDir = 'temp_cover_pic';
                $file->move(public_path($tempDir), $fileName);

                // Define paths
                $tempPath = $tempDir . '/' . $fileName;
                $finalDir = 'cover_photos';
                $finalPath = $finalDir . '/' . $fileName;

                // Create cover_photos directory if it doesn't exist
                if (!file_exists(public_path($finalDir))) {
                    mkdir(public_path($finalDir), 0755, true);
                }

                // Copy from temp to final directory
                copy(public_path($tempPath), public_path($finalPath));

                // Delete temp file
                if (file_exists(public_path($tempPath))) {
                    unlink(public_path($tempPath));
                }

                // Save new path
                DB::table('users')->where('id', $user->id)->update(['cover_photo' => $finalPath]);
            }

            return back()->with('success', 'Cover photo updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating cover photo.');
        }
    }


    public function addSkillForm()
    {
        $skills = DB::table('users_skills')
            ->where('user_id', Auth::id())
            ->get();

        return view('add_skills', compact('skills'));
    }


    public function addSkill(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to add skills.');
        }

        $user = Auth::user();

        $request->validate([
            'skill_name' => 'required|string|max:100',
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert',
        ]);

        // Optional: Prevent duplicate skills
        $exists = DB::table('users_skills')
            ->where('user_id', $user->id)
            ->where('skill_name', $request->input('skill_name'))
            ->exists();

        if ($exists) {
            return back()->with('error', 'This skill already exists.');
        }

        try {
            DB::table('users_skills')->insert([
                'user_id' => $user->id,
                'skill_name' => $request->input('skill_name'),
                'proficiency_level' => $request->input('proficiency_level'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Skill added successfully.');
        } catch (\Exception $e) {
            Log::error('Add skill error: ' . $e->getMessage());
            return back()->with('error', 'Error adding skill.');
        }
    }


    public function editSkill(Request $request, $skill_id)
    {
        $user = Auth::user();

        $request->validate([
            'skill_name' => 'required|string|max:100',
            'proficiency_level' => 'required|in:beginner,intermediate,advanced,expert',
        ]);


        try {
            DB::table('users_skills')
                ->where('id', $skill_id)
                ->where('user_id', $user->id)
                ->update([
                    'skill_name' => $request->input('skill_name'),
                    'proficiency_level' => $request->input('proficiency_level'),
                    'updated_at' => Carbon::now(),
                ]);

            return back()->with('success', 'Skill updated successfully.');
        } catch (\Exception $e) {
            Log::error('Edit skill error: ' . $e->getMessage());
            return back()->with('error', 'Error updating skill.');
        }
    }

    public function deleteSkill($skill_id)
    {
        $user = Auth::user();

        try {
            DB::table('users_skills')
                ->where('id', $skill_id)
                ->where('user_id', $user->id)
                ->delete();

            return back()->with('success', 'Skill deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Delete skill error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting skill.');
        }
    }


    public function academicHistoryForm()
    {
        $academicHistory = DB::table('academic_history')
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        return view('academic_history', compact('academicHistory'));
    }

    public function addAcademicHistory(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to add academic history.');
        }

        $user = Auth::user();

        $request->validate([
            'education_level' => 'required|in:10th,12th,diploma,bachelor,master,phd,other',
            'institution_name' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'grade_type' => 'nullable|in:gpa,percentage',
            'grade' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        try {

            // Check date logic
            if ($request->input('end_date') && $request->input('start_date') && $request->input('end_date') < $request->input('start_date')) {
                return back()->with('error', 'End date cannot be earlier than start date.');
            }

            // Ensure grade_type and grade are both provided
            if (is_null($request->input('grade_type')) || is_null($request->input('grade'))) {
                return back()->with('error', 'Both grade type and grade are required.');
            }

            // Validate grade ranges
            if ($request->input('grade_type') === 'percentage' && ($request->input('grade') < 0 || $request->input('grade') > 100)) {
                return back()->with('error', 'Percentage grade must be between 0 and 100.');
            }

            if ($request->input('grade_type') === 'gpa' && ($request->input('grade') < 0 || $request->input('grade') > 10)) {
                return back()->with('error', 'GPA grade must be between 0 and 10.');
            }
            // Check for null education level
            if (is_null($request->education_level)) {
                // Handle non-null education level
                return back()->with('error', 'Education level cannot be null.');
            }

            // Check for duplicate education level
            $exist_education_level = DB::table('academic_history')
                ->where('user_id', $user->id)
                ->where('education_level', $request->input('education_level'))
                ->where('is_active', true)
                ->first();

            if ($exist_education_level) {
                return back()->with('error', 'Education level already exists in academic history.');
            }




            // Update graduation status
            if ($request->input('education_level') === 'bachelor') {
                DB::table('users_profile')
                    ->where('user_id', $user->id)
                    ->update([
                        'is_graduate' => 1,
                        'updated_at' => now(),
                    ]);
            }

            DB::table('academic_history')->insert([
                'user_id' => $user->id,
                'education_level' => $request->input('education_level'),
                'institution_name' => $request->input('institution_name'),
                'degree' => $request->input('degree'),
                'field_of_study' => $request->input('field_of_study'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'grade_type' => $request->input('grade_type'),
                'grade' => $request->input('grade'),
                'description' => $request->input('description'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Academic history added successfully.');
        } catch (\Exception $e) {
            Log::error('Add academic history error: ' . $e->getMessage());
            return back()->with('error', 'Error adding academic history.');
        }
    }

    public function editAcademicHistory(Request $request, $history_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to edit academic history.');
        }

        $user = Auth::user();

        $request->validate([
            'education_level' => 'required|in:10th,12th,diploma,bachelor,master,phd,other',
            'institution_name' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'grade_type' => 'nullable|in:gpa,percentage',
            'grade' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            $updated = DB::table('academic_history')
                ->where('id', $history_id)
                ->where('user_id', $user->id)
                ->update([
                    'education_level' => $request->input('education_level'),
                    'institution_name' => $request->input('institution_name'),
                    'degree' => $request->input('degree'),
                    'field_of_study' => $request->input('field_of_study'),
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'grade_type' => $request->input('grade_type'),
                    'grade' => $request->input('grade'),
                    'description' => $request->input('description'),
                    'updated_at' => now(),
                ]);

            if ($updated) {
                return back()->with('success', 'Academic history updated successfully.');
            } else {
                return back()->with('error', 'Academic history not found or you do not have permission to edit it.');
            }
        } catch (\Exception $e) {
            Log::error('Edit academic history error: ' . $e->getMessage());
            return back()->with('error', 'Error updating academic history.');
        }
    }

    public function deleteAcademicHistory($history_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to delete academic history.');
        }

        $user = Auth::user();

        try {
            $deleted = DB::table('academic_history')
                ->where('id', $history_id)
                ->where('user_id', $user->id)
                ->update(['is_active' => false]);

            if ($deleted) {
                return back()->with('success', 'Academic history deleted successfully.');
            } else {
                return back()->with('error', 'Academic history not found or you do not have permission to delete it.');
            }
        } catch (\Exception $e) {
            Log::error('Delete academic history error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting academic history.');
        }
    }

    public function resumeForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your resumes.');
        }

        $user = Auth::user();
        $resumes = DB::table('users_resume')
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->orderBy('is_default', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('resume', compact('resumes'));
    }

    public function uploadResume(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to upload a resume.');
        }

        $user = Auth::user();

        $request->validate([
            'resume' => 'required|mimes:pdf,doc,docx|max:8048',
            'resume_title' => 'nullable|string|max:255',
        ]);

        try {
            if ($request->hasFile('resume')) {
                // Upload new file to temp directory
                $file = $request->file('resume');
                $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

                // Move to temp directory first
                $tempDir = 'temp_resume';
                $file->move(public_path($tempDir), $fileName);

                // Define paths
                $tempPath = $tempDir . '/' . $fileName;
                $finalDir = 'resumes';
                $finalPath = $finalDir . '/' . $fileName;

                // Create resumes directory if it doesn't exist
                if (!file_exists(public_path($finalDir))) {
                    mkdir(public_path($finalDir), 0755, true);
                }

                // Copy from temp to final directory
                copy(public_path($tempPath), public_path($finalPath));

                // Delete temp file
                if (file_exists(public_path($tempPath))) {
                    unlink(public_path($tempPath));
                }

                $isDefault = $request->has('is_default') ? 1 : 0;

                $resumeId = DB::table('users_resume')->insertGetId([
                    'resume_path'   => $finalPath,
                    'resume_title'  => $request->input('resume_title'),
                    'user_id'       => $user->id,
                    'is_active'     => 1,
                    'is_default'    => $isDefault,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                if ($isDefault) {
                    DB::table('users_resume')
                        ->where('user_id', $user->id)
                        ->where('id', '!=', $resumeId)
                        ->update(['is_default' => 0]);
                }

                return back()->with('success', 'Resume uploaded successfully.');
            }

            return back()->with('error', 'No resume file found.');
        } catch (\Exception $e) {
            Log::error('Resume upload error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->with('error', 'Error uploading resume.');
        }
    }





    public function editResume(Request $request)
    {
        $user = Auth::user();
        $request->validate(['resume_id' => 'required|exists:users_resume,id']);

        try {
            $resumeId = $request->input('resume_id');
            // Set all other resumes to non-default
            DB::table('users_resume')
                ->where('user_id', $user->id)
                ->where('id', '!=', $resumeId)
                ->update(['is_default' => 0]);

            // Set the selected resume as default
            DB::table('users_resume')
                ->where('id', $resumeId)
                ->where('user_id', $user->id)
                ->update(['is_default' => 1]);

            return back()->with('success', 'Resume updated successfully.');
        } catch (\Exception $e) {
            Log::error('Resume update error: ' . $e->getMessage());
            return back()->with('error', 'Error updating resume.');
        }
    }

    public function deleteResume(Request $request)
    {
        $user = Auth::user();
        $request->validate(['resume_id' => 'required|exists:users_resume,id']);

        try {
            $resumeId = $request->input('resume_id');
            DB::table('users_resume')
                ->where('id', $resumeId)
                ->where('user_id', $user->id)
                ->update(['is_active' => 0, 'is_default' => 0]);


            return back()->with('success', 'Resume deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Delete resume error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting resume.');
        }
    }



   /**
 * Show profile edit page with data
 */
public function userProfileeditShow(Request $request)
{
    $userId = Auth::id();

    // Get user profile - create empty object if doesn't exist
    $profile = DB::table('users_profile')->where('user_id', $userId)->first();

    // If no profile exists, create an empty stdClass object
    if (!$profile) {
        $profile = (object) [
            'bio' => null,
            'gender' => null,
            'date_of_birth' => null,
            'roll_number' => null,
            'college' => null,
            'course' => null,
            'department' => null,
            'passing_year' => null,
            'cgpa' => null,
        ];
    }

    // Get user data
    $user = DB::table('users')->where('id', $userId)->first();

    // Get dropdown data
    $colleges = DB::table('college')->get();
    $courses = DB::table('courses')->get();
    $departments = DB::table('departments')->get();

    if ($request->ajax()) {
        if ($request->filled('college_id') && !$request->filled('course_id')) {
            $collegeId = $request->input('college_id');
            $courses = DB::table('courses')
                ->where('college_id', $collegeId)
                ->get();

            $departments = collect();

            return response()->json([
                'courses' => $courses,
                'departments' => $departments,
            ]);
        } elseif ($request->filled('course_id')) {
            $courseId = $request->input('course_id');

            $courses = DB::table('courses')
                ->where('college_id', $request->input('college_id'))
                ->get();

            $departments = DB::table('departments')
                ->where('courses_id', $courseId) // Note: using courses_id (not course_id)
                ->get();

            return response()->json([
                'courses' => $courses,
                'departments' => $departments,
            ]);
        }
    }

    return view('profile_edits', compact('profile', 'user', 'colleges', 'courses', 'departments'));
}

/**
 * Update or Insert Academic Info
 */
public function updateAcademic(Request $request)
{
    $userId = Auth::id();
    $now = now();

    // First, validate basic fields
    $request->validate([
        'gender' => 'required|in:male,female,other',
        'date_of_birth' => 'required|date|before_or_equal:today',
        'roll_number' => 'required|string|max:50',
        'college_id' => 'required|exists:college,id',
        'course_id' => 'required|exists:courses,id',
        'department_id' => 'required|exists:departments,id',
        'passing_year' => 'required|integer|min:2000|max:2030',
        'cgpa' => 'required|numeric|min:0|max:10',
        'is_reappear' => 'required|in:0,1',
    ]);

    // Validate hierarchical relationships
    $errors = [];
    
    // Check if course belongs to selected college
    $course = DB::table('courses')
        ->where('id', $request->course_id)
        ->where('college_id', $request->college_id)
        ->first();
    
    if (!$course) {
        $errors['course_id'] = 'The selected course does not belong to the selected college.';
    }

    // Check if department belongs to selected course
    $department = DB::table('departments')
        ->where('id', $request->department_id)
        ->where('courses_id', $request->course_id)
        ->first();
    
    if (!$department) {
        $errors['department_id'] = 'The selected department does not belong to the selected course.';
    }

    // If there are validation errors, redirect back with errors
    if (!empty($errors)) {
        return redirect()->back()
            ->withErrors($errors)
            ->withInput();
    }

    try {
        DB::beginTransaction();

        // Check if profile exists
        $existingProfile = DB::table('users_profile')->where('user_id', $userId)->first();

        $academicData = [
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'roll_number' => $request->roll_number,
            'college' => $request->college_id,
            'course' => $request->course_id,
            'department' => $request->department_id,
            'passing_year' => $request->passing_year,
            'cgpa' => $request->cgpa,
            'updated_at' => $now,
        ];

        if ($existingProfile) {
            // Update existing profile
            DB::table('users_profile')
                ->where('user_id', $userId)
                ->update($academicData);
        } else {
            // Insert new profile
            $academicData['user_id'] = $userId;
            $academicData['created_at'] = $now;
            DB::table('users_profile')->insert($academicData);
        }

        // Update user table with reappear status
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'is_reappear' => $request->is_reappear,
                'updated_at' => $now,
            ]);

        DB::commit();

        return back()->with('success', 'Academic details saved successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Failed to save academic details: ' . $e->getMessage());
        return back()->with('error', 'Failed to save academic details. Please try again.')
            ->withInput();
    }
}

    /**
     * Update or Insert Bio Info
     */
    public function updateBio(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:500',
        ]);


        try {

            // Check if profile exists
            $existingProfile = DB::table('users_profile')->where('user_id', Auth::user()->id)->first();

            if ($existingProfile) {
                // Update existing profile
                DB::table('users_profile')
                    ->where('user_id', Auth::user()->id)
                    ->update([
                        'bio' => $request->bio,
                        'updated_at' => now()
                    ]);
            } else {
                // Insert new profile with only bio data
                DB::table('users_profile')->insert([
                    'user_id' => Auth::user()->id,
                    'bio' => $request->bio,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return back()->with('success', 'Bio saved successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to save bio: ' . $e->getMessage());
            return back()->with('error', 'Failed to save bio. Please try again.');
        }
    }


    /**
     * Update Address Info
     */
    public function updateAddress(Request $request)
    {
        $request->validate([
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        try {
            $userId = Auth::id();

            $addressData = [
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'updated_at' => now(),
            ];

            DB::table('users')
                ->where('id', $userId)
                ->update($addressData);

            return back()->with('success', 'Address updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update address: ' . $e->getMessage());
            return back()->with('error', 'Failed to update address. Please try again.');
        }
    }








    public function editProfile(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'college_id' => 'required|exists:college,id',
                'course_id' => 'required|exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'gender' => 'required|string|in:male,female,other',
                'roll_number' => 'required|string|max:255',
                'date_of_birth' => 'required|date',

            ]);

            $userId = Auth::id();

            $existing = DB::table('users_profile')->where('user_id', $userId)->first();

            $data = [
                'user_id' => $userId,
                'college' => $request->college_id,
                'course' => $request->course_id,
                'department' => $request->department_id,
                'gender' => $request->gender,
                'roll_number' => $request->roll_number,
                'date_of_birth' => $request->date_of_birth,
                'updated_at' => now(),
            ];

            if ($existing) {
                // Update existing record
                DB::table('users_profile')->where('user_id', $userId)->update($data);
            } else {
                // Insert new record
                $data['created_at'] = now();
                DB::table('users_profile')->insert($data);
            }

            return redirect()->route('profile')->with('success', 'Profile saved successfully!');
        } catch (\Exception $e) {
            // Optionally log error for debugging
            Log::error('Profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->with('error', 'An error occurred while saving your profile. Please try again.');
        }
    }


    public function written()
    {
        return view('handwritten');
    }


    // public function for edit


    public function projectForm()
    {
        $projects = DB::table('user_projects')
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        return view('add_projects', compact('projects'));
    }

    public function addProject(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to add projects.');
        }

        $user = Auth::user();

        $request->validate([
            'tittle' => 'required|string|max:255',
            'description' => 'required|string',
            'skills' => 'required|array|min:1',
            'skills.*' => 'string|max:100',
            'project_url' => 'required|url|unique:user_projects,project_url|starts_with:https://',
        ], [
            'tittle.required' => 'Project title is required',
            'description.required' => 'Description is required only 100 characters.',
            'skills.required' => 'Please add at least one skill',
            'skills.min' => 'Please add at least one skill',
            'project_url.required' => 'Project URL is required',
            'project_url.url' => 'Please enter a valid URL',
            'project_url.starts_with' => 'URL must start with https://',
            'project_url.unique' => 'This project URL already exists',
        ]);

        try {
            DB::table('user_projects')->insert([
                'user_id' => $user->id,
                'tittle' => $request->input('tittle'),
                'description' => $request->input('description'),
                'skills' => json_encode($request->input('skills')),
                'project_url' => $request->input('project_url'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Project added successfully.');
        } catch (\Exception $e) {
            Log::error('Add project error: ' . $e->getMessage());
            return back()->with('error', 'Error adding project.');
        }
    }
    public function editProject(Request $request, $project_id)
    {
        $user = Auth::user();

        $request->validate([
            'tittle' => 'required|string|max:255',
            'description' => 'required|string',
            'skills' => 'required|array|min:1',
            'skills.*' => 'string|max:100',
            'project_url' => "required|url|unique:user_projects,project_url,$project_id",
        ]);

        try {
            DB::table('user_projects')
                ->where('id', $project_id)
                ->where('user_id', $user->id)
                ->update([
                    'tittle' => $request->input('tittle'),
                    'description' => $request->input('description'),
                    'skills' => json_encode($request->input('skills')),
                    'project_url' => $request->input('project_url'),
                    'updated_at' => Carbon::now(),
                ]);

            return back()->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            Log::error('Edit project error: ' . $e->getMessage());
            return back()->with('error', 'Error updating project.');
        }
    }

    public function deleteProject($project_id)
    {
        $user = Auth::user();

        try {
            DB::table('user_projects')
                ->where('id', $project_id)
                ->where('user_id', $user->id)
                ->update([
                    'is_active' => false,
                    'updated_at' => Carbon::now(),
                ]);

            return back()->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Delete project error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting project.');
        }
    }



    public function exprience()
    {
        $experiences = DB::table('user_experience')
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->get();

        return view('add_experience', compact('experiences'));
    }

    public function addExperience(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to add experience.');
        }

        $user = Auth::user();

        $request->validate([
            'experience_level' => 'required|string|max:255',
            'tittle' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
        ]);

        try {
            DB::table('user_experience')->insert([
                'user_id' => $user->id,
                'experience_level' => $request->input('experience_level'),
                'tittle' => $request->input('tittle'),
                'company_name' => $request->input('company_name'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date') ?: null,
                'description' => $request->input('description'),
                'skills' => $request->input('skills') ? json_encode($request->input('skills')) : null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Experience added successfully.');
        } catch (\Exception $e) {
            Log::error('Add experience error: ' . $e->getMessage());
            Log::error('Request data: ', $request->all());
            return back()->with('error', 'Error adding experience: ' . $e->getMessage())->withInput();
        }
    }

    public function editExprience(Request $request, $experience_id)
    {
        $user = Auth::user();

        $request->validate([
            'experience_level' => 'required|string|max:255',
            'tittle' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
        ]);

        try {
            DB::table('user_experience')
                ->where('id', $experience_id)
                ->where('user_id', $user->id)
                ->update([
                    'experience_level' => $request->input('experience_level'),
                    'tittle' => $request->input('tittle'),
                    'company_name' => $request->input('company_name'),
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'description' => $request->input('description'),
                    'skills' => $request->input('skills') ? json_encode($request->input('skills')) : null,
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Experience updated successfully.');
        } catch (\Exception $e) {
            Log::error('Edit experience error: ' . $e->getMessage());
            return back()->with('error', 'Error updating experience.');
        }
    }

    public function deleteExperience($experience_id)
    {
        $user = Auth::user();

        try {
            DB::table('user_experience')
                ->where('id', $experience_id)
                ->where('user_id', $user->id)
                ->update(['is_active' => false]);

            return back()->with('success', 'Experience deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Delete experience error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting experience.');
        }
    }





    public function showUserStats()
    {
        // ✅ Only allow logged-in users
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your statistics.');
        }

        $user = Auth::user();

        // --- Example: if you want stats only for this user's applications ---
        $query = DB::table('placement_applications')->where('student_id', $user->id);

        // --- Overall application counts ---
        $stats = $query->select(
            DB::raw("COUNT(CASE WHEN application_status = 'applied' THEN 1 END) as applied_count"),
            DB::raw("COUNT(CASE WHEN application_status = 'shortlisted' THEN 1 END) as shortlisted_count"),
            DB::raw("COUNT(CASE WHEN application_status = 'rejected' THEN 1 END) as rejected_count"),
            DB::raw("COUNT(CASE WHEN application_status = 'selected' THEN 1 END) as selected_count"),
            DB::raw("COUNT(*) as total_count")
        )->first();

        // --- Build 6-month rolling trend ---
        $monthsBack = 5;
        $startDate = now()->startOfMonth()->subMonths($monthsBack);

        $rawTrends = $query->selectRaw("YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count")
            ->whereNotNull('created_at')
            ->where('created_at', '>=', $startDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $trendLabels = [];
        $trendCounts = [];

        for ($i = $monthsBack; $i >= 0; $i--) {
            $dt = Carbon::now()->startOfMonth()->subMonths($i);
            $label = $dt->format('M');
            $year = (int)$dt->format('Y');
            $month = (int)$dt->format('n');

            $trendLabels[] = $label;
            $row = $rawTrends->first(fn($r) => (int)$r->year === $year && (int)$r->month === $month);
            $trendCounts[] = $row->count ?? 0;
        }

        // --- Send data to Blade ---
        return view('stats', compact('stats', 'trendLabels', 'trendCounts'));
    }






    public function displayAnnouncementsList()
    {
        $announcements = DB::table('announcements')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            })
            ->orderBy('priority', 'desc')
            ->orderBy('publish_date', 'desc')
            ->get();

        $recentAnnouncements = DB::table('announcements')
            ->where('type', 'announcement')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            })
            ->orderBy('publish_date', 'desc')
            ->limit(5)
            ->get();

        $recentNotices = DB::table('announcements')
            ->where('type', 'notice')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            })
            ->orderBy('publish_date', 'desc')
            ->limit(5)
            ->get();

        return view('announcements.index', compact('announcements', 'recentAnnouncements', 'recentNotices'));
    }

    public function showAnnouncementDetail($id)
    {
        $announcement = DB::table('announcements')
            ->where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$announcement) {
            abort(404);
        }

        return view('announcements.show', compact('announcement'));
    }
}

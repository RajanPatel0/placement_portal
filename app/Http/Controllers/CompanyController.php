<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\welcomeEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\CompanyShortlisted;
use App\Mail\CompanySelected;


class CompanyController extends Controller
{

    public function companyWelcome()
{
    try {
        // Get the authenticated user
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Get company's latest active drive
        $company = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc') // Latest drive first
            ->first();

        if (!$company) {
            return view('companyDashboard.welcome', [
                'appliedCount' => 0,
                'shortlistedCount' => 0,
                'selectedCount' => 0,
                'selectionPendingCount' => 0,
                'company' => null,
                'totalDrives' => 0,
                'upcomingDrives' => 0
            ]);
        }

        // Get all company drives for stats (just for info)
        $allCompanyDrives = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->get();

        $totalDrives = $allCompanyDrives->count();
        $upcomingDrives = $allCompanyDrives->filter(function ($drive) {
            $driveDate = $drive->drive_date ? \Carbon\Carbon::parse($drive->drive_date) : null;
            return $driveDate && $driveDate->isFuture();
        })->count();

        // Initialize counts for latest drive only
        $appliedCount = 0;
        $shortlistedCount = 0;
        $selectedCount = 0;
        $selectionPendingCount = 0;

        // ✅ Count total applications for latest drive only
        $appliedCount = DB::table('placement_applications')
            ->where('placement_drive_id', $company->id)
            ->count();

        // ✅ Count shortlisted for latest drive only
        $shortlistedCount = DB::table('placement_applications')
            ->where('placement_drive_id', $company->id)
            ->whereNotNull('shortlisted_at')
            ->count();

        // ✅ Count selected for latest drive only
        $selectedCount = DB::table('placement_applications')
            ->where('placement_drive_id', $company->id)
            ->whereNotNull('selected_at')
            ->count();

        // ✅ Count pending (not shortlisted or selected) for latest drivekonly
        $selectionPendingCount = DB::table('placement_applications')
            ->where('placement_drive_id', $company->id)
            ->whereNull('shortlisted_at')
            ->whereNull('selected_at')
            ->count();

        // ✅ Return view with data
        return view('companyDashboard.welcome', compact(
            'appliedCount',
            'shortlistedCount',
            'selectedCount',
            'selectionPendingCount',
            'company',
            'totalDrives',
            'upcomingDrives'
        ));
    } catch (\Exception $e) {
        Log::error('Error fetching dashboard data: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error fetching dashboard data.');
    }
}

    public function allDrives()
    {
        $user = Auth::user();

        // Method 1: Using company_user_id (best approach)
        $getAllDrives = DB::table('placement_drive')
            ->where('company_user_id', $user->id)
            ->orderBy('drive_date', 'desc')
            ->get();

        // OR Method 2: Using contact_email
        if ($getAllDrives->isEmpty()) {
            $getAllDrives = DB::table('placement_drive')
                ->where('contact_email', $user->email)
                ->orderBy('drive_date', 'desc')
                ->get();
        }

        return view('companyDashboard.viewAllDrives', compact('getAllDrives'));
    }

    /**
     * View detailed information of a specific drive
     */
    /**
     * View detailed information of a specific drive (Read Only)
     */
    /**
     * View detailed information of a specific drive (Read Only)
     */
    public function detailedViewDrive($driveId)
    {
        $user = Auth::user();

        // Verify the company user owns this drive
        $drive = DB::table('placement_drive')
            ->where('id', $driveId)
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->first();

        if (!$drive) {
            return redirect()->route('company.all.drives')->with('error', 'Drive not found or access denied.');
        }

        // Get application statistics for this drive (Read Only)
        $applicationCount = DB::table('placement_applications')
            ->where('placement_drive_id', $driveId)
            ->count();

        $shortlistedCount = DB::table('placement_applications')
            ->where('placement_drive_id', $driveId)
            ->whereNotNull('shortlisted_at')
            ->count();

        $selectedCount = DB::table('placement_applications')
            ->where('placement_drive_id', $driveId)
            ->whereNotNull('selected_at')
            ->count();

        // Decode JSON fields for display only
        $courseIds = json_decode($drive->course_id, true) ?? [];
        $departmentIds = json_decode($drive->department_id, true) ?? [];
        $campusIds = json_decode($drive->campus_id, true) ?? [];
        $requiredSkills = json_decode($drive->required_skills, true) ?? [];
        $eligibilityPassingYears = json_decode($drive->eligibility_passing_year, true) ?? [];

        // Get course names for display
        $courseNames = [];
        if (!empty($courseIds)) {
            $courseNames = DB::table('courses')
                ->whereIn('id', $courseIds)
                ->pluck('name')
                ->toArray();
        }

        // Get department names for display
        $departmentNames = [];
        if (!empty($departmentIds)) {
            $departmentNames = DB::table('departments')
                ->whereIn('id', $departmentIds)
                ->pluck('name')
                ->toArray();
        }

        // Get campus names for display
        $campusNames = [];
        if (!empty($campusIds)) {
            $campusNames = DB::table('campus')
                ->whereIn('id', $campusIds)
                ->pluck('name')
                ->toArray();
        }

        // Get creator name for display
        $creatorName = null;
        if ($drive->created_by) {
            $creator = DB::table('users')
                ->where('id', $drive->created_by)
                ->first();
            $creatorName = $creator ? $creator->name : 'Unknown';
        }

        // Determine drive status for display
        $driveDate = $drive->drive_date ? \Carbon\Carbon::parse($drive->drive_date) : null;
        $isUpcoming = $driveDate && $driveDate->isFuture();
        $isPast = $driveDate && $driveDate->isPast();

        if ($isUpcoming) {
            $status = 'Upcoming';
            $statusClass = 'success';
        } elseif ($isPast) {
            $status = 'Completed';
            $statusClass = 'secondary';
        } else {
            $status = 'Ongoing';
            $statusClass = 'info';
        }

        // Format drive type for display
        $driveTypeMap = [
            'on_campus' => 'On Campus',
            'off_campus' => 'Off Campus',
            'virtual' => 'Virtual'
        ];
        $driveType = $driveTypeMap[$drive->drive_type] ?? $drive->drive_type;

        return view('companyDashboard.viewAlldrive_detailed', compact(
            'drive',
            'applicationCount',
            'shortlistedCount',
            'selectedCount',
            'courseNames',
            'departmentNames',
            'campusNames',
            'requiredSkills',
            'eligibilityPassingYears',
            'creatorName',
            'status',
            'statusClass',
            'driveType'
        ));
    }

    // public function getAppicationForCompany()
    // {
    //     // Get company ID - use first() to get single value
    //     $companyId = DB::table('users')->where('id', Auth::user()->id)->value('company_id');

    //     if (!$companyId) {
    //         return redirect()->back()->with('error', 'Company profile not found.');
    //     }

    //     // Get company details - verify the company 
    //     $company = DB::table('placement_drive')
    //         ->where('id', $companyId)
    //         ->first();

    //     if (!$company) {
    //         return redirect()->back()->with('error', 'Drive not found or access denied.');
    //     }

    //     // Decode JSON fields for display
    //     $company->course_id = json_decode($company->course_id, true) ?? [];
    //     $company->department_id = json_decode($company->department_id, true) ?? [];
    //     $company->eligibility_passing_year = json_decode($company->eligibility_passing_year, true) ?? [];
    //     $company->required_skills = json_decode($company->required_skills, true) ?? [];

    //     // Get applications with student data - only college approved applications
    //     $applicationData = DB::table('placement_applications')
    //         ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
    //         ->join('users', 'placement_applications.student_id', '=', 'users.id')
    //         ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
    //         ->leftJoin('college', 'users_profile.college', '=', 'college.id') // Fixed: college_id
    //         ->leftJoin('courses', 'users_profile.course', '=', 'courses.id') // Fixed: course_id
    //         ->leftJoin('departments', 'users_profile.department', '=', 'departments.id') // Fixed: department_id
    //         ->leftJoin('users_resume', function ($join) {
    //             $join->on('users_resume.user_id', '=', 'users.id')
    //                 ->where('users_resume.is_active', true)
    //                 ->whereRaw('users_resume.id = (
    //                      SELECT MAX(ur2.id) FROM users_resume ur2 
    //                      WHERE ur2.user_id = users_resume.user_id AND ur2.is_active = true
    //                  )');
    //         })
    //         ->where('placement_drive.id', $companyId)
    //         ->where('placement_applications.college_by', 1) // Only college approved
    //         ->where('placement_applications.application_status', 'applied')
    //         ->select(
    //             'placement_applications.id as application_id',
    //             'users.id as student_id',
    //             'users.name as student_name',
    //             'users.email as student_email',
    //             'users_profile.roll_number',
    //             'college.name as college_name',
    //             'courses.name as course_name',
    //             'departments.name as department_name',
    //             'users_profile.passing_year',
    //             'users_profile.cgpa',
    //             'placement_drive.company_name',
    //             'placement_drive.job_title',
    //             'placement_drive.drive_date',
    //             'placement_drive.package_offered',
    //             'placement_drive.eligibility_cgpa',
    //             'placement_drive.required_skills',
    //             'placement_applications.application_status',
    //             'placement_applications.applied_at',
    //             'placement_applications.college_by',
    //             'placement_applications.company_by',
    //             'placement_applications.shortlisted_at',
    //             'placement_applications.application_responses', // Added this field
    //             'users_resume.resume_path'
    //         )
    //         ->orderBy('placement_applications.applied_at', 'desc')
    //         ->get();

    //     // Get skills for all students in one query
    //     $studentIds = $applicationData->pluck('student_id')->unique()->toArray();
    //     $skillsData = DB::table('users_skills')
    //         ->whereIn('user_id', $studentIds)
    //         ->select('user_id', 'skill_name')
    //         ->get();

    //     // Group skills by student ID
    //     $studentSkills = [];
    //     foreach ($skillsData as $skill) {
    //         $studentSkills[$skill->user_id][] = $skill->skill_name;
    //     }

    //     // Get filter options from the actual tables
    //     $allSkills = DB::table('users_skills')->distinct()->pluck('skill_name')->sort();
    //     $allCourses = DB::table('courses')->distinct()->pluck('name')->sort();
    //     $allDepartments = DB::table('departments')->distinct()->pluck('name')->sort();
    //     $allColleges = DB::table('college')->distinct()->pluck('name')->sort();

    //     return view('companyDashboard.index', compact(
    //         'applicationData',
    //         'studentSkills',
    //         'company',
    //         'allSkills',
    //         'allCourses',
    //         'allDepartments',
    //         'allColleges'
    //     ));
    // }

    public function getAppicationForCompany()
    {
        $user = Auth::user();

        // Get company's latest active drive
        $company = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->first();

        if (!$company) {
            return redirect()->back()->with('error', 'No active drive found.');
        }

        // Get all company drives for dropdown
        $allCompanyDrives = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->get();

        // Decode JSON fields
        $company->course_id = json_decode($company->course_id, true) ?? [];
        $company->department_id = json_decode($company->department_id, true) ?? [];
        $company->eligibility_passing_year = json_decode($company->eligibility_passing_year, true) ?? [];
        $company->required_skills = json_decode($company->required_skills, true) ?? [];

        // Get applications for this drive
        $applicationData = DB::table('placement_applications')
            ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->leftJoin('college', 'users_profile.college', '=', 'college.id')
            ->leftJoin('courses', 'users_profile.course', '=', 'courses.id')
            ->leftJoin('departments', 'users_profile.department', '=', 'departments.id')
            ->leftJoin('users_resume', function ($join) {
                $join->on('users_resume.user_id', '=', 'users.id')
                    ->where('users_resume.is_active', true)
                    ->whereRaw('users_resume.id = (
             SELECT MAX(ur2.id) FROM users_resume ur2 
             WHERE ur2.user_id = users_resume.user_id AND ur2.is_active = true
         )');
            })
            ->where('placement_drive.id', $company->id)
            ->where('placement_applications.college_by', 1)
            ->where('placement_applications.application_status', 'applied')
            ->select(
                'placement_applications.id as application_id',
                'users.id as student_id',
                'users.name as student_name',
                'users.email as student_email',
                'users.is_reappear',
                'users_profile.roll_number',
                'college.name as college_name',
                'courses.name as course_name',
                'departments.name as department_name',
                'users_profile.passing_year',
                'users_profile.cgpa',
                'users_profile.is_graduate',
                'users_profile.college as college_id',
                'users_profile.course as course_id',
                'users_profile.department as department_id',
                'placement_drive.company_name',
                'placement_drive.job_title',
                'placement_drive.drive_date',
                'placement_drive.package_offered',
                'placement_drive.is_reappear as drive_is_reappear',
                'placement_drive.eligibility_cgpa',
                'placement_drive.tenth_percentage',
                'placement_drive.twelfth_percentage',
                'placement_drive.graduation_percentage',
                'placement_drive.college_id as drive_college_id',
                'placement_drive.required_skills',
                'placement_applications.application_status',
                'placement_applications.applied_at',
                'placement_applications.college_by',
                'placement_applications.company_by',
                'placement_applications.shortlisted_at',
                'placement_applications.application_responses',
                'users_resume.resume_path'
            )
            ->orderBy('placement_applications.applied_at', 'desc')
            ->get();

        // Get academic history
        $studentIds = $applicationData->pluck('student_id')->unique()->toArray();
        $academicHistory = DB::table('academic_history')
            ->whereIn('user_id', $studentIds)
            ->where('is_active', true)
            ->select('user_id', 'education_level', 'grade', 'grade_type')
            ->get()
            ->groupBy('user_id');

        // Get skills
        $skillsData = DB::table('users_skills')
            ->whereIn('user_id', $studentIds)
            ->select('user_id', 'skill_name')
            ->get();

        $studentSkills = [];
        foreach ($skillsData as $skill) {
            $studentSkills[$skill->user_id][] = $skill->skill_name;
        }

        // Get filter options
        $allSkills = DB::table('users_skills')->distinct()->pluck('skill_name')->unique()->sort()->values();
        $allCourses = DB::table('courses')->whereIn('id', $company->course_id)->pluck('name')->sort();
        $allDepartments = DB::table('departments')->whereIn('id', $company->department_id)->pluck('name')->sort();
        $allColleges = DB::table('college')->distinct()->pluck('name')->sort();
        $allYears = DB::table('users_profile')
            ->whereIn('user_id', $studentIds)
            ->distinct()
            ->pluck('passing_year')
            ->sort()
            ->values();

        return view('companyDashboard.index', compact(
            'applicationData',
            'studentSkills',
            'academicHistory',
            'company',
            'allCompanyDrives',
            'allSkills',
            'allCourses',
            'allDepartments',
            'allColleges',
            'allYears'
        ));
    }
// Method to update company_by status and shortlist students
// Method to update company_by status and shortlist students
public function updateCompanyBy(Request $request)
{
    $request->validate([
        'student_ids' => 'nullable|array',
        'student_ids.*' => 'exists:users,id',
        'placement_drive_id' => 'required|exists:placement_drive,id'
    ]);

    // First, check if the drive is completed
    $drive = DB::table('placement_drive')
        ->where('id', $request->placement_drive_id)
        ->first();

    // Check if drive exists
    if (!$drive) {
        return redirect()->back()->with('error', 'Drive not found');
    }

    // Check if drive is completed
    if ($drive->status == 'completed') {
        return redirect()->back()->with('error', 'Cannot perform action. The drive has already been completed.');
    }

    $user = Auth::user();

    // Verify the company user owns this drive
    $drive = DB::table('placement_drive')
        ->where('id', $request->placement_drive_id)
        ->where(function ($query) use ($user) {
            $query->where('company_user_id', $user->id)
                ->orWhere('contact_email', $user->email);
        })
        ->first();

    if (!$drive) {
        return redirect()->back()->with('error', 'Access denied or drive not found');
    }

    try {
        // Get ALL applications for this drive that are still in 'applied' status
        $allApplications = DB::table('placement_applications')
            ->where('placement_drive_id', $request->placement_drive_id)
            ->where('college_by', 1)
            ->where('application_status', 'applied') // Only update applied applications
            ->get();

        if ($allApplications->isEmpty()) {
            return redirect()->back()->with('error', 'No valid applications found to process');
        }

        $updatedCount = 0;
        $rejectedCount = 0;
        $alreadyProcessed = 0;

        // Process shortlisted students
        foreach ($allApplications as $application) {
            // Check if student is in the shortlist request
            $isShortlisted = in_array($application->student_id, $request->student_ids);
            
            if ($isShortlisted) {
                // SHORTLIST LOGIC
                // Check if already shortlisted or selected
                if ($application->application_status === 'shortlisted' || $application->application_status === 'selected') {
                    $alreadyProcessed++;
                    continue;
                }

                // Decode existing responses or start with empty array
                $currentResponses = json_decode($application->application_responses, true) ?? [];

                // Preserve the original message and add shortlisting info
                $updatedResponses = array_merge($currentResponses, [
                    'shortlisted_at' => now()->format('Y-m-d H:i:s'),
                    'shortlisted_message' => "Congratulations! You have been shortlisted by {$drive->company_name} for the next round of selection process.",
                    'company_action' => 'shortlisted',
                    'status_update' => 'moved_to_next_round',
                    'next_steps' => 'Please check your registered email for further communication regarding interview schedule and process.',
                    'last_updated' => now()->format('Y-m-d H:i:s')
                ]);

                $updateResult = DB::table('placement_applications')
                    ->where('id', $application->id)
                    ->update([
                        'company_by' => 1,
                        'application_status' => 'shortlisted',
                        'shortlisted_at' => now(),
                        'application_responses' => json_encode($updatedResponses),
                        'updated_at' => now()
                    ]);

                if ($updateResult) {
                    // Create notification for student
                    DB::table('drive_notifications')->insert([
                        'drive_id' => $request->placement_drive_id,
                        'user_id' => $application->student_id,
                        'message' => "Congratulations! You have been shortlisted by {$drive->company_name} for the next round.",
                        'read_at' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $updatedCount++;
                }
            } else {
                // REJECT LOGIC - for students NOT in the shortlist
                // Check if already rejected, shortlisted or selected
                if ($application->application_status === 'rejected' || 
                    $application->application_status === 'shortlisted' || 
                    $application->application_status === 'selected') {
                    $alreadyProcessed++;
                    continue;
                }

                // Decode existing responses or start with empty array
                $currentResponses = json_decode($application->application_responses, true) ?? [];

                // Add rejection info
                $updatedResponses = array_merge($currentResponses, [
                    'rejected_at' => now()->format('Y-m-d H:i:s'),
                    'rejection_message' => "Thank you for your interest. After careful consideration, we regret to inform you that you have not been shortlisted for the next round.",
                    'company_action' => 'rejected',
                    'status_update' => 'application_reviewed',
                    'feedback' => 'We encourage you to apply for future opportunities.',
                    'last_updated' => now()->format('Y-m-d H:i:s')
                ]);

                $updateResult = DB::table('placement_applications')
                    ->where('id', $application->id)
                    ->update([
                        'company_by' => 1,
                        'application_status' => 'rejected',
                        
                        'application_responses' => json_encode($updatedResponses),
                        'updated_at' => now()
                    ]);

                if ($updateResult) {
                    // Create notification for student
                    DB::table('drive_notifications')->insert([
                        'drive_id' => $request->placement_drive_id,
                        'user_id' => $application->student_id,
                        'message' => "Your application for {$drive->company_name} has been reviewed. You were not selected for the next round.",
                        'read_at' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $rejectedCount++;
                }
            }
        }

        $message = '';
        
        if ($updatedCount > 0) {
            $message .= 'Successfully shortlisted ' . $updatedCount . ' student(s)';
        }
        
        if ($rejectedCount > 0) {
            if (!empty($message)) $message .= ' and ';
            $message .= 'marked ' . $rejectedCount . ' student(s) as rejected';
        }
        
        if ($alreadyProcessed > 0) {
            $message .= '. ' . $alreadyProcessed . ' student(s) were already processed (shortlisted/selected/rejected)';
        }
        
        if (empty($message)) {
            $message = 'No changes were made to any applications';
        }

        return redirect()->back()->with('success', $message);
    } catch (\Exception $e) {
        Log::error('Error updating company_by: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error processing applications: ' . $e->getMessage());
    }
}
    public function shortlistedStudents()
    {
        $user = Auth::user();

        // Get company's latest active drive
        $company = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->first();

        if (!$company) {
            return redirect()->back()->with('error', 'No active drive found.');
        }

        // Get all company drives for dropdown
        $allCompanyDrives = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->get();

        // Get shortlisted students for this drive
        $shortlistedStudents = DB::table('placement_applications')
            ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->leftJoin('users_resume', function ($join) {
                $join->on('users_resume.user_id', '=', 'users.id')
                    ->where('users_resume.is_active', true)
                    ->whereRaw('users_resume.id = (
                 SELECT MAX(ur2.id) FROM users_resume ur2 
                 WHERE ur2.user_id = users.id AND ur2.is_active = true
             )');
            })
            ->leftJoin('college', 'users_profile.college', '=', 'college.id')
            ->leftJoin('courses', 'users_profile.course', '=', 'courses.id')
            ->leftJoin('departments', 'users_profile.department', '=', 'departments.id')
            ->where('placement_drive.id', $company->id)
            ->where('placement_applications.company_by', 1)
            ->whereNotNull('shortlisted_at')
            ->select(
                'placement_applications.id as application_id',
                'users.id as student_id',
                'users.name as student_name',
                'users.email as student_email',
                'users_profile.roll_number',
                'users_resume.resume_path',
                'college.name as college_name',
                'courses.name as course_name',
                'departments.name as department_name',
                'users_profile.passing_year',
                'users_profile.cgpa',
                'placement_drive.company_name',
                'placement_drive.job_title',
                'placement_drive.package_offered',
                'placement_applications.application_status',
                'placement_applications.shortlisted_at'
            )
            ->orderBy('users_profile.roll_number', 'asc')
            ->get();

        $studentIds = $shortlistedStudents->pluck('student_id')->unique()->toArray();
        $skillsData = DB::table('users_skills')
            ->whereIn('user_id', $studentIds)
            ->select('user_id', 'skill_name')
            ->get();

        $studentSkills = [];
        foreach ($skillsData as $skill) {
            $studentSkills[$skill->user_id][] = $skill->skill_name;
        }

        return view('companyDashboard.shortlisted_students', compact(
            'shortlistedStudents',
            'studentSkills',
            'company',
            'allCompanyDrives'
        ));
    }


public function rejectedStudents()
{
    $user = Auth::user();

    // Get company's latest active drive
    $company = DB::table('placement_drive')
        ->where(function ($query) use ($user) {
            $query->where('company_user_id', $user->id)
                ->orWhere('contact_email', $user->email);
        })
        ->where('is_active', 1)
        ->orderBy('drive_date', 'desc')
        ->first();

    if (!$company) {
        return redirect()->back()->with('error', 'No active drive found.');
    }

    // Get all company drives for dropdown
    $allCompanyDrives = DB::table('placement_drive')
        ->where(function ($query) use ($user) {
            $query->where('company_user_id', $user->id)
                ->orWhere('contact_email', $user->email);
        })
        ->where('is_active', 1)
        ->orderBy('drive_date', 'desc')
        ->get();

    // Get rejected students for this drive
    $rejectedStudents = DB::table('placement_applications')
        ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
        ->join('users', 'placement_applications.student_id', '=', 'users.id')
        ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
        ->leftJoin('college', 'users_profile.college', '=', 'college.id')
        ->leftJoin('courses', 'users_profile.course', '=', 'courses.id')
        ->leftJoin('departments', 'users_profile.department', '=', 'departments.id')
        ->where('placement_drive.id', $company->id)
        ->where('placement_applications.company_by', 1)
        ->where('placement_applications.application_status', 'rejected')
        ->select(
            'placement_applications.id as application_id',
            'users.id as student_id',
            'users.name as student_name',
            'users.email as student_email',
            'users_profile.roll_number',
            'college.name as college_name',
            'courses.name as course_name',
            'departments.name as department_name',
            'users_profile.passing_year',
            'users_profile.cgpa',
            'placement_drive.company_name',
            'placement_drive.job_title',
            'placement_drive.package_offered',
            'placement_applications.application_status',
           
            'placement_applications.application_responses'
        )
     
        ->get();

    // Parse application_responses for rejection reasons
    $rejectionDetails = [];
    foreach ($rejectedStudents as $student) {
        $responses = json_decode($student->application_responses, true) ?? [];
        $rejectionDetails[$student->student_id] = [
            'reason' => $responses['rejection_reason'] ?? 'No reason provided',
            'feedback' => $responses['feedback'] ?? null,
            'stage' => $responses['rejection_stage'] ?? 'Unknown',
          
        ];
    }

    $studentIds = $rejectedStudents->pluck('student_id')->unique()->toArray();
    $skillsData = DB::table('users_skills')
        ->whereIn('user_id', $studentIds)
        ->select('user_id', 'skill_name')
        ->get();

    $studentSkills = [];
    foreach ($skillsData as $skill) {
        $studentSkills[$skill->user_id][] = $skill->skill_name;
    }

    // Get counts for stats
    $stats = [
        'total_rejected' => $rejectedStudents->count(),
        'total_selected' => DB::table('placement_applications')
            ->where('placement_drive_id', $company->id)
            ->where('company_by', 1)
            ->where('application_status', 'selected')
            ->count(),
        'total_shortlisted' => DB::table('placement_applications')
            ->where('placement_drive_id', $company->id)
            ->where('company_by', 1)
            ->where('application_status', 'shortlisted')
            ->count(),
    ];

    return view('companyDashboard.rejected_students', compact(
        'rejectedStudents',
        'studentSkills',
        'rejectionDetails',
        'company',
        'allCompanyDrives',
        'stats'
    ));
}

    public function finalSelection()
    {
        $user = Auth::user();

        // Get company's latest active drive
        $company = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->first();

        if (!$company) {
            return redirect()->back()->with('error', 'No active drive found.');
        }

        // Get all company drives for dropdown
        $allCompanyDrives = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->get();

        // Get shortlisted students for final selection
        $shortlistedStudents = DB::table('placement_applications')
            ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->leftJoin('college', 'users_profile.college', '=', 'college.id')
            ->leftJoin('courses', 'users_profile.course', '=', 'courses.id')
            ->leftJoin('departments', 'users_profile.department', '=', 'departments.id')
            ->where('placement_drive.id', $company->id)
            ->where('placement_applications.company_by', 1)
            ->where('placement_applications.application_status', 'shortlisted')
            ->select(
                'placement_applications.id as application_id',
                'users.id as student_id',
                'users.name as student_name',
                'users.email as student_email',
                'users_profile.roll_number',
                'college.name as college_name',
                'courses.name as course_name',
                'departments.name as department_name',
                'users_profile.passing_year',
                'users_profile.cgpa',
                'placement_drive.company_name',
                'placement_drive.job_title',
                'placement_drive.package_offered',
                'placement_applications.application_status',
                'placement_applications.shortlisted_at'
            )
            ->orderBy('users_profile.roll_number', 'asc')
            ->get();

        $studentIds = $shortlistedStudents->pluck('student_id')->unique()->toArray();
        $skillsData = DB::table('users_skills')
            ->whereIn('user_id', $studentIds)
            ->select('user_id', 'skill_name')
            ->get();

        $studentSkills = [];
        foreach ($skillsData as $skill) {
            $studentSkills[$skill->user_id][] = $skill->skill_name;
        }

        return view('companyDashboard.final_selection', compact(
            'shortlistedStudents',
            'studentSkills',
            'company',
            'allCompanyDrives'
        ));
    }

  public function selectStudents(Request $request)
{
    $request->validate([
        'student_data' => 'nullable|string', // 改为 nullable，允许为空
        'placement_drive_id' => 'required|exists:placement_drive,id'
    ]);

    // Verify the company user owns this drive
    $drive = DB::table('placement_drive')
        ->where('id', $request->placement_drive_id)
        ->first();

    if (!$drive) {
        return redirect()->back()->with('error', 'Access denied or drive not found');
    }

    try {
        // Parse the student data (roll numbers or emails) - 允许为空
        $studentIdentifiers = [];
        if ($request->student_data) {
            $studentIdentifiers = array_map('trim', explode("\n", $request->student_data));
            $studentIdentifiers = array_filter($studentIdentifiers); // Remove empty lines
        }

        // 获取本次招聘中所有处于"已入围"状态的学生申请
        $allShortlistedApplications = DB::table('placement_applications')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->leftJoin('users_profile', 'users.id', '=', 'users_profile.user_id')
            ->where('placement_applications.placement_drive_id', $request->placement_drive_id)
            ->where('placement_applications.company_by', 1)
            ->where('placement_applications.application_status', 'shortlisted')
            ->select(
                'placement_applications.id as application_id',
                'users.id as user_id',
                'users.name as student_name',
                'users.email as student_email',
                'users_profile.roll_number',
                'placement_applications.student_id',
                'placement_applications.application_status'
            )
            ->get()
            ->keyBy('application_id'); // 按申请ID索引方便后续移除

        // 初始化计数器和消息数组
        $selectedCount = 0;
        $rejectedCount = 0;
        $notFoundStudents = [];
        $alreadySelected = [];
        $emailsSent = 0;
        
        $operationMode = 'mixed'; // 默认模式：混合（有选中，有拒绝）
        
        // 检查是否没有任何学生输入
        if (empty($studentIdentifiers)) {
            $operationMode = 'reject_all'; // 特殊模式：拒绝所有
        }

        // 1. 如果有学生输入，处理要选中的学生
        if ($operationMode === 'mixed') {
            foreach ($studentIdentifiers as $identifier) {
                if (empty($identifier)) continue;

                // 检查标识符是邮箱还是学号
                if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                    // 通过邮箱查找
                    $student = $allShortlistedApplications->first(function ($app) use ($identifier) {
                        return $app->student_email === $identifier;
                    });
                } else {
                    // 通过学号查找
                    $student = $allShortlistedApplications->first(function ($app) use ($identifier) {
                        return $app->roll_number === $identifier;
                    });
                }

                if (!$student) {
                    $notFoundStudents[] = $identifier;
                    continue;
                }

                // 检查是否已被选中
                if ($student->application_status === 'selected') {
                    $alreadySelected[] = $identifier;
                    // 从待处理列表中移除，避免被后续"拒绝"逻辑处理
                    $allShortlistedApplications->forget($student->application_id);
                    continue;
                }

                // 更新为"已选中"状态
                $updateResult = DB::table('placement_applications')
                    ->where('id', $student->application_id)
                    ->update([
                        'application_status' => 'selected',
                        'selected_at' => now(),
                        'application_responses' => DB::raw("JSON_MERGE_PATCH(
                            COALESCE(application_responses, '{}'),
                            '{
                                \"selected_at\": \"" . now()->format('Y-m-d H:i:s') . "\",
                                \"selected_message\": \"Congratulations! You have been finally selected by {$drive->company_name} for the {$drive->job_title} position.\",
                                \"final_status\": \"selected\",
                                \"package_offered\": \"{$drive->package_offered}\",
                                \"next_steps\": \"Please wait for further communication from HR regarding offer letter and joining formalities.\",
                                \"last_updated\": \"" . now()->format('Y-m-d H:i:s') . "\"
                            }'
                        )"),
                        'updated_at' => now()
                    ]);

                if ($updateResult) {
                    // 为选中的学生创建站内通知
                    DB::table('drive_notifications')->insert([
                        'drive_id' => $request->placement_drive_id,
                        'user_id' => $student->user_id,
                        'message' => "Congratulations! You have been finally selected by {$drive->company_name} for the {$drive->job_title} position.",
                        'read_at' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // 发送邮件给选中的学生
                    try {
                        Mail::to($student->student_email)->send(new CompanySelected(
                            $student->student_name,
                            $drive->company_name,
                            $drive->job_title ?? 'Job Position',
                            $drive->package_offered ?? 'To be discussed',
                            $drive->company_name  ?? 'Placement Drive',
                            $drive->contact_email ?? 'hr@company.com',
                            now()->format('F j, Y')
                        ));
                        $emailsSent++;
                    } catch (\Exception $emailException) {
                        Log::error('Error sending selection email to student ' . $student->student_email . ': ' . $emailException->getMessage());
                    }

                    $selectedCount++;
                    // 从待处理列表中移除，避免被后续"拒绝"逻辑处理
                    $allShortlistedApplications->forget($student->application_id);
                }
            }
        }

        // 2. 自动拒绝所有剩余的学生
        // 设置固定的拒绝原因
        $rejectionReason = "The selection process for this drive has been completed. We appreciate your participation.";

        foreach ($allShortlistedApplications as $application) {
            // 更新为"已拒绝"状态
            $updateResult = DB::table('placement_applications')
                ->where('id', $application->application_id)
                ->update([
                    'application_status' => 'rejected',
                 
                    'application_responses' => DB::raw("JSON_MERGE_PATCH(
                        COALESCE(application_responses, '{}'),
                        '{
                            \"rejected_at\": \"" . now()->format('Y-m-d H:i:s') . "\",
                            \"rejection_reason\": \"{$rejectionReason}\",
                            \"company_action\": \"rejected_after_final_selection\",
                            \"status_update\": \"not_selected\",
                            \"last_updated\": \"" . now()->format('Y-m-d H:i:s') . "\"
                        }'
                    )"),
                    'updated_at' => now()
                ]);

            if ($updateResult) {
                // 为被拒绝的学生创建站内通知（不发邮件）
                $rejectionMessage = "Update regarding your application for {$drive->job_title} at {$drive->company_name}. "
                    . "The selection process is now complete. We regret to inform you that you have not been selected. "
                    . "We sincerely thank you for your time and interest.";

                DB::table('drive_notifications')->insert([
                    'drive_id' => $request->placement_drive_id,
                    'user_id' => $application->user_id,
                    'message' => $rejectionMessage,
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $rejectedCount++;
            }
        }

        // 3. 更新本次招聘的状态为"已完成"
        DB::table('placement_drive')
            ->where('id', $request->placement_drive_id)
            ->update([
                'updated_at' => now(),
                'status' => 'completed'
            ]);

        // 准备最终的成功消息
        if ($operationMode === 'reject_all') {
            $message = "No students selected. All {$rejectedCount} shortlisted students have been rejected.";
        } else {
            $message = "Final selection completed successfully. ";
            $message .= "Selected: {$selectedCount} student(s). ";
            $message .= "Rejected: {$rejectedCount} student(s).";

            if ($emailsSent > 0) {
                $message .= " Confirmation emails sent to {$emailsSent} student(s).";
            }

            // 添加额外的信息（如果有问题）
            $additionalMessages = [];
            if (!empty($notFoundStudents)) {
                $additionalMessages[] = "Not found or not shortlisted: " . implode(', ', array_slice($notFoundStudents, 0, 5)) . (count($notFoundStudents) > 5 ? '...' : '');
            }
            if (!empty($alreadySelected)) {
                $additionalMessages[] = "Already selected: " . implode(', ', array_slice($alreadySelected, 0, 5)) . (count($alreadySelected) > 5 ? '...' : '');
            }

            if (!empty($additionalMessages)) {
                $message .= " Note: " . implode('; ', $additionalMessages);
            }
        }

        return redirect()->back()->with('success', $message);
    } catch (\Exception $e) {
        Log::error('Error in final selection process: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error during final selection: ' . $e->getMessage());
    }
}

    public function selectedStudents()
    {
        $user = Auth::user();

        // Get company's latest active drive
        $company = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->first();

        if (!$company) {
            return redirect()->back()->with('error', 'No active drive found.');
        }

        // Get all company drives for dropdown
        $allCompanyDrives = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->get();

        $studentIds = DB::table('placement_applications')
            ->where('placement_drive_id', $company->id)
            ->where('application_status', 'selected')
            ->pluck('student_id')
            ->unique()
            ->toArray();

        $selectedStudents = DB::table('users')
            ->join('users_profile', 'users.id', '=', 'users_profile.user_id')
            ->leftJoin('college', 'users_profile.college', '=', 'college.id')
            ->leftJoin('courses', 'users_profile.course', '=', 'courses.id')
            ->leftJoin('departments', 'users_profile.department', '=', 'departments.id')
            ->whereIn('users.id', $studentIds)
            ->select(
                'users.id as student_id',
                'users.name as student_name',
                'users.email as student_email',
                'users_profile.roll_number',
                'college.name as college_name',
                'courses.name as course_name',
                'departments.name as department_name',
                'users_profile.passing_year',
                'users_profile.cgpa'
            )
            ->orderBy('users_profile.roll_number')
            ->get();

        $resumePaths = DB::table('users_resume')
            ->whereIn('user_id', $studentIds)
            ->where('is_active', true)
            ->select('user_id', 'resume_path')
            ->get()
            ->keyBy('user_id');

        $studentSkills = [];
        if (!empty($studentIds)) {
            $skillsData = DB::table('users_skills')
                ->whereIn('user_id', $studentIds)
                ->select('user_id', 'skill_name')
                ->get();

            foreach ($skillsData as $skill) {
                $studentSkills[$skill->user_id][] = $skill->skill_name;
            }
        }

        foreach ($selectedStudents as $student) {
            $student->resume_path = $resumePaths[$student->student_id]->resume_path ?? null;
            $student->selected_at = DB::table('placement_applications')
                ->where('placement_drive_id', $company->id)
                ->where('student_id', $student->student_id)
                ->where('application_status', 'selected')
                ->value('selected_at');
        }

        return view('companyDashboard.selected_students', compact(
            'selectedStudents',
            'studentSkills',
            'company',
            'allCompanyDrives'
        ));
    }


    public function viewPlacementDriveCompany()
    {
        $user = Auth::user();

        // Get company's latest active drive
        $driveDetails = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->first();

        if (!$driveDetails) {
            return redirect()->back()->with('error', 'No active drive found.');
        }

        // Get all company drives for dropdown
        $allCompanyDrives = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->where('company_user_id', $user->id)
                    ->orWhere('contact_email', $user->email);
            })
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->get();

        if ($driveDetails) {
            if ($driveDetails->course_id) {
                $courseIds = json_decode($driveDetails->course_id);
                $courseNames = DB::table('courses')
                    ->whereIn('id', $courseIds)
                    ->pluck('name', 'id')
                    ->toArray();
                $driveDetails->course_names = $courseNames;
            }

            if ($driveDetails->department_id) {
                $departmentIds = json_decode($driveDetails->department_id);
                $departmentNames = DB::table('departments')
                    ->whereIn('id', $departmentIds)
                    ->pluck('name', 'id')
                    ->toArray();
                $driveDetails->department_names = $departmentNames;
            }

            if ($driveDetails->created_by) {
                $creator = DB::table('users')
                    ->where('id', $driveDetails->created_by)
                    ->first();
                $driveDetails->creator_name = $creator ? $creator->name : 'Unknown';
            }
        }

        return view('companyDashboard.drivedetail', compact('driveDetails', 'allCompanyDrives'));
    }

    public function updateViewPlacementDriveCompany(Request $request, $id)
    {
        $request->validate([
            'drive_date' => 'required|date',
            'application_deadline' => 'nullable|date',
        ]);

        $driveDate = $request->drive_date;
        $deadline = $request->application_deadline;

        // ✅ Check: deadline should not be after drive date
        if ($deadline && $deadline > $driveDate) {
            return redirect()->back()
                ->with('error', 'Application deadline cannot be later than the drive date.')
                ->withInput();
        }

        try {
            DB::table('placement_drive')
                ->where('id', $id)
                ->update([
                    'drive_date' => $driveDate,
                    'application_deadline' => $deadline,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Placement drive updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update placement drive.');
        }
    }


    public function companyUserProfile($userId)
    {
        // Use the same logic as user profile but for any user
        $skills = DB::table('users_skills')
            ->where('user_id', $userId)
            ->get();

        $user = DB::table('users')->where('id', $userId)->first();

        $academicHistory = DB::table('academic_history')
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        $resumePath = DB::table('users_resume')
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->exists();

        $projects = DB::table('user_projects')
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        $experiences = DB::table('user_experience')
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        $userProfile = DB::table('users_profile')
            ->where('user_id', $userId)
            ->first();

        // Simple completion calculation
        $completionPercent = $this->calculateCompletionForUser($userId);

        return view('companyDashboard.user_profile', compact('user', 'skills', 'academicHistory', 'resumePath', 'projects', 'experiences', 'userProfile', 'completionPercent'));
    }

    private function calculateCompletionForUser($userId)
    {
        // Simple completion calculation
        $totalFields = 0;
        $completedFields = 0;

        // Check user basic info
        $user = DB::table('users')->where('id', $userId)->first();
        if ($user) {
            $fields = ['name', 'email', 'phone', 'city', 'state', 'country', 'profile_picture'];
            foreach ($fields as $field) {
                $totalFields++;
                if (!empty($user->$field)) $completedFields++;
            }
        }

        // Check if sections have data
        $sections = ['users_skills', 'academic_history', 'user_experience', 'user_projects', 'users_resume', 'users_profile'];
        foreach ($sections as $table) {
            $totalFields++;
            $exists = DB::table($table)->where('user_id', $userId)->exists();
            if ($exists) $completedFields++;
        }

        return $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
    }


// Method to reject students after shortlisting// Method to reject students after shortlisting
public function rejectShortlistedStudents(Request $request)
{
   

}
}

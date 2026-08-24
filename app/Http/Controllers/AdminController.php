<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\welcomeEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\PlacementDriveNotification;
use Faker\Provider\ar_EG\Company;
use App\Mail\CompanyEmail;
use App\Mail\CompanyDriveNotification;
use App\Mail\PlacementOfficerCreatedMail;
use Illuminate\Support\Facades\File;


class AdminController extends Controller
{

 public function testimonials_index()
    {
        $testimonials = DB::table('testimonials')
            ->orderBy('order', 'asc')
            ->get();
        
        return view('adminDashboard.site_configuration.testimonials', compact('testimonials'));
    }
    
    // 2. STORE - Add new testimonial
    public function testimonials_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        try {
            $filePath = null;
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/testimonials');
                
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $fileName);
                $filePath = 'uploads/testimonials/' . $fileName;
            }
            
            DB::table('testimonials')->insert([
                'name' => $request->name,
                'designation' => $request->designation,
                'company' => $request->company,
                'quote' => $request->quote,
                'image_path' => $filePath,
                'order' => DB::table('testimonials')->max('order') + 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return redirect()->route('testimonials.index')
                ->with('success', 'Testimonial added successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add testimonial: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    // 3. DESTROY - Delete testimonial
    public function testimonials_destroy($id)
    {
        $testimonial = DB::table('testimonials')->where('id', $id)->first();
        
        if (!$testimonial) {
            return redirect()->back()->with('error', 'Testimonial not found');
        }
        
        try {
            // Delete image if exists
            if ($testimonial->image_path && file_exists(public_path($testimonial->image_path))) {
                File::delete(public_path($testimonial->image_path));
            }
            
            DB::table('testimonials')->where('id', $id)->delete();
            
            return redirect()->route('testimonials.index')
                ->with('success', 'Testimonial deleted successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete testimonial');
        }
    }

    public function placement_update_index()
    {
        $placementUpdates = DB::table('placement_updates')
            ->orderBy('order', 'asc')
            ->get();

        return view('adminDashboard.site_configuration.placement_update', compact('placementUpdates'));
    }

    // Store placement update
    public function placement_update_store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'package' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'order' => 'nullable|integer',
        ]);

        $filePath = null;

        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                $destinationPath = public_path('uploads/placement-updates');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $fileName);
                $filePath = 'uploads/placement-updates/' . $fileName;
            }

            DB::table('placement_updates')->insert([
                'title' => $request->title,
                'company_name' => $request->company_name,
                'package' => $request->package,
                'description' => $request->description,
                'image_path' => $filePath,
                'order' => $request->order ?? DB::table('placement_updates')->max('order') + 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('placement-updates.index')
                ->with('success', 'Placement update added successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add placement update: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Delete placement update
    public function placement_update_destroy($id)
    {
        $placementUpdate = DB::table('placement_updates')->where('id', $id)->first();

        if (!$placementUpdate) {
            return redirect()->back()->with('error', 'Placement update not found');
        }

        try {
            // Delete image if exists
            if ($placementUpdate->image_path && file_exists(public_path($placementUpdate->image_path))) {
                File::delete(public_path($placementUpdate->image_path));
            }

            DB::table('placement_updates')->where('id', $id)->delete();

            // Reorder remaining items
            $remaining = DB::table('placement_updates')->orderBy('order')->get();
            foreach ($remaining as $index => $item) {
                DB::table('placement_updates')->where('id', $item->id)->update(['order' => $index]);
            }

            return redirect()->route('placement-updates.index')
                ->with('success', 'Placement update deleted successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete placement update');
        }
    }

    // Update order (for drag & drop sorting)
    public function placement_update_updateOrder(Request $request)
    {
        $orders = $request->orders;
        
        try {
            foreach ($orders as $index => $id) {
                DB::table('placement_updates')->where('id', $id)->update([
                    'order' => $index,
                    'updated_at' => now()
                ]);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // Toggle status (active/inactive)
    public function placement_update_toggleStatus($id)
    {
        $placementUpdate = DB::table('placement_updates')->where('id', $id)->first();
        
        if (!$placementUpdate) {
            return redirect()->back()->with('error', 'Placement update not found');
        }
        
        DB::table('placement_updates')->where('id', $id)->update([
            'is_active' => !$placementUpdate->is_active,
            'updated_at' => now()
        ]);
        
        $status = !$placementUpdate->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Placement update {$status} successfully");
    }




public function deleteSlider($id)
{
    $slider = DB::table('sliders')->where('id', $id)->first();

    if (!$slider) {
        return redirect()->back()->with('error', 'Slider not found');
    }

    try {
        // Delete image if exists
        if ($slider->image_path && file_exists(public_path($slider->image_path))) {
            File::delete(public_path($slider->image_path));
        }

        DB::table('sliders')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Slider deleted successfully');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to delete slider');
    }
}

public function storeSlider(Request $request)
{
    $request->validate([
        'title' => 'nullable|string|max:255',
        'subheading' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'order' => 'nullable|integer',
        'is_active' => 'nullable|boolean',
    ]);

    $filePath = null;
    $tempFilePath = null;

    try {
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Step 1: Temp directory
            $tempDir = 'uploads/temp';
            if (!File::exists(public_path($tempDir))) {
                File::makeDirectory(public_path($tempDir), 0755, true);
            }

            // Save to temp
            $file->move(public_path($tempDir), $fileName);
            $tempFilePath = $tempDir . '/' . $fileName;

            // Step 2: Final directory
            $finalDir = 'uploads/sliders';
            if (!File::exists(public_path($finalDir))) {
                File::makeDirectory(public_path($finalDir), 0755, true);
            }

            // Step 3: Copy temp → final
            $finalPath = $finalDir . '/' . $fileName;

            if (copy(public_path($tempFilePath), public_path($finalPath))) {

                $filePath = $finalPath;

                // Step 4: Delete temp file
                if (file_exists(public_path($tempFilePath))) {
                    unlink(public_path($tempFilePath));
                }

            } else {
                // Cleanup temp if failed
                if (file_exists(public_path($tempFilePath))) {
                    unlink(public_path($tempFilePath));
                }

                return redirect()->back()
                    ->with('error', 'Failed to save slider image. Please try again.')
                    ->withInput();
            }
        }

        // Insert into DB
        DB::table('sliders')->insert([
            'title' => $request->title,
            'subheading' => $request->subheading,
            'description' => $request->description,
            'image_path' => $filePath,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Slider added successfully');

    } catch (\Exception $e) {

        // Cleanup temp file if exists
        if ($tempFilePath && file_exists(public_path($tempFilePath))) {
            unlink(public_path($tempFilePath));
        }

        return redirect()->back()
            ->with('error', 'Something went wrong. Please try again.')
            ->withInput();
    }
}

public function getSliders()
{
    $sliders = DB::table('sliders')
        ->orderBy('order', 'asc')
        ->get();

    return view('adminDashboard.site_configuration.sliders', compact('sliders'));
}

 public function siteConfig(){   return view('adminDashboard.site_configuration.index'); }

    public function showAlluser()
    {
        $users = DB::table('users')

            ->leftJoin('users_profile', 'users.id', '=', 'users_profile.user_id')

            ->leftJoin('college', 'users_profile.college', '=', 'college.id')

            ->leftJoin('courses', 'users_profile.course', '=', 'courses.id')

            ->leftJoin('departments', 'users_profile.department', '=', 'departments.id')

            ->select(
                'users.*',

                'users_profile.gender',
                'users_profile.roll_number',
                'users_profile.passing_year',

                'college.name as college_name',
                'courses.name as course_name',
                'departments.name as department_name'
            )->where('role', 'user') // Fixed: Changed user to 'user' (string)
            ->get();

        $colleges = DB::table('college')->get();
        $courses = DB::table('courses')->get();
        $departments = DB::table('departments')->get();
        $years = DB::table('users_profile')
            ->whereNotNull('passing_year')
            ->distinct()
            ->orderBy('passing_year', 'desc')
            ->pluck('passing_year');

        $departmentsCount = DB::table('departments')->count();
        $coursesCount = DB::table('courses')->count();


        return view('adminDashboard.allUser', compact('users', 'colleges', 'courses', 'departments', 'years', 'departmentsCount', 'coursesCount'));
    }

    public function campusGet()
    {
        $campusData = DB::table('campus')
            ->leftJoin('college', 'campus.college_id', '=', 'college.id')
            ->select('campus.*', 'college.name as college_name')
            ->orderBy('college.name')
            ->orderBy('campus.name')
            ->get();

        // Also get colleges for the dropdown
        $colleges = DB::table('college')->orderBy('name')->get();

        return view('adminDashboard.campus', compact('campusData', 'colleges'));
    }

    public function addCampus(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:campus,name',
            'college_id' => 'required|exists:college,id',
        ]);

        try {
            DB::table('campus')->insert([
                'name' => $request->input('name'),
                'college_id' => $request->input('college_id'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Campus added successfully.');
        } catch (\Exception $e) {
            Log::error('Error adding campus: ' . $e->getMessage());

            return back()->withErrors(['name' => 'Error adding campus: ' . $e->getMessage()]);
        }
    }

    public function updateCampus(Request $request, $campus_id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:campus,name,' . $campus_id,
            'college_id' => 'required|exists:college,id',
        ]);

        try {
            DB::table('campus')->where('id', $campus_id)->update([
                'name' => $request->input('name'),
                'college_id' => $request->input('college_id'),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Campus updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating campus: ' . $e->getMessage());

            return back()->withErrors(['name' => 'Error updating campus: ' . $e->getMessage()]);
        }
    }

    public function deleteCampus($id)
    {
        try {
            DB::table('campus')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Campus deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting campus: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Error deleting campus: ' . $e->getMessage()]);
        }
    }




    public function index()
    {
        $collegecount = DB::table('college')->count();
        $drivecount = DB::table('placement_drive')->count();
        $collegecount = DB::table('college')->count();



        return view('adminDashboard.index', compact('collegecount', 'drivecount'));
    }

    public function collegeGet()
    {
        $collegeData = DB::table('college')->get();

        $courseData = DB::table('courses')->join('college', 'courses.college_id', '=', 'college.id')
            ->select('courses.*', 'college.name as college_name')
            ->get();


        $departmentData = DB::table('departments')->get();

        return view('adminDashboard.college', compact('collegeData', 'courseData', 'departmentData'));
    }


    public function importStudents(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $csvFile = $request->file('csv_file');
            $handle = fopen($csvFile->getPathname(), 'r');

            fgetcsv($handle); // Skip header row

            $users = [];
            $emails = [];

            while (($data = fgetcsv($handle)) !== FALSE) {
                if (empty($data[0]) || empty($data[1]) || empty($data[4])) {
                    continue;
                }

                $email = $data[1];
                $emails[] = $email;

                $users[] = [
                    'name' => $data[0],
                    'email' => $email,
                    'phone' => $data[2],
                    'role' => 'user',
                    'password' => Hash::make($data[4]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            fclose($handle);

            // Get existing emails from database
            $existingEmails = DB::table('users')
                ->whereIn('email', $emails)
                ->pluck('email')
                ->toArray();

            // Filter out users with existing emails
            $usersToInsert = [];
            foreach ($users as $user) {
                if (!in_array($user['email'], $existingEmails)) {
                    $usersToInsert[] = $user;
                }
            }

            // Insert only new users
            if (!empty($usersToInsert)) {
                DB::table('users')->insert($usersToInsert);
            }

            $total = count($users);
            $imported = count($usersToInsert);
            $skipped = $total - $imported;

            return redirect()->back()->with('success', "Imported: $imported, Skipped: $skipped");
        } catch (\Exception $e) {
            return back()->withErrors(['csv_file' => 'Error: ' . $e->getMessage()]);
        }
    }



    public function storeCollegeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:college,name',
            'location' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        $createdBy = Auth::id(); // Get the ID of the currently authenticated user

        try {
            DB::table('college')->insert([
                'created_by' => $createdBy,
                'name' => $request->input('name'),
                'location' => $request->input('location'),
                'website' => $request->input('website'),
                'contact_email' => $request->input('contact_email'),
                'contact_phone' => $request->input('contact_phone'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'College added successfully.');
        } catch (\Exception $e) {
            Log::error('Error adding college: ' . $e->getMessage());

            return back()->withErrors(['name' => 'Error adding college: ' . $e->getMessage()]);
        }
    }

    public function updateCollegeAdmin(Request $request, $college_id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:college,name,' . $college_id,
            'location' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        try {
            DB::table('college')->where('id', $college_id)->update([
                'name' => $request->input('name'),
                'location' => $request->input('location'),
                'website' => $request->input('website'),
                'contact_email' => $request->input('contact_email'),
                'contact_phone' => $request->input('contact_phone'),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'College updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating college: ' . $e->getMessage());

            return back()->withErrors(['name' => 'Error updating college: ' . $e->getMessage()]);
        }
    }

    public function deleteCollegeAdmin($id)
    {
        try {
            DB::table('college')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'College deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting college: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Error deleting college: ' . $e->getMessage()]);
        }
    }

    public function getCollegeCourses($college_id)
    {
        $courseData = DB::table('courses')
            ->join('college', 'courses.college_id', '=', 'college.id')
            ->select('courses.*', 'college.name as college_name')
            ->where('courses.college_id', $college_id)
            ->get();

        $course_id =  DB::table('courses')
            ->where('courses.college_id', $college_id)
            ->first();

        // $course_id = $course ? $course->id : null; // Safe check

        $selectedCollege = DB::table('college')->where('id', $college_id)->first();

        $collegeData = DB::table('college')->where('id', $college_id)->get();

        $departmentData = DB::table('departments')
            ->join('courses', 'departments.courses_id', '=', 'courses.id')
            // ->where('departments.courses_id', $course_id)
            ->get();



        return view('adminDashboard.course', compact('courseData', 'collegeData', 'selectedCollege', 'departmentData'));
    }

    public function storeCourse(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'college_id' => 'required|exists:college,id',
            'description' => 'nullable|string',
        ]);




        try {
            DB::table('courses')->insert([
                'name' => $request->input('name'),
                'college_id' => $request->input('college_id'),
                'description' => $request->input('description'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Course added successfully.');
        } catch (\Exception $e) {
            Log::error('Error adding course: ' . $e->getMessage());

            return back()->withErrors(['name' => 'Error adding course: ' . $e->getMessage()]);
        }
    }

    public function updateCourse(Request $request, $course_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'college_id' => 'required|exists:college,id',
            'description' => 'nullable|string',
        ]);

        try {
            DB::table('courses')->where('id', $course_id)->update([
                'name' => $request->input('name'),
                'college_id' => $request->input('college_id'),
                'description' => $request->input('description'),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Course updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating course: ' . $e->getMessage());

            return back()->withErrors(['name' => 'Error updating course: ' . $e->getMessage()]);
        }
    }

    public function deleteCourse($id)
    {
        try {
            DB::table('courses')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Course deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting course: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Error deleting course: ' . $e->getMessage()]);
        }
    }

    public function getCourseDepartments($course_id)
    {
        $courseData = DB::table('courses')
            ->where('id', $course_id)
            ->get();
  $selectedCourse = DB::table('courses')
            ->where('id', $course_id)
            ->first();

        $departmentData = DB::table('departments')
            ->join('courses', 'departments.courses_id', '=', 'courses.id')
            ->join('college', 'courses.college_id', '=', 'college.id')
            ->where('courses.id', $course_id)
            ->select('departments.*', 'courses.name as course_name', 'college.name as college_name')
            ->get();

        $departmentIds = $departmentData->pluck('id');



        return view('adminDashboard.department', compact('departmentData', 'courseData', 'departmentIds', 'selectedCourse'));
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'courses_id' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            DB::table('departments')->insert([
                'courses_id' => $request->input('courses_id'),
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Department added successfully.');
        } catch (\Exception $e) {
            Log::error('Error adding department: ' . $e->getMessage());

            return back()->withErrors(['name' => 'Error adding department: ' . $e->getMessage()]);
        }
    }

    public function updateDepartment(Request $request, $department_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            DB::table('departments')->where('id', $department_id)->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success', 'Department updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating department: ' . $e->getMessage());

            return back()->withErrors(['name' => 'Error updating department: ' . $e->getMessage()]);
        }
    }

    public function deleteDepartment($id)
    {
        try {
            DB::table('departments')->where('id', $id)->delete();

            return redirect()->back()->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting department: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Error deleting department: ' . $e->getMessage()]);
        }
    }



    public function placementDrives()
    {
        $placementDrives = DB::table('placement_drive')->get();

        return view('adminDashboard.placement_drive', compact('placementDrives'));
    }

    public function placementDrivesCreate()
    {
        $colleges = DB::table('college')->get();
        $courses = DB::table('courses')->get();
        $departments = DB::table('departments')->get();

        $campuses = DB::table('campus')
            ->leftJoin('college', 'campus.college_id', '=', 'college.id')
            ->select('campus.*', 'college.name as college_name')
            ->orderBy('college.name')
            ->orderBy('campus.name')
            ->get();

        return view('adminDashboard.placement_drive_create', compact('colleges', 'courses', 'departments', 'campuses'));
    }

    // ✅ Fetch Courses by College
    // ✅ Fetch Courses by College
    public function fetchCourses(Request $request)
    {
        try {
            // Get the first college ID (simplified for now)
            $collegeId = $request->input('college_id');

            // If no college_id, try college_ids
            if (!$collegeId) {
                $collegeIds = $request->input('college_ids');
                if (is_array($collegeIds)) {
                    $collegeId = $collegeIds[0] ?? null;
                } elseif (is_string($collegeIds)) {
                    $ids = explode(',', $collegeIds);
                    $collegeId = $ids[0] ?? null;
                }
            }

            if (!$collegeId) {
                return response()->json(['error' => 'No college ID'], 400);
            }

            // Get courses for this college
            $courses = DB::table('courses')
                ->where('college_id', $collegeId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fetchDepartments(Request $request)
    {
        try {
            $courseIds = $request->input('course_ids');

            // If string, make it array
            if (is_string($courseIds)) {
                $courseIds = explode(',', $courseIds);
            }

            // If still not array, make it array
            if (!is_array($courseIds)) {
                $courseIds = [$courseIds];
            }

            if (empty($courseIds)) {
                return response()->json(['error' => 'No course IDs'], 400);
            }

            // Get departments for these courses
            $departments = DB::table('departments')
                ->whereIn('courses_id', $courseIds)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json($departments);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function storePlacementDrive(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'drive_date' => 'required|date',
            'job_title' => 'required|string|max:255',
            'required_skills' => 'nullable',
            'application_deadline' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'job_role' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'drive_type' => 'required|in:on_campus,off_campus,virtual',
            'package_offered' => 'nullable|numeric|min:0',
            'eligibility_cgpa' => 'nullable|string|max:5',
            'eligibility_passing_year' => 'required|string',
            'vacancies' => 'nullable|integer|min:0',
            'drive_coordinator' => 'nullable|string|max:255',
            'company_website' => 'nullable|url|max:255',
            // CHANGED: From single college_id to multiple college_ids
            'college_ids' => 'required|string',
            // You can keep campus_ids if still needed, or remove it
            'course_ids' => 'required|string',
            'department_ids' => 'required|string',
            'is_reappear' => 'boolean',
            'tenth_percentage' => 'nullable|string|max:5',
            'twelfth_percentage' => 'nullable|string|max:5',
            'graduation_percentage' => 'nullable|string|max:5'
        ]);

        try {
            $courseIdsArr = explode(',', $request->course_ids);
            $departmentIdsArr = explode(',', $request->department_ids);

            // Process multiple college IDs
            $collegeIds = [];
            if ($request->has('college_ids') && !empty($request->college_ids)) {
                $collegeIds = array_map('trim', explode(',', $request->college_ids));
                $collegeIds = array_filter($collegeIds); // Remove empty values
            }



            $requiredSkills = json_decode($request->required_skills, true) ?? [];

            // Process eligibility_passing_year
            $eligibilityPassingYear = null;
            if ($request->has('eligibility_passing_year') && !empty($request->eligibility_passing_year)) {
                $yearsArray = array_map('trim', explode(',', $request->eligibility_passing_year));
                $eligibilityPassingYear = json_encode($yearsArray);
            }

            $companyUserId = 0;
            $contactEmail = $request->contact_email ?? null;

            if ($contactEmail) {
                $existingCompanyUser = DB::table('users')->where('email', $contactEmail)->first();
            }

            $companyUserId = $existingCompanyUser ? $existingCompanyUser->id : 0;

            $driveId = DB::table('placement_drive')->insertGetId([
                'company_name' => $request->company_name,
                'drive_date' => $request->drive_date,
                'job_title' => $request->job_title,
                'required_skills' => !empty($requiredSkills) ? json_encode($requiredSkills) : null,
                'application_deadline' => $request->application_deadline,
                'location' => $request->location,
                'job_role' => $request->job_role,
                // CHANGED: Store multiple college IDs as JSON
                'college_id' => !empty($collegeIds) ? json_encode($collegeIds) : null,
                'course_id' => json_encode($courseIdsArr),
                'department_id' => json_encode($departmentIdsArr),
                'description' => $request->description,
                'contact_person' => $request->contact_person,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'status' => $request->status,
                'drive_type' => $request->drive_type,
                'package_offered' => $request->package_offered,
                'eligibility_cgpa' => $request->eligibility_cgpa,
                'eligibility_passing_year' => $eligibilityPassingYear,
                'vacancies' => $request->vacancies,
                'drive_coordinator' => $request->drive_coordinator,
                'company_website' => $request->company_website,
                'is_active' =>  1,
                'created_by' => Auth::id(),
                'company_user_id' => $companyUserId,
                'is_reappear' => $request->is_reappear ?? false,
                'tenth_percentage' => $request->tenth_percentage ?? null,
                'twelfth_percentage' => $request->twelfth_percentage ?? null,
                'graduation_percentage' => $request->graduation_percentage ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Email logic remains the same...

            if ($contactEmail) {
                if (!$existingCompanyUser) {
                    $companyPass = 'company' . rand(1001, 9999);

                    $userIdforcom = DB::table('users')->insertGetId([
                        'name' => $request->contact_person ?? $request->company_name,
                        'email' => $contactEmail,
                        'password' => Hash::make($companyPass),
                        'role' => 'company',
                        'is_active' => true,
                        'company_id' => $driveId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $updateDrive = DB::table('placement_drive')->where('id', $driveId)->update([
                        'company_user_id' => $userIdforcom,
                    ]);

                    if ($userIdforcom) {
                        try {
                            Mail::to($contactEmail)->send(new CompanyEmail(
                                $request->contact_person ?? $request->company_name,
                                $contactEmail,
                                $companyPass
                            ));
                        } catch (\Exception $e) {
                            Log::error("Welcome email failed to {$contactEmail}: " . $e->getMessage());
                        }
                    }
                } else {
                    try {
                        Mail::to($contactEmail)->send(new CompanyDriveNotification(
                            $request->contact_person ?? $request->company_name,
                            $contactEmail,
                            $request->company_name,
                            $request->drive_date,
                            $request->job_title
                        ));
                    } catch (\Exception $e) {
                        Log::error("Notification email failed to {$contactEmail}: " . $e->getMessage());
                    }
                }
            }

            // Get eligible students - filter by multiple colleges
            $eligibleStudentsQuery = DB::table('users')
                ->where('role', 'user')
                ->where('is_active', 1);



            $eligibleStudents = $eligibleStudentsQuery->pluck('id');

            // Send web notification for each eligible user
            foreach ($eligibleStudents as $userId) {
                DB::table('drive_notifications')->insert([
                    'drive_id' => $driveId,
                    'user_id' => $userId,
                    'message' => "New Drive for You {$request->company_name}. Please check placement drive details.",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            return redirect()->back()
                ->with('success', "Placement drive created for multiple colleges.");
        } catch (\Exception $e) {
            Log::error("Drive creation failed: " . $e->getMessage());
            return redirect()->back()
                ->with('error', "Failed to create drive: " . $e->getMessage())
                ->withInput();
        }
    }







public function indexdb()
    {
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];
        
        foreach ($tables as $table) {
            $tableName = $table->{'Tables_in_' . config('database.connections.mysql.database')};
            $tableNames[] = $tableName;
        }
        
        return view('adminDashboard.indexdb', ['tables' => $tableNames]);
    }

    /**
     * Show table data
     */
    public function showTabledb($table)
    {
        // Get all data from table
        $data = DB::table($table)->paginate(2000);
        
        // Get column names
        $columns = [];
        if (count($data) > 0) {
            $firstRow = (array) $data[0];
            $columns = array_keys($firstRow);
        }
        
        return view('adminDashboard.tabledb', [
            'table' => $table,
            'data' => $data,
            'columns' => $columns
        ]);
    }

    /**
     * Show edit form
     */
    public function editdb($table, $id)
    {
        $record = DB::table($table)->where('id', $id)->first();
        
        if (!$record) {
            return back()->with('error', 'Record not found');
        }
        
        $columns = array_keys((array) $record);
        
        return view('adminDashboard.editdb', [
            'table' => $table,
            'record' => $record,
            'columns' => $columns,
            'id' => $id
        ]);
    }

    /**
     * Update record
     */
    public function updatedb(Request $request, $table, $id)
    {
        $data = $request->except(['_token', '_method']);
        
        // Add updated_at if column exists
        if (DB::getSchemaBuilder()->hasColumn($table, 'updated_at')) {
            $data['updated_at'] = now();
        }
        
        DB::table($table)->where('id', $id)->update($data);
        
        return redirect()->route('tabledb', $table)
            ->with('success', 'Record updated successfully');
    }

    /**
     * Delete record
     */
    public function destroydb($table, $id)
    {
        DB::table($table)->where('id', $id)->delete();
        
        return back()->with('success', 'Record deleted successfully');
    }

    /**
     * Add new record
     */
    public function createdb($table)
    {
        // Get one record to know columns
        $sample = DB::table($table)->first();
        $columns = $sample ? array_keys((array) $sample) : [];
        
        return view('adminDashboard.createdb', [
            'table' => $table,
            'columns' => $columns
        ]);
    }

    /**
     * Store new record
     */
    public function storedb(Request $request, $table)
    {
        $data = $request->except('_token');
        
        // Add timestamps if columns exist
        if (DB::getSchemaBuilder()->hasColumn($table, 'created_at')) {
            $data['created_at'] = now();
        }
        if (DB::getSchemaBuilder()->hasColumn($table, 'updated_at')) {
            $data['updated_at'] = now();
        }
        
        DB::table($table)->insert($data);
        
        return redirect()->route('tabledb', $table)
            ->with('success', 'Record added successfully');
    }












    public function viewPlacementDrive($drive_id)
    {
        $driveDetails = DB::table('placement_drive')->where('id', $drive_id)->first();

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

        return view('adminDashboard.placement_drive_view', compact('driveDetails'));
    }

    public function updateViewPlacementDrive(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'company_name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'drive_date' => 'required|date',
            'application_deadline' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'drive_type' => 'required|in:on_campus,off_campus,virtual',
            'package_offered' => 'nullable|numeric',
            'vacancies' => 'nullable|integer',
        ]);

        try {
            // Update the placement drive
            DB::table('placement_drive')
                ->where('id', $id)
                ->update([
                    'company_name' => $request->company_name,
                    'job_title' => $request->job_title,
                    'drive_date' => $request->drive_date,
                    'application_deadline' => $request->application_deadline,
                    'location' => $request->location,
                    'status' => $request->status,
                    'drive_type' => $request->drive_type,
                    'package_offered' => $request->package_offered,
                    'vacancies' => $request->vacancies,
                    'is_active' => $request->is_active ?? 1,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Placement drive updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update placement drive: ' . $e->getMessage());
        }
    }



    public function getCompanies()
    {
        $companies = DB::table('placement_drive')
            ->select(
                'id',
                'company_name',
                'job_role',
                'drive_date',
                'application_deadline',
                'location',
                'status',
                'drive_type',
                'package_offered',
                'vacancies',
                'created_at'
            )
            ->where('is_active', true)
            ->orderBy('drive_date', 'desc')
            ->get();

        return view('adminDashboard.companies', compact('companies'));
    }


    public function driveApwplqqication()
    {
        $applicationData = DB::table('placement_applications')
            ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->leftJoin('users_resume', function ($join) {
                $join->on('users_resume.user_id', '=', 'users.id')
                    ->where('users_resume.is_active', true);
            })
            ->select(
                'placement_applications.id as application_id',
                'users.id as student_id',
                'users.name as student_name',
                'users.email as student_email',
                'users.phone',
                'users_profile.roll_number',
                'users_profile.college',
                'users_profile.course',
                'users_profile.department',
                'users_profile.gender',
                'users_profile.date_of_birth',
                'users_profile.passing_year',
                'users_profile.cgpa',
                'placement_drive.company_name',
                'placement_drive.job_role',
                'placement_drive.drive_date',
                'placement_drive.package_offered',
                'placement_drive.eligibility_cgpa',
                'placement_drive.eligibility_passing_year',
                'placement_applications.application_status',
                'placement_applications.applied_at',
                'placement_applications.shortlisted_at',
                'placement_applications.selected_at',
                'placement_applications.remarks',
                'users_resume.resume_path',
                'users_resume.resume_title'
            )
            ->get();

        // Get skills for each student
        $studentSkills = [];
        foreach ($applicationData as $application) {
            $skills = DB::table('users_skills')
                ->where('user_id', $application->student_id)
                ->pluck('skill_name')
                ->toArray();
            $studentSkills[$application->student_id] = $skills;
        }

        return view('adminDashboard.application', compact('applicationData', 'studentSkills'));
    }



    public function driveApggqqplication($companyId)
    {
        // Get company details
        $company = DB::table('placement_drive')->where('id', $companyId)->first();

        if (!$company) {
            return redirect()->route('admin.placement.companies')->with('error', 'Company not found.');
        }

        // Get applications for this company
        $applicationData = DB::table('placement_applications')
            ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
            ->leftJoin('users_resume', function ($join) {
                $join->on('users_resume.user_id', '=', 'users.id')
                    ->where('users_resume.is_active', true);
            })
            ->where('placement_drive.id', $companyId)
            ->select(
                'placement_applications.id as application_id',
                'users.id as student_id',
                'users.name as student_name',
                'users.email as student_email',
                'users.phone',
                'users_profile.roll_number',
                'users_profile.college',
                'users_profile.course',
                'users_profile.department',
                'users_profile.gender',
                'users_profile.date_of_birth',
                'users_profile.passing_year',
                'users_profile.cgpa',
                'placement_drive.company_name',
                'placement_drive.job_role',
                'placement_drive.drive_date',
                'placement_drive.package_offered',
                'placement_drive.eligibility_cgpa',
                'placement_drive.eligibility_passing_year',
                'placement_applications.application_status',
                'placement_applications.applied_at',
                'placement_applications.shortlisted_at',
                'placement_applications.selected_at',
                'placement_applications.remarks',
                'users_resume.resume_path',
                'users_resume.resume_title'
            )
            ->orderBy('placement_applications.applied_at', 'desc')
            ->get();

        // Get skills for each student
        $studentSkills = [];
        foreach ($applicationData as $application) {
            $skills = DB::table('users_skills')
                ->where('user_id', $application->student_id)
                ->pluck('skill_name')
                ->toArray();
            $studentSkills[$application->student_id] = $skills;
        }

        return view('adminDashboard.application', compact('applicationData', 'studentSkills', 'company'));
    }


    //  public function driveApplication($companyId)
    // {
    //     // Get company details
    //     $company = DB::table('placement_drive')->where('id', $companyId)->first();

    //     if (!$company) {
    //         return redirect()->route('admin.placement.companies')->with('error', 'Company not found.');
    //     }

    //     // Get applications with student data and their latest active resume
    //     $applicationData = DB::table('placement_applications')
    //         ->join('placement_drive', 'placement_applications.placement_drive_id', '=', 'placement_drive.id')
    //         ->join('users', 'placement_applications.student_id', '=', 'users.id')
    //         ->join('users_profile', 'users_profile.user_id', '=', 'users.id')
    //         ->leftJoin('college', 'users_profile.college', '=', 'college.id')
    //         ->leftJoin('courses', 'users_profile.course', '=', 'courses.id')
    //         ->leftJoin('departments', 'users_profile.department', '=', 'departments.id')
    //         ->leftJoin('users_resume', function ($join) {
    //             $join->on('users_resume.user_id', '=', 'users.id')
    //                 ->where('users_resume.is_active', true)
    //                 ->whereRaw('users_resume.id = (
    //                  SELECT MAX(ur2.id) FROM users_resume ur2 
    //                  WHERE ur2.user_id = users_resume.user_id AND ur2.is_active = true
    //              )');
    //         })
    //         ->where('placement_drive.id', $companyId)
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
    //             'placement_applications.application_status',
    //             'placement_applications.applied_at',
    //             'placement_applications.college_by',
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

    //     return view('adminDashboard.application', compact(
    //         'applicationData',
    //         'studentSkills',
    //         'company',
    //         'allSkills',
    //         'allCourses',
    //         'allDepartments',
    //         'allColleges'
    //     ));
    // }

    public function driveApplication($companyId)
    {
        // Get company details
        $company = DB::table('placement_drive')->where('id', $companyId)->first();

        if (!$company) {
            return redirect()->route('admin.placement.companies')->with('error', 'Company not found.');
        }

        // Get applications with student data and their latest active resume
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
            ->where('placement_drive.id', $companyId)
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
                'placement_drive.company_name',
                'placement_drive.job_title',
                'placement_drive.drive_date',
                'placement_drive.package_offered',
                'placement_drive.is_reappear as drive_is_reappear',
                'placement_applications.application_status',
                'placement_applications.applied_at',
                'placement_applications.college_by',
                'users_resume.resume_path'
            )
            ->orderBy('placement_applications.applied_at', 'desc')
            ->get();

        // Get academic history for all students in one query
        $studentIds = $applicationData->pluck('student_id')->unique()->toArray();
        $academicHistory = DB::table('academic_history')
            ->whereIn('user_id', $studentIds)
            ->where('is_active', true)
            ->select('user_id', 'education_level', 'grade', 'grade_type')
            ->get()
            ->groupBy('user_id');

        // Get user data for reappear check
        $usersData = DB::table('users')
            ->whereIn('id', $studentIds)
            ->select('id', 'is_reappear')
            ->get()
            ->keyBy('id');


        // Parse company eligibility criteria
        $driveCourses = json_decode($company->course_id, true) ?? [];
        $driveDepartments = json_decode($company->department_id, true) ?? [];
        $drivePassingYears = json_decode($company->eligibility_passing_year, true) ?? [];

        // Ensure arrays
        $driveCourses = is_array($driveCourses) ? $driveCourses : [];
        $driveDepartments = is_array($driveDepartments) ? $driveDepartments : [];
        $drivePassingYears = is_array($drivePassingYears) ? $drivePassingYears : [];


        // Get filter options from the actual tables
        $allCourses = DB::table('courses')->whereIn('id', $driveCourses)->pluck('name')->sort();
        $allDepartments = DB::table('departments')->whereIn('id', $driveDepartments)->pluck('name')->sort();

        return view('adminDashboard.application', compact(
            'applicationData',
            'company',
            'allCourses',
            'allDepartments',
            'drivePassingYears',
            'academicHistory',
            'usersData'
        ));
    }



    // New method to update college_by status
    public function updateCollegeBy(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
            'placement_drive_id' => 'required|exists:placement_drive,id'
        ]);

        try {
            DB::table('placement_applications')
                ->where('placement_drive_id', $request->placement_drive_id)
                ->whereIn('student_id', $request->student_ids)
                ->update([
                    'college_by' => 1,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated ' . count($request->student_ids) . ' student(s)'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating students: ' . $e->getMessage()
            ], 500);
        }
    }


    // public function manage_admin_dashboard()
    // {
    //     // Check if the user is authenticated
    //     if (!Auth::check()) {
    //         return redirect()->route('login')->withErrors(['message' => 'You must be logged in to access this page.']);
    //     }

    //     // Check the user's role and return the appropriate view
    //     $user = Auth::user();
    //     if ($user->role === 'admin') {
    //         return view('adminDashboard.index');
    //     } elseif ($user->role === 'user') {
    //         return view('index');
    //     } elseif ($user->role === 'company') {
    //         return view('companyDashboard.welcome');
    //     }

    //     // Handle unauthorized access for roles not explicitly allowed
    //     return redirect()->route('index')->withErrors(['message' => 'You are not authorized to access this page.']);
    // }


    public function adminUserProfile($userId)
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

        $userResume = DB::table('users_resume')
            ->where('user_id', $userId)
            ->where('users_resume.is_active', true)
            ->first();



        // Simple completion calculation
        $completionPercent = $this->calculateCompletionForUser($userId);

        return view('adminDashboard.user_profile', compact('user', 'skills', 'academicHistory', 'resumePath', 'projects', 'experiences', 'userProfile', 'completionPercent', 'userResume'));
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

    public function showDriveStats()
    {
        // Get overall application statistics
        $overallStats = DB::table('placement_applications')
            ->selectRaw('
                COUNT(*) as total_applications,
                SUM(CASE WHEN application_status = "shortlisted" THEN 1 ELSE 0 END) as shortlisted,
                SUM(CASE WHEN application_status = "selected" THEN 1 ELSE 0 END) as selected
            ')
            ->first();

        // Calculate percentages
        $totalApplications = $overallStats->total_applications ?? 0;
        $shortlistedCount = $overallStats->shortlisted ?? 0;
        $selectedCount = $overallStats->selected ?? 0;

        $shortlistRate = $totalApplications > 0
            ? round(($shortlistedCount / $totalApplications) * 100, 1)
            : 0;

        $selectionRate = $totalApplications > 0
            ? round(($selectedCount / $totalApplications) * 100, 1)
            : 0;

        // Get company-wise statistics
        $companyStats = DB::table('placement_applications as pa')
            ->join('placement_drive as pd', 'pa.placement_drive_id', '=', 'pd.id')
            ->select(
                'pd.id',
                'pd.company_name',
                'pd.job_title',
                DB::raw('COUNT(pa.id) as total_applied'),
                DB::raw('SUM(CASE WHEN pa.application_status = "shortlisted" THEN 1 ELSE 0 END) as shortlisted'),
                DB::raw('SUM(CASE WHEN pa.application_status = "selected" THEN 1 ELSE 0 END) as selected')
            )
            ->where('pd.is_active', 1)
            ->groupBy('pd.id', 'pd.company_name', 'pd.job_title')
            ->orderBy('pd.company_name')
            ->get();

        // Get application funnel data for the chart
        $funnelData = DB::table('placement_applications')
            ->selectRaw('
                COUNT(*) as applied,
                SUM(CASE WHEN application_status = "shortlisted" THEN 1 ELSE 0 END) as shortlisted,
                SUM(CASE WHEN application_status = "selected" THEN 1 ELSE 0 END) as selected
            ')
            ->first();

        return view('adminDashboard.driveStats', compact(
            'totalApplications',
            'shortlistedCount',
            'selectedCount',
            'shortlistRate',
            'selectionRate',
            'companyStats',
            'funnelData'
        ));
    }

    public function driveStatsDetail($id)
    {
        // Get drive basic information
        $driveInfo = DB::table('placement_drive')
            ->select(
                'id',
                'company_name',
                'job_title',
                'drive_date',
                'application_deadline',
                'location',
                'package_offered',
                'vacancies',
                'contact_email',
                'company_website',
                'description',
                'drive_type',
                'status'
            )
            ->where('id', $id)
            ->where('is_active', 1)
            ->first();

        if (!$driveInfo) {
            abort(404, 'Placement drive not found');
        }

        // Get application statistics for this drive
        $driveStats = DB::table('placement_applications')
            ->selectRaw('
                COUNT(*) as total_applied,
                SUM(CASE WHEN application_status = "shortlisted" THEN 1 ELSE 0 END) as shortlisted,
                SUM(CASE WHEN application_status = "selected" THEN 1 ELSE 0 END) as selected,
                SUM(CASE WHEN application_status = "rejected" THEN 1 ELSE 0 END) as rejected
            ')
            ->where('placement_drive_id', $id)
            ->first();

        // Get growth compared to previous period (last 30 days)
        $previousStats = DB::table('placement_applications')
            ->selectRaw('
                COUNT(*) as total_applied,
                SUM(CASE WHEN application_status = "shortlisted" THEN 1 ELSE 0 END) as shortlisted,
                SUM(CASE WHEN application_status = "selected" THEN 1 ELSE 0 END) as selected
            ')
            ->where('placement_drive_id', $id)
            ->where('applied_at', '<', now()->subDays(30))
            ->first();

        // Calculate growth percentages
        $appliedGrowth = $previousStats->total_applied > 0
            ? round((($driveStats->total_applied - $previousStats->total_applied) / $previousStats->total_applied) * 100, 1)
            : 0;

        $shortlistedGrowth = $previousStats->shortlisted > 0
            ? round((($driveStats->shortlisted - $previousStats->shortlisted) / $previousStats->shortlisted) * 100, 1)
            : 0;

        $selectedGrowth = $previousStats->selected > 0
            ? round((($driveStats->selected - $previousStats->selected) / $previousStats->selected) * 100, 1)
            : 0;

        // Get recent activities
        $recentActivities = DB::table('placement_applications as pa')
            ->join('users as s', 'pa.student_id', '=', 's.id')
            ->select(
                'pa.application_status',
                'pa.applied_at',
                'pa.shortlisted_at',
                'pa.selected_at',
                's.name as student_name',
                DB::raw('CASE 
                    WHEN pa.selected_at IS NOT NULL THEN "selected"
                    WHEN pa.shortlisted_at IS NOT NULL THEN "shortlisted" 
                    ELSE "applied"
                END as activity_type')
            )
            ->where('pa.placement_drive_id', $id)
            ->orderBy('pa.applied_at', 'desc')
            ->limit(50)
            ->get();

        // Calculate selection rates for the funnel
        $totalApplied = $driveStats->total_applied ?: 1; // Avoid division by zero
        $shortlistRate = round(($driveStats->shortlisted / $totalApplied) * 100, 1);
        $selectionRate = round(($driveStats->selected / $totalApplied) * 100, 1);

        return view('adminDashboard.driveStatsDetail', compact(
            'driveInfo',
            'driveStats',
            'appliedGrowth',
            'shortlistedGrowth',
            'selectedGrowth',
            'recentActivities',
            'shortlistRate',
            'selectionRate'
        ));
    }

    public function selectedStudents($id)
    {
        $companyId = $id;
        // Verify the company exists
        $company = DB::table('placement_drive')
            ->where('id', $companyId)
            ->first();

        if (!$company) {
            return redirect()->back()->with('error', 'Company drive not found.');
        }

        // First, get unique student IDs who are selected
        $studentIds = DB::table('placement_applications')
            ->where('placement_drive_id', $companyId)
            ->where('application_status', 'selected')
            ->pluck('student_id')
            ->unique()
            ->toArray();

        // Then get student details without multiple joins that cause duplicates
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

        // Get resume paths separately
        $resumePaths = DB::table('users_resume')
            ->whereIn('user_id', $studentIds)
            ->where('is_active', true)
            ->select('user_id', 'resume_path')
            ->get()
            ->keyBy('user_id');

        // Get skills for students
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

        // Add resume paths to students
        foreach ($selectedStudents as $student) {
            $student->resume_path = $resumePaths[$student->student_id]->resume_path ?? null;
            $student->selected_at = DB::table('placement_applications')
                ->where('placement_drive_id', $companyId)
                ->where('student_id', $student->student_id)
                ->where('application_status', 'selected')
                ->value('selected_at');
        }

        return view('adminDashboard.selectedStudent', compact(
            'selectedStudents',
            'studentSkills',
            'company'
        ));
    }

    public function shortlistedStudents($id)
    {
        // Get the company user's ID
        $driveId = $id;

        // Verify the company user owns this drive
        $company = DB::table('placement_drive')
            ->where('id', $driveId)
            ->first();

        if (!$company) {
            return redirect()->back()->with('error', 'Drive not found or access denied.');
        }

        // Get shortlisted students for viewing
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
            ->where('placement_drive.id', $driveId)
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

        // Get skills for all shortlisted students
        $studentIds = $shortlistedStudents->pluck('student_id')->unique()->toArray();
        $skillsData = DB::table('users_skills')
            ->whereIn('user_id', $studentIds)
            ->select('user_id', 'skill_name')
            ->get();

        $studentSkills = [];
        foreach ($skillsData as $skill) {
            $studentSkills[$skill->user_id][] = $skill->skill_name;
        }

        return view('adminDashboard.shortlistedStudent', compact(
            'shortlistedStudents',
            'studentSkills',
            'company'
        ));
    }

    public function adminSendCustomMailOrNotification(Request $request)
    {
        Log::info('Notification request received', $request->all());

        $request->validate([
            'message' => 'required|string',
            'link' => 'nullable|url',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,txt|max:5120',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'drive_id' => 'required|exists:placement_drive,id',
        ]);

        $messageBody = $request->message;
        $userIds = $request->user_ids;
        $driveId = $request->drive_id;
        $link = $request->link;

        Log::info('Processing notification', [
            'user_ids_count' => count($userIds),
            'drive_id' => $driveId,
            'has_link' => !empty($link),
            'has_attachment' => $request->hasFile('attachment')
        ]);

        // Determine if link is provided
        $isLink = 0;
        if (!empty($link)) {
            $link = trim($link);
            if (filter_var($link, FILTER_VALIDATE_URL) && $link !== '#') {
                $isLink = 1;
            } else {
                $link = null;
            }
        }

        // Get drive information
        $drive = DB::table('placement_drive')->where('id', $driveId)->first();
        if (!$drive) {
            Log::error('Drive not found', ['drive_id' => $driveId]);
            return redirect()->back()->with('error', 'Drive not found.');
        }

        // Handle file upload
        $attachmentPath = null;
        $isAttachment = 0;
        $tempFilePath = null; // Track temp file

        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            try {
                $file = $request->file('attachment');

                // Generate filename
                $cleanCompanyName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $drive->company_name);
                $cleanCompanyName = substr($cleanCompanyName, 0, 50);
                $extension = $file->getClientOriginalExtension();
                $filename = 'notification_' . $cleanCompanyName . '_' . $driveId . '_' . time() . '.' . $extension;

                // Step 1: Upload to temp directory first
                $tempDir = 'temp_notification_attachments';
                $tempDirPath = public_path($tempDir);

                // Create temp directory if it doesn't exist
                if (!file_exists($tempDirPath)) {
                    mkdir($tempDirPath, 0755, true);
                }

                // Move file to temp directory
                if ($file->move($tempDirPath, $filename)) {
                    $tempFilePath = $tempDir . '/' . $filename;
                    Log::info('File uploaded to temp directory', ['temp_path' => $tempFilePath]);

                    // Step 2: Prepare final directory
                    $finalDir = 'notification_attachments';
                    $finalDirPath = public_path($finalDir);

                    // Create final directory if it doesn't exist
                    if (!file_exists($finalDirPath)) {
                        mkdir($finalDirPath, 0755, true);
                    }

                    // Step 3: Copy from temp to final directory
                    $finalPath = $finalDir . '/' . $filename;
                    if (copy(public_path($tempFilePath), public_path($finalPath))) {
                        $attachmentPath = $finalPath;
                        $isAttachment = 1;
                        Log::info('File copied to final directory', ['final_path' => $attachmentPath]);

                        // Step 4: Delete temp file
                        if (file_exists(public_path($tempFilePath))) {
                            unlink(public_path($tempFilePath));
                            Log::info('Temp file deleted', ['temp_path' => $tempFilePath]);
                        }
                    } else {
                        Log::error('Failed to copy file from temp to final directory');
                    }
                }
            } catch (\Exception $e) {
                Log::error('File upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                // Clean up temp file if exists
                if ($tempFilePath && file_exists(public_path($tempFilePath))) {
                    unlink(public_path($tempFilePath));
                }
            }
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($userIds as $userId) {
            try {
                Log::info('Processing user', ['user_id' => $userId]);

                $user = DB::table('users')->where('id', $userId)->first();
                if (!$user) {
                    Log::warning('User not found', ['user_id' => $userId]);
                    $errorCount++;
                    continue;
                }

                // Personalize message
                $personalizedMessage = str_replace(
                    ['{student_name}', '{company_name}', '{job_title}', '{drive_date}'],
                    [
                        $user->name,
                        $drive->company_name ?? 'Company',
                        $drive->job_title ?? 'Position',
                        $drive->drive_date ? \Carbon\Carbon::parse($drive->drive_date)->format('d M Y') : 'TBD'
                    ],
                    $messageBody
                );

                // Create notification with proper flags
                DB::table('drive_notifications')->insert([
                    'user_id' => $userId,
                    'drive_id' => $driveId,
                    'message' => $personalizedMessage,
                    'link' => $link,
                    'attachment_path' => $attachmentPath,
                    'is_link' => $isLink,
                    'is_attachment' => $isAttachment,
                    'from_admin' => 1,
                    'read_at' => null, // Explicitly set read_at to null
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $successCount++;
                Log::info('Notification created for user', ['user_id' => $userId]);
            } catch (\Exception $e) {
                Log::error("Failed to create notification for user {$userId}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $errorCount++;
            }
        }

        // Final cleanup: Delete temp file if it still exists (in case of errors)
        if ($tempFilePath && file_exists(public_path($tempFilePath))) {
            unlink(public_path($tempFilePath));
            Log::info('Cleaned up orphaned temp file', ['temp_path' => $tempFilePath]);
        }

        Log::info('Notification process completed', [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'has_attachment' => $isAttachment,
            'attachment_path' => $attachmentPath
        ]);

        if ($successCount > 0) {
            $message = "Notifications sent successfully to {$successCount} student(s).";

            if ($errorCount > 0) {
                $message .= " Failed for {$errorCount} student(s).";
            }

            // Add content details
            if ($isLink && $isAttachment) {
                $message .= " Link and attachment included.";
            } elseif ($isLink) {
                $message .= " Link included.";
            } elseif ($isAttachment) {
                $message .= " Attachment included.";
            }

            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Failed to send notifications to any students. Check the logs for details.');
        }
    }







    // Admin functions
    public function renderAdminAnnouncementsDashboard()
    {
        $announcements = DB::table('announcements')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('adminDashboard.announcements.index', compact('announcements'));
    }

    public function displayAnnouncementCreationForm()
    {
        return view('adminDashboard.announcements.create');
    }

    public function processAnnouncementCreation(Request $request)
    {
        // Simple validation without Validator
        if (!$request->title || !$request->type || !$request->priority || !$request->publish_date) {
            return redirect()->back()
                ->with('error', 'Please fill all required fields.')
                ->withInput();
        }

        $filePath = null;
        $tempFilePath = null; // Track temp file

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            try {
                $file = $request->file('file');

                // Validate file type
                $allowedMimes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt'];
                $extension = $file->getClientOriginalExtension();

                if (!in_array(strtolower($extension), $allowedMimes)) {
                    return redirect()->back()
                        ->with('error', 'Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT.')
                        ->withInput();
                }

                // Validate file size (max 10MB)
                if ($file->getSize() > 10485760) {
                    return redirect()->back()
                        ->with('error', 'File size exceeds 10MB limit.')
                        ->withInput();
                }

                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\.]/', '_', $file->getClientOriginalName());

                // Step 1: Upload to temp directory first
                $tempDir = 'temp_announcements';
                $tempDirPath = public_path($tempDir);

                // Create temp directory if it doesn't exist
                if (!file_exists($tempDirPath)) {
                    mkdir($tempDirPath, 0755, true);
                }

                // Move file to temp directory
                if ($file->move($tempDirPath, $fileName)) {
                    $tempFilePath = $tempDir . '/' . $fileName;

                    // Step 2: Prepare final directory
                    $finalDir = 'uploads/announcements';
                    $finalDirPath = public_path($finalDir);

                    // Create final directory if it doesn't exist
                    if (!file_exists($finalDirPath)) {
                        mkdir($finalDirPath, 0755, true);
                    }

                    // Step 3: Copy from temp to final directory
                    $finalPath = $finalDir . '/' . $fileName;
                    if (copy(public_path($tempFilePath), public_path($finalPath))) {
                        $filePath = $finalPath;

                        // Step 4: Delete temp file
                        if (file_exists(public_path($tempFilePath))) {
                            unlink(public_path($tempFilePath));
                        }
                    } else {
                        // Clean up temp file if copy failed
                        if (file_exists(public_path($tempFilePath))) {
                            unlink(public_path($tempFilePath));
                        }
                        return redirect()->back()
                            ->with('error', 'Failed to save announcement file. Please try again.')
                            ->withInput();
                    }
                } else {
                    return redirect()->back()
                        ->with('error', 'Failed to upload announcement file. Please try again.')
                        ->withInput();
                }
            } catch (\Exception $e) {
                // Clean up temp file if exists
                if ($tempFilePath && file_exists(public_path($tempFilePath))) {
                    unlink(public_path($tempFilePath));
                }

                Log::error('Announcement file upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Error uploading announcement file: ' . $e->getMessage())
                    ->withInput();
            }
        }

        try {
            DB::table('announcements')->insert([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'priority' => $request->priority,
                'file_path' => $filePath,
                'external_link' => $request->external_link,
                'publish_date' => $request->publish_date,
                'expiry_date' => $request->expiry_date,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Final cleanup: Delete temp file if it still exists
            if ($tempFilePath && file_exists(public_path($tempFilePath))) {
                unlink(public_path($tempFilePath));
            }

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement created successfully.');
        } catch (\Exception $e) {
            // Clean up uploaded file if database insert fails
            if ($filePath && file_exists(public_path($filePath))) {
                unlink(public_path($filePath));
            }

            Log::error('Announcement creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error creating announcement: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function displayAnnouncementEditForm($id)
    {
        $announcement = DB::table('announcements')->where('id', $id)->first();

        if (!$announcement) {
            abort(404);
        }

        return view('adminDashboard.announcements.edit', compact('announcement'));
    }

    public function processAnnouncementUpdate(Request $request, $id)
    {
        // Simple validation without Validator
        if (!$request->title || !$request->type || !$request->priority || !$request->publish_date) {
            return redirect()->back()
                ->with('error', 'Please fill all required fields.')
                ->withInput();
        }

        $announcement = DB::table('announcements')->where('id', $id)->first();

        if (!$announcement) {
            abort(404);
        }

        $filePath = $announcement->file_path;
        $tempFilePath = null; // Track temp file

        if ($request->hasFile('file')) {
            try {
                $file = $request->file('file');

                // Validate file type
                $allowedMimes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'txt'];
                $extension = $file->getClientOriginalExtension();

                if (!in_array(strtolower($extension), $allowedMimes)) {
                    return redirect()->back()
                        ->with('error', 'Invalid file type. Allowed types: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT.')
                        ->withInput();
                }

                // Validate file size (max 10MB)
                if ($file->getSize() > 10485760) {
                    return redirect()->back()
                        ->with('error', 'File size exceeds 10MB limit.')
                        ->withInput();
                }

                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\.]/', '_', $file->getClientOriginalName());

                // Step 1: Upload to temp directory first
                $tempDir = 'temp_announcements';
                $tempDirPath = public_path($tempDir);

                // Create temp directory if it doesn't exist
                if (!file_exists($tempDirPath)) {
                    mkdir($tempDirPath, 0755, true);
                }

                // Move file to temp directory
                if ($file->move($tempDirPath, $fileName)) {
                    $tempFilePath = $tempDir . '/' . $fileName;

                    // Step 2: Prepare final directory
                    $finalDir = 'uploads/announcements';
                    $finalDirPath = public_path($finalDir);

                    // Create final directory if it doesn't exist
                    if (!file_exists($finalDirPath)) {
                        mkdir($finalDirPath, 0755, true);
                    }

                    // Step 3: Copy from temp to final directory
                    $finalPath = $finalDir . '/' . $fileName;
                    if (copy(public_path($tempFilePath), public_path($finalPath))) {
                        // Delete old file if exists (only after successful copy)
                        if ($announcement->file_path && file_exists(public_path($announcement->file_path))) {
                            File::delete(public_path($announcement->file_path));
                        }

                        $filePath = $finalPath;

                        // Step 4: Delete temp file
                        if (file_exists(public_path($tempFilePath))) {
                            unlink(public_path($tempFilePath));
                        }
                    } else {
                        // Clean up temp file if copy failed
                        if (file_exists(public_path($tempFilePath))) {
                            unlink(public_path($tempFilePath));
                        }
                        return redirect()->back()
                            ->with('error', 'Failed to save announcement file. Please try again.')
                            ->withInput();
                    }
                } else {
                    return redirect()->back()
                        ->with('error', 'Failed to upload announcement file. Please try again.')
                        ->withInput();
                }
            } catch (\Exception $e) {
                // Clean up temp file if exists
                if ($tempFilePath && file_exists(public_path($tempFilePath))) {
                    unlink(public_path($tempFilePath));
                }

                Log::error('Announcement file upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Error uploading announcement file: ' . $e->getMessage())
                    ->withInput();
            }
        }

        try {
            DB::table('announcements')
                ->where('id', $id)
                ->update([
                    'title' => $request->title,
                    'content' => $request->content,
                    'type' => $request->type,
                    'priority' => $request->priority,
                    'file_path' => $filePath,
                    'external_link' => $request->external_link,
                    'publish_date' => $request->publish_date,
                    'expiry_date' => $request->expiry_date,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    'updated_at' => now(),
                ]);

            // Final cleanup: Delete temp file if it still exists
            if ($tempFilePath && file_exists(public_path($tempFilePath))) {
                unlink(public_path($tempFilePath));
            }

            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement updated successfully.');
        } catch (\Exception $e) {
            // If database update fails and we uploaded a new file, delete it and restore old file
            if ($request->hasFile('file') && $filePath && $filePath !== $announcement->file_path) {
                if (file_exists(public_path($filePath))) {
                    unlink(public_path($filePath));
                }
            }

            Log::error('Announcement update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error updating announcement: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function executeAnnouncementDeletion($id)
    {
        $announcement = DB::table('announcements')->where('id', $id)->first();

        if (!$announcement) {
            abort(404);
        }

        // Delete file if exists
        if ($announcement->file_path && File::exists(public_path($announcement->file_path))) {
            File::delete(public_path($announcement->file_path));
        }

        DB::table('announcements')->where('id', $id)->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    public function toggleAnnouncementStatus($id)
    {
        $announcement = DB::table('announcements')->where('id', $id)->first();

        if (!$announcement) {
            abort(404);
        }

        DB::table('announcements')
            ->where('id', $id)
            ->update([
                'is_active' => $announcement->is_active ? 0 : 1,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Status updated successfully.');
    }

    // Utility function to get file URL
    public function getAnnouncementFileUrl($filePath)
    {
        if ($filePath && File::exists(public_path($filePath))) {
            return asset($filePath);
        }
        return null;
    }

    // Show all placement officers
    public function placementOfficersIndex()
    {
        $placementOfficers = DB::table('users')
            ->where('role', 'placement_officer')
            ->leftJoin('college', 'users.college_id', '=', 'college.id')
            ->select('users.*', 'college.name as college_name')
            ->orderBy('users.created_at', 'desc')
            ->paginate(10);

        $colleges = DB::table('college')
            ->orderBy('name')
            ->get();

        return view('adminDashboard.placement_officers.index', compact('placementOfficers', 'colleges'));
    }

    // Show create form
    public function placementOfficersCreateform()
    {
        $colleges = DB::table('college')
            ->orderBy('name')
            ->get();

        return view('adminDashboard.placement_officers.create', compact('colleges'));
    }

    // Store new placement officer
    public function placementOfficersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:13',
            'college_id' => 'required|exists:college,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $userId = DB::table('users')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'placement_officer',
                'college_id' => $request->college_id,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Convert array to object
            $user = (object) $request->only('name', 'email', 'phone');
            $plainPassword = $request->password;

            Mail::to($request->email)->send(
                new PlacementOfficerCreatedMail($user, $plainPassword)
            );

            return redirect()->route('admin.placement-officers.index')
                ->with('success', 'Placement officer created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating placement officer: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Show edit form
    public function placementOfficersEdit($id)
    {
        $officer = DB::table('users')
            ->where('id', $id)
            ->where('role', 'placement_officer')
            ->first();

        if (!$officer) {
            return back()->with('error', 'Placement officer not found!');
        }

        $colleges = DB::table('college')
            ->orderBy('name')
            ->get();

        return view('adminDashboard.placement_officers.edit', compact('officer', 'colleges'));
    }

    // Update placement officer
    public function placementOfficersUpdate(Request $request, $id)
    {
        // Check if officer exists
        $officer = DB::table('users')
            ->where('id', $id)
            ->where('role', 'placement_officer')
            ->first();

        if (!$officer) {
            return back()->with('error', 'Placement officer not found!');
        }

        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:13',
            'college_id' => 'required|exists:college,id',
        ]);

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'college_id' => $request->college_id,
                'updated_at' => now(),
            ];



            DB::table('users')
                ->where('id', $id)
                ->update($updateData);

            return redirect()->route('admin.placement-officers.index')
                ->with('success', 'Placement officer updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating placement officer: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Delete placement officer
    public function placementOfficersDestroy($id)
    {
        try {
            $deleted = DB::table('users')
                ->where('id', $id)
                ->where('role', 'placement_officer')
                ->delete();

            if ($deleted) {
                return redirect()->route('admin.placement-officers.index')
                    ->with('success', 'Placement officer deleted successfully!');
            }

            return back()->with('error', 'Placement officer not found!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting placement officer: ' . $e->getMessage());
        }
    }

    // Toggle active status
    public function placementOfficersToggleStatus($id)
    {
        try {
            $officer = DB::table('users')
                ->where('id', $id)
                ->where('role', 'placement_officer')
                ->first();

            if (!$officer) {
                return back()->with('error', 'Placement officer not found!');
            }

            $newStatus = $officer->is_active ? 0 : 1;

            DB::table('users')
                ->where('id', $id)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);

            $status = $newStatus ? 'activated' : 'deactivated';

            return back()->with('success', "Placement officer {$status} successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }
}

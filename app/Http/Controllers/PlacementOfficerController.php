<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlacementOfficerController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // First, check if user is a placement officer
        if ($user->role != 'placement_officer') {
            abort(403, 'Unauthorized access');
        }

        // Check if placement officer has college assigned
        if (!$user->college_id) {
            return back()->with('error', 'No college assigned to your account. Please contact administrator.');
        }

        // Get statistics
        $totalStudents = DB::table('users')
            ->join('users_profile', 'users.id', '=', 'users_profile.user_id')
            ->where('role', 'user')
            ->where('users_profile.college', $user->college_id)
            ->count();

        $totalDrives = DB::table('placement_drive')
            ->where(function ($query) use ($user) {
                $query->whereJsonContains('college_id', $user->college_id)
                    ->orWhere('college_id', $user->college_id);
            })
            ->where('is_active', 1)
            ->count();

        // Get selected students count (application_status = 'selected')
        $selectedStudents = DB::table('placement_applications')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->where('users.college_id', $user->college_id)
            ->where('placement_applications.application_status', 'selected')
            ->count();

        // Get shortlisted students count (application_status = 'shortlisted')
        $shortlistedStudents = DB::table('placement_applications')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->where('users.college_id', $user->college_id)
            ->where('placement_applications.application_status', 'shortlisted')
            ->distinct('placement_applications.student_id')
            ->count();

        // Get placed students (same as selected students)
        $placedStudents = $selectedStudents;

        // Get unplaced students (total students minus selected students)
        $unplacedStudents = $totalStudents - $selectedStudents;

        // Get college name
        $college = DB::table('college')
            ->where('id', $user->college_id)
            ->first();

        return view('placement_officerDashboard.index', compact(
            'totalStudents',
            'totalDrives',
            'selectedStudents',
            'shortlistedStudents',
            'placedStudents',
            'unplacedStudents',
            'college'
        ));
    }

    public function allStudents()
    {
        $user = Auth::user();
        if ($user->role != 'placement_officer') {
            abort(403, 'Unauthorized access');
        }

        // Get students data
        $students = DB::table('users_profile')
            ->join('users', 'users_profile.user_id', '=', 'users.id')
            ->leftJoin('courses', 'users_profile.course', '=', 'courses.id')
            ->leftJoin('departments', 'users_profile.department', '=', 'departments.id')
            ->where('users.role', 'user')
            ->where('users.is_active', 1)
            ->where('users_profile.college', $user->college_id)
            ->select(
                'users_profile.*',
                'users.id as user_id',
                'users.name as user_name',
                'users.email',
                'courses.name as course_name',
                'departments.name as department_name',
                'users.is_reappear',
                'users_profile.passing_year',
            )
            ->orderBy('users.name')
            ->get();

        // Get college info
        $college = DB::table('college')
            ->where('id', $user->college_id)
            ->first();

        // Get statistics
        $totalStudents = $students->count();

        // Get placed students count (from placement_applications where status = 'selected')
        $placedStudents = DB::table('placement_applications')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->where('users.college_id', $user->college_id)
            ->where('placement_applications.application_status', 'selected')
            ->distinct('placement_applications.student_id')
            ->count();

        // Get shortlisted students count
        $shortlistedStudents = DB::table('placement_applications')
            ->join('users', 'placement_applications.student_id', '=', 'users.id')
            ->where('users.college_id', $user->college_id)
            ->where('placement_applications.application_status', 'shortlisted')
            ->distinct('placement_applications.student_id')
            ->count();

        // Get department-wise distribution
        $departmentStats = $students->groupBy('department_name')
            ->map(function ($group) {
                return $group->count();
            });

        // Get CGPA distribution
        $cgpaStats = $students->groupBy(function ($student) {
            if ($student->cgpa >= 9) return '9+';
            if ($student->cgpa >= 8) return '8-9';
            if ($student->cgpa >= 7) return '7-8';
            if ($student->cgpa >= 6) return '6-7';
            return '<6';
        })->map->count();

        return view('placement_officerDashboard.all_students', compact(
            'students',
            'college',
            'totalStudents',
            'placedStudents',
            'shortlistedStudents',
            'departmentStats',
            'cgpaStats'
        ));
    }

    public function allPlacementDrives()
    {
        $user = Auth::user();
        if ($user->role != 'placement_officer') {
            abort(403, 'Unauthorized access');
        }

        $placementDrives = DB::table('placement_drive')
            ->whereJsonContains('college_id', $user->college_id)
            ->orWhere('college_id', $user->college_id)
            ->where('is_active', 1)
            ->orderBy('drive_date', 'desc')
            ->get();

        // Get application counts for each drive
        $driveIds = $placementDrives->pluck('id');

        // Assuming you have an 'applications' or 'student_applications' table
        // that has a 'placement_drive_id' column
        $applicationCounts = DB::table('placement_applications') // or 'student_applications'
            ->select('placement_drive_id', DB::raw('COUNT(*) as count'))
            ->whereIn('placement_drive_id', $driveIds)
            ->groupBy('placement_drive_id')
            ->pluck('count', 'placement_drive_id')
            ->toArray();

        // Add application count to each drive
        $placementDrives = $placementDrives->map(function ($drive) use ($applicationCounts) {
            $drive->application_count = $applicationCounts[$drive->id] ?? 0;
            return $drive;
        });

        return view('placement_officerDashboard.all_drives', compact('placementDrives'));
    }

 public function PlacementOfficerUserProfile($userId)
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

        return view('placement_officerDashboard.user_profile', compact('user', 'skills', 'academicHistory', 'resumePath', 'projects', 'experiences', 'userProfile', 'completionPercent', 'userResume'));
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

}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PlacementOfficerController;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [AuthController::class, 'home'])->name('home');
Route::get('/placement-drives', [AuthController::class, 'drive'])->name('index');
Route::get('/written', [AuthController::class, 'written'])->name('written');

Route::get('/account', [AuthController::class, 'accounts'])->name('accounts');



Route::get('/otp-request', [AuthController::class, 'otpRequest'])->name('otp.request');
Route::post('/otp-registered', [AuthController::class, 'otpRegistered'])->name('otp.registered');
Route::get('/otp-verify', [AuthController::class, 'otpVerify'])->name('otp.verify');
Route::post('/otp-verifed', [AuthController::class, 'otpVerifed'])->name('otp.verifed');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logined', [AuthController::class, 'logined'])->name('logined');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// routes/web.php
Route::get('/forget-password', [AuthController::class, 'showForgotPasswordForm'])->name('forget.password.request');
Route::post('/forget-password', [AuthController::class, 'forgetPassworded'])->name('password.forget');
Route::get('/verify-forget-otp', [AuthController::class, 'forgetOtpVerifyForm'])->name('forget.otp.verify.form');
Route::post('/verify-forget-otp', [AuthController::class, 'forgetOtpVerify'])->name('forget.otp.verify');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth');
Route::post('/profile-pic/update', [AuthController::class, 'updateProfilePic'])->name('updateProfilePic')->middleware('auth');
Route::post('/cover-photo/update', [AuthController::class, 'updateCoverPic'])->name('updateCoverPic')->middleware('auth');


Route::post('/profile/update-academic', [AuthController::class, 'updateAcademic'])->name('profile.updateAcademic');
Route::post('/profile/update-bio', [AuthController::class, 'updateBio'])->name('profile.updateBio');
Route::post('/profile/update-address', [AuthController::class, 'updateAddress'])->name('profile.updateAddress');




Route::get('/add-skill', [AuthController::class, 'addSkillForm'])->name('add.skill.form')->middleware('auth');
Route::post('/skill/add', [AuthController::class, 'addSkill'])->name('add.skill')->middleware('auth');
Route::post('/skill/edit/{skill_id}', [AuthController::class, 'editSkill'])->name('edit.skill')->middleware('auth');

Route::post('/skill/delete/{skill_id}', [AuthController::class, 'deleteSkill'])->name('delete.skill')->middleware('auth');

Route::get('/academic-history', [AuthController::class, 'academicHistoryForm'])->name('academic.history.form')->middleware('auth');
Route::post('/academic-history/add', [AuthController::class, 'addAcademicHistory'])->name('add.academic.history')->middleware('auth');
Route::post('/academic-history/edit/{history_id}', [AuthController::class, 'editAcademicHistory'])->name('edit.academic.history')->middleware('auth');
Route::post('/academic-history/delete/{history_id}', [AuthController::class, 'deleteAcademicHistory'])->name('delete.academic.history')->middleware('auth');

Route::get('/resume', [AuthController::class, 'resumeForm'])->name('resume')->middleware('auth');
Route::post('/resume/upload', [AuthController::class, 'uploadResume'])->name('resume.upload')->middleware('auth');
Route::post('/resume/edit', [AuthController::class, 'editResume'])->name('resume.edit')->middleware('auth');
Route::post('/resume/delete', [AuthController::class, 'deleteResume'])->name('resume.delete')->middleware('auth');

Route::get('/projects', [AuthController::class, 'projectForm'])->name('projects');
Route::post('/projects/add', [AuthController::class, 'addProject'])->name('projects.add');
Route::post('/projects/edit/{project_id}', [AuthController::class, 'editProject'])->name('projects.edit');
Route::post('/projects/delete/{project_id}', [AuthController::class, 'deleteProject'])->name('projects.delete');

Route::get('/experience', [AuthController::class, 'exprience'])->name('experience');
Route::post('/experience/add', [AuthController::class, 'addExperience'])->name('experience.add');
Route::post('/experience/edit/{experience_id}', [AuthController::class, 'editExprience'])->name('experience.edit');
Route::post('/experience/delete/{experience_id}', [AuthController::class, 'deleteExperience'])->name('experience.delete');

Route::get('/user-profile/edit', [AuthController::class, 'userProfileeditShow'])
   ->name('userProfileEditShow')
   ->middleware('auth');

Route::post('user-profile/edit', [AuthController::class, 'editProfile'])->name('editProfile')->middleware('auth');

Route::get('/drive-detail/{id}', [AuthController::class, 'driveDetail'])->name('drive.detail')->middleware('auth');

Route::get('/notifications', [AuthController::class, 'notifications'])->name('notifications');
Route::post('/notifications/{id}/mark-seen', [AuthController::class, 'markSeenNotification'])->name('notifications.markSeen');

Route::post('/apply/{drive_id}', [AuthController::class, 'applyPlacement'])->name('placement.apply');
Route::get('/applied-placements', [AuthController::class, 'appliedPlacements'])->name('applied.placements')->middleware('auth');
Route::get('/application-detail/{application_id}', [AuthController::class, 'applicationDetail'])->name('application.detail')->middleware('auth');

Route::post('/drive/toggle-save', [AuthController::class, 'toggleSave'])->name('drive.toggle-save')->middleware('auth');
Route::get('/saved-drives', [AuthController::class, 'savedDrives'])->name('saved.drives')->middleware('auth');

Route::get('/placement/stats', [AuthController::class, 'showUserStats'])->name('placement.stats')->middleware('auth');

Route::get('/announcements', [AuthController::class, 'displayAnnouncementsList'])->name('announcements.displayList');
Route::get('/announcements/{id}', [AuthController::class, 'showAnnouncementDetail'])->name('announcements.showDetail');
Route::get('/announcements/file/{id}', [AuthController::class, 'downloadAnnouncementFile'])->name('announcements.downloadFile');

Route::get('/help-center', [AuthController::class, 'helpCenter'])->name('helpCenter');


Route::group(['middleware' => ['role:admin']], function () {

Route::get('/site-config', [AdminController::class, 'siteConfig'])->name('adminDashboard.siteConfig');

Route::get('/admin/site-config/sliders', [AdminController::class, 'getSliders'])->name('adminDashboard.getSliders');

Route::post('/admin/site-config/store', [AdminController::class, 'storeSlider'])->name('adminDashboard.siteConfig.store');

Route::delete('/admin/site-config/delete/{id}', [AdminController::class, 'deleteSlider'])->name('adminDashboard.siteConfig.delete');

        Route::get('/admin/placement-updates', [AdminController::class, 'placement_update_index'])->name('placement-updates.index');
        Route::post('/admin/placement-updates/store', [AdminController::class, 'placement_update_store'])->name('placement-updates.store');
        Route::delete('/admin/placement-updates/destroy/{id}', [AdminController::class, 'placement_update_destroy'])->name('placement-updates.destroy');
        Route::post('/admin/placement-updates/update-order', [AdminController::class, 'placement_update_updateOrder'])->name('placement-updates.update-order');
        Route::post('/admin/placement-updates/toggle-status/{id}', [AdminController::class, 'placement_update_toggleStatus'])->name('placement-updates.toggle-status');

       Route::get('/admin/testimonials', [AdminController::class, 'testimonials_index'])->name('testimonials.index');
Route::post('/admin/testimonials/store', [AdminController::class, 'testimonials_store'])->name('testimonials.store');
Route::delete('/admin/testimonials/destroy/{id}', [AdminController::class, 'testimonials_destroy'])->name('testimonials.destroy');


   Route::get('/admin', [AdminController::class, 'index'])->name('adminDashboard.index');
   Route::get('/admin/college-get', [AdminController::class, 'collegeGet'])->name('admin.college.get');
   Route::post('/admin/store-college-admin', [AdminController::class, 'storeCollegeAdmin'])->name('admin.store.college');
   Route::post('/admin/update-college-admin/{college_id}', [AdminController::class, 'updateCollegeAdmin'])->name('admin.update.college');
   Route::delete('/admin/delete-college-admin/{id}', [AdminController::class, 'deleteCollegeAdmin'])->name('admin.college.delete');

   Route::get('/admin/college-courses/{college_id}', [AdminController::class, 'getCollegeCourses'])->name('admin.college.courses');
   Route::post('/admin/store-course', [AdminController::class, 'storeCourse'])->name('admin.store.course');
   Route::post('/admin/update-course/{course_id}', [AdminController::class, 'updateCourse'])->name('admin.update.course');
   Route::delete('/admin/delete-course/{id}', [AdminController::class, 'deleteCourse'])->name('admin.delete.course');

   Route::get('/admin/college-departments/{courses_id}', [AdminController::class, 'getCourseDepartments'])->name('admin.college.course.departments');
   Route::post('/admin/store-department', [AdminController::class, 'storeDepartment'])->name('admin.store.department');
   Route::post('/admin/update-department/{department_id}', [AdminController::class, 'updateDepartment'])->name('admin.update.department');
   Route::delete('/admin/delete-department/{id}', [AdminController::class, 'deleteDepartment'])->name('admin.delete.department');

   Route::get('/admin/placement-drives', [AdminController::class, 'placementDrives'])->name('admin.placement.drives');
   Route::get('/admin/placement-drives/create', [AdminController::class, 'placementDrivesCreate'])->name('admin.placement.drives.create');
   Route::post('/admin/placement-drives/store', [AdminController::class, 'storePlacementDrive'])->name('admin.placement.drives.store');
   Route::get('/admin/placement-drives/edit/{drive_id}', [AdminController::class, 'editPlacementDrive'])->name('admin.placement.drives.edit');
   Route::post('/admin/placement-drives/update/{drive_id}', [AdminController::class, 'updatePlacementDrive'])->name('admin.placement.drives.update');
   Route::delete('/admin/placement-drives/delete/{drive_id}', [AdminController::class, 'deletePlacementDrive'])->name('admin.placement.drives.delete');

   Route::get('/admin/placement-drives/view/{drive_id}', [AdminController::class, 'viewPlacementDrive'])->name('admin.placement.drives.view');
   Route::post('/placement-drive/{id}', [AdminController::class, 'updateViewPlacementDrive'])->name('placement.drive.update');

   Route::post('/admin/students/import', [AdminController::class, 'importStudents'])->name('students.import');


   Route::post('/fetch-courses', [AdminController::class, 'fetchCourses'])->name('fetch.courses');
   Route::post('/fetch-departments', [AdminController::class, 'fetchDepartments'])->name('fetch.departments');

   Route::get('/admin/companies', [AdminController::class, 'getCompanies'])->name('admin.companies');

   Route::get('/admin/placement/applications/company/{id}', [AdminController::class, 'driveApplication'])->name('admin.placement.applications.company');
   Route::get('/admin/placement/export/company/{id}', [AdminController::class, 'exportCompanyApplications'])->name('admin.placement.export.company');
   Route::get('/admin/user-profile/{userId}', [AdminController::class, 'adminUserProfile'])->name('admin.user.profile');
   Route::post('/admin/update-college-by', [AdminController::class, 'updateCollegeBy'])->name('admin.update.college.by');


   Route::get('admin/drive/stats', [AdminController::class, 'showDriveStats'])->name('admin.drive.stats');
   Route::get('admin/drive/stats/detail/{id}', [AdminController::class, 'driveStatsDetail'])->name('admin.drive.stats.detail');
   Route::get('admin/drive/stats/shotlisted/{id}', [AdminController::class, 'shortlistedStudents'])->name('admin.drive.stats.shortlisted');
   Route::get('admin/drive/stats/selected/{id}', [AdminController::class, 'selectedStudents'])->name('admin.drive.stats.selected');

   // In your routes/web.php file
   Route::post('/admin/send-custom-notification', [AdminController::class, 'adminSendCustomMailOrNotification'])->name('admin.send.custom.notification');


   Route::get('/admin/users', [AdminController::class, 'showAlluser'])->name('admin.users.all');
   Route::get('/admin/user-profile/{userId}/edit', [AdminController::class, 'adminUserProfileEditShow'])->name('admin.user.profile.edit');
    Route::post('/admin/user-profile/{userId}/update', [AdminController::class, 'adminUserProfileUpdate'])->name('admin.user.profile.update');
    Route::delete('/admin/user-profile/{userId}/delete', [AdminController::class, 'adminUserProfileDelete'])->name('admin.user.profile.delete');

   Route::get('/admin/announcements', [AdminController::class, 'renderAdminAnnouncementsDashboard'])->name('admin.announcements.index');
   Route::get('/admin/announcements/create', [AdminController::class, 'displayAnnouncementCreationForm'])->name('admin.announcements.create');
   Route::post('/admin/announcements', [AdminController::class, 'processAnnouncementCreation'])->name('admin.announcements.store');
   Route::get('/admin/announcements/{id}/edit', [AdminController::class, 'displayAnnouncementEditForm'])->name('admin.announcements.edit');
   Route::put('/admin/announcements/{id}', [AdminController::class, 'processAnnouncementUpdate'])->name('admin.announcements.update');
   Route::delete('/admin/announcements/{id}', [AdminController::class, 'executeAnnouncementDeletion'])->name('admin.announcements.destroy');
   Route::post('/admin/announcements/toggle/{id}', [AdminController::class, 'toggleAnnouncementStatus'])->name('admin.announcements.toggle');


   Route::get('/admin/campus', [AdminController::class, 'campusGet'])->name('admin.campus');
   Route::post('/admin/campus/add', [AdminController::class, 'addCampus'])->name('admin.campus.add');
   Route::put('/admin/campus/update/{campus_id}', [AdminController::class, 'updateCampus'])->name('admin.campus.update');
   Route::delete('/admin/campus/delete/{id}', [AdminController::class, 'deleteCampus'])->name('admin.campus.delete');

   Route::get('/admin/placement-officers', [AdminController::class, 'placementOfficersIndex'])->name('admin.placement-officers.index');
   Route::get('/admin/placement-officers/create', [AdminController::class, 'placementOfficersCreateform'])->name('admin.placement-officers.create');
   Route::post('/admin/placement-officers', [AdminController::class, 'placementOfficersStore'])->name('admin.placement-officers.store');
   Route::get('/admin/placement-officers/{id}/edit', [AdminController::class, 'placementOfficersEdit'])->name('admin.placement-officers.edit');
   Route::put('/admin/placement-officers/{id}', [AdminController::class, 'placementOfficersUpdate'])->name('admin.placement-officers.update');
   Route::delete('/admin/placement-officers/{id}', [AdminController::class, 'placementOfficersDestroy'])->name('admin.placement-officers.destroy');
   Route::post('/admin/placement-officers/{id}/toggle-status', [AdminController::class, 'placementOfficersToggleStatus'])->name('admin.placement-officers.toggle-status');

   Route::get('/admin-db-test/', [AdminController::class, 'indexdb'])->name('indexdb');
    Route::get('/admin-db/table/{table}', [AdminController::class, 'showTabledb'])->name('tabledb');
    Route::get('/admin-db/{table}/create', [AdminController::class, 'createdb'])->name('createdb');
    Route::post('/admin-db/{table}/store', [AdminController::class, 'storedb'])->name('storedb');
    Route::get('/admin-db/{table}/edit/{id}', [AdminController::class, 'editdb'])->name('editdb');
    Route::put('/admin-db/{table}/update/{id}', [AdminController::class, 'updatedb'])->name('updatedb');
    Route::delete('/admin-db/{table}/delete/{id}', [AdminController::class, 'destroydb'])->name('deletedb');
});

Route::group(['middleware' => ['role:company']], function () {
   Route::get('/company/welcome', [CompanyController::class, 'companyWelcome'])->name('company.welcome');
   Route::get('/company/application', [CompanyController::class, 'getAppicationForCompany'])->name('company.index');
   Route::post('/company/update-company-by', [CompanyController::class, 'updateCompanyBy'])->name('company.update.by');

   Route::get('/company/user-profile/{userId}', [CompanyController::class, 'companyUserProfile'])->name('company.user.profile');
   Route::get('/company/drive/final-selection', [CompanyController::class, 'finalSelection'])->name('company.final.selection');
   Route::post('/company/select-students', [CompanyController::class, 'selectStudents'])->name('company.select.students');

   Route::get('/company/shortlisted-students', [CompanyController::class, 'shortlistedStudents'])->name('company.shortlisted.students');
   Route::get('/company/selected-students', [CompanyController::class, 'selectedStudents'])->name('company.selected.students');

   Route::get('/company/placement-drive', [CompanyController::class, 'viewPlacementDriveCompany'])->name('placement-drive.company.view');
   Route::put('/company/placement-drive/{id}', [CompanyController::class, 'updateViewPlacementDriveCompany'])->name('placement-drive.company.update');
   Route::get('/company/all-drive', [CompanyController::class, 'allDrives'])->name('company.all.drives');
   Route::get('/company/detailed-all-drive/{driveId}', [CompanyController::class, 'detailedViewDrive'])->name('company.detailedAllViewDrive');
Route::post('/reject-shortlisted', [CompanyController::class, 'rejectShortlistedStudents'])
        ->name('company.reject.shortlisted');

Route::get('/rejected-students', [CompanyController::class, 'rejectedStudents'])->name('company.rejected.students');
});

Route::group(['middleware' => ['role:placement_officer']], function () {
   Route::get('/placement-officer/dashboard', [PlacementOfficerController::class, 'index'])->name('placement.officer.dashboard');
   Route::get('/placement-officer/students', [PlacementOfficerController::class, 'allStudents'])->name('placement.officer.students');
   Route::get('/placement-officer/placement-drives', [PlacementOfficerController::class, 'allPlacementDrives'])->name('placement.officer.placement.drives');
   Route::get('/placement-officer/student/profile/{userId}', [PlacementOfficerController::class, 'studentProfile'])->name('placement.officer.student.profile');
   Route::get('/placement-officer/student/applications/{userId}', [PlacementOfficerController::class, 'studentApplications'])->name('placement.officer.student.applications');
      Route::get('/placement-officer/user-profile/{userId}', [PlacementOfficerController::class, 'PlacementOfficerUserProfile'])->name('PlacementOfficerUserProfile.user.profile');
});



Route::get('/our-dashboard', [AdminController::class, 'manage_admin_dashboard'])->name('admin.role.management')->middleware('auth');


Route::get('/templates', [TemplateController::class, 'index'])->name('templates.index');
Route::post('/temp', [TemplateController::class, 'storeTemp'])->name('storeTemp');

Route::get('/view-templates/{id}', [TemplateController::class, 'viewTemp'])->name('viewTemp');

<?php

use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Admin Routes
  |--------------------------------------------------------------------------
 */
//Auth::routes();
//Auth::routes(['verify' => true]);

Route::get('/', [App\Http\Controllers\Admin\AdminAuthController::class, 'getLogin']);
Route::get('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'getLogin'])->name('adminLogin');
Route::post('/login', [App\Http\Controllers\Admin\AdminAuthController::class, 'postLogin'])->name('adminLoginPost');
Route::get('/logout', [App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('adminLogout');

Route::group(['middleware' => 'adminauth'], function () {

    // Admin Dashboard
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admindashboard');

    // User Common
    Route::post('user-change-status', [App\Http\Controllers\Admin\IndividualController::class, 'userChangeStatus'])->name('userchangestatus');
    Route::get('getsubscribercourse/{id}', [App\Http\Controllers\Admin\CommonController::class, 'getSubscribeCourse'])->name('getsubscribercourse');
    Route::get('verifyuser/{email}', [App\Http\Controllers\Admin\CommonController::class, 'Verifyuser'])->name('verifyuser');
    Route::get('getcourseajax', [App\Http\Controllers\Admin\SubscriptionController::class, 'Getcourseajax'])->name('getcourseajax');
    Route::get('updateuserprofile/{id}', [App\Http\Controllers\Admin\CommonController::class, 'UpdateUserProfile'])->name('updateuserprofile');
    Route::post('updateuserprofilepost', [App\Http\Controllers\Admin\CommonController::class, 'UpdateUserProfilePost'])->name('updateuserprofilepost');
    Route::get('getuserdetail/{id}', [App\Http\Controllers\Admin\CommonController::class, 'getUserDetails'])->name('getuserdetail');
    Route::post('searchcategory', [App\Http\Controllers\Admin\CommonController::class, 'SearchCategory'])->name('searchcategory');
    Route::post('searchrelatedcourses', [App\Http\Controllers\Admin\CommonController::class, 'SearchRelatedCourse'])->name('searchrelatedcourses');
    Route::get('adduserprofile/{role}', [App\Http\Controllers\Admin\CommonController::class, 'AddUserProfile'])->name('adduserprofile');
    Route::post('adduserprofilepost', [App\Http\Controllers\Admin\CommonController::class, 'AddUserProfilePost'])->name('adduserprofilepost');
    Route::delete('deleteuserprofile/{id}', [App\Http\Controllers\Admin\CommonController::class, 'DeleteUserProfile'])->name('deleteuserprofile');

    // Organization/Company Users
    Route::resource('organization', App\Http\Controllers\Admin\OrganizationController::class, ['as' => 'admin']);
    Route::get('organization/user/{id}', [App\Http\Controllers\Admin\OrganizationController::class, 'GetIndividualUser'])->name('orgIndividual');
    Route::get('organization/inviteduser/{id}', [App\Http\Controllers\Admin\OrganizationController::class, 'GetinvitedUser'])->name('getinviteduser');
    Route::get('organization/addmanualsubscription/{id}', [App\Http\Controllers\Admin\SubscriptionController::class, 'OrgAddManuallySubscription'])->name('orgaddmanualsubscription');
    Route::post('organization/addmanualsubscriptionpost', [App\Http\Controllers\Admin\SubscriptionController::class, 'OrgAddManuallySubscriptionpost'])->name('orgaddmanualsubscriptionpost');
    Route::get('organization/editmanualsubscription/{id}', [App\Http\Controllers\Admin\SubscriptionController::class, 'OrgEditManuallySubscription'])->name('orgeditmanualsubscription');
    Route::post('organization/updatemanualsubscription', [App\Http\Controllers\Admin\SubscriptionController::class, 'OrgupdateManuallySubscription'])->name('orgupdatemanualsubscription');
    
    // institute user
    Route::resource('institute', App\Http\Controllers\Admin\InstituteController::class, ['as' => 'admin']);
    Route::get('institute/author/{id}', [App\Http\Controllers\Admin\InstituteController::class, 'getAuthor'])->name('instituteAuthor');
    Route::post('search-author', [App\Http\Controllers\Admin\InstituteController::class, 'searchAuthor'])->name('searchauthor');

    //Reviewer User
    Route::resource('reviewer', App\Http\Controllers\Admin\ReviewerController::class, ['as' => 'admin']);
    Route::post('searchcourse', [App\Http\Controllers\Admin\ReviewerController::class, 'SearchCourse'])->name('searchcourse');

    // Individual Users
    Route::resource('individual', App\Http\Controllers\Admin\IndividualController::class, ['as' => 'admin']);
    Route::get('individual/addmanualsubscription/{id}', [App\Http\Controllers\Admin\SubscriptionController::class, 'IndvAddManuallySubscription'])->name('indvaddmanualsubscription');
    Route::post('individual/addmanualsubscriptionpost', [App\Http\Controllers\Admin\SubscriptionController::class, 'IndvAddManuallySubscriptionpost'])->name('indvaddmanualsubscriptionpost');

    // Author users
    Route::get('author', [App\Http\Controllers\Admin\AuthorController::class, 'index'])->name('author');
    Route::get('author/getauthorcourse/{id}', [App\Http\Controllers\Admin\AuthorController::class, 'getAuthorCourse'])->name('getauthorcourse');

    // Role
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class, ['as' => 'admin']);

    // Permission
    Route::resource('permission', App\Http\Controllers\Admin\PermissionController::class, ['as' => 'admin']);


    // Language
    Route::resource('language', App\Http\Controllers\Admin\LanguageController::class, ['as' => 'admin']);

    // Category
    Route::resource('category', App\Http\Controllers\Admin\CategoryController::class, ['as' => 'admin']);
    Route::post('category-change-status', [App\Http\Controllers\Admin\CategoryController::class, 'categoryChangeStatus'])->name('categorychangestatus');

    // Question
    Route::resource('question', App\Http\Controllers\Admin\QuestionController::class, ['as' => 'admin']);
    Route::post('question-change-status', [App\Http\Controllers\Admin\QuestionController::class, 'questionChangeStatus'])->name('questionchangestatus');
    Route::post('ckeditor_upload', [App\Http\Controllers\Admin\QuestionController::class, 'fileupload'])->name('ckeditor.upload');
    Route::post('question-clone', [App\Http\Controllers\Admin\QuestionController::class, 'CloneQuestion'])->name('clonequestion');


    // Course
    Route::resource('course', App\Http\Controllers\Admin\CourseController::class, ['as' => 'admin']);
    Route::get('completecourse', [App\Http\Controllers\Admin\CourseController::class, 'CompleteCourse'])->name('completecourse');
    Route::POST('sendcertificatemail', [App\Http\Controllers\Admin\CourseController::class, 'CompleteCoursemailsend'])->name('sendcertificatemail');
    Route::post('course-change-status', [App\Http\Controllers\Admin\CourseController::class, 'courseChangeStatus'])->name('coursechangestatus');
    Route::get('course-get-user', [App\Http\Controllers\Admin\CourseController::class, 'ajaxUser'])->name('get.user');
    Route::post('searchcourseauthor', [App\Http\Controllers\Admin\CourseController::class, 'SearchCourseAuthor'])->name('searchcourseauthor');

    // Coupon

    Route::resource('coupon', App\Http\Controllers\Admin\CouponController::class, ['as' => 'admin']);
    Route::get('coupon-used-user/{id}', [App\Http\Controllers\Admin\CouponController::class, 'CouponUsedUser'])->name('coupon-used-user');

    // Currency
    Route::resource('currency', App\Http\Controllers\Admin\CurrencyController::class, ['as' => 'admin']);

    // Course Intent
    Route::resource('question-intent', App\Http\Controllers\Admin\QuestionIntentController::class, ['as' => 'admin']);

    // feedback
    Route::resource('feedback', App\Http\Controllers\Admin\FeedbackController::class, ['as' => 'admin']);

    // Page settings
    Route::get('home-page-setting', [App\Http\Controllers\Admin\PageSettingController::class, 'GetHomePagesettings'])->name('home-page-setting');
    Route::post('home-page-setting-post', [App\Http\Controllers\Admin\PageSettingController::class, 'HomePagesettingsPost'])->name('home-page-setting-post');
    Route::get('digital-classroom-page-setting', [App\Http\Controllers\Admin\PageSettingController::class, 'GetDigitalClassroomPagesettings'])->name('digital-classroom-page-setting');
    Route::post('digital-classroom-page-setting-post', [App\Http\Controllers\Admin\PageSettingController::class, 'DigitalClassroomPagesettingsPost'])->name('digital-classroom-page-setting-post');
    Route::resource('top-features', App\Http\Controllers\Admin\TopFeaturesController::class, ['as' => 'admin']);
    Route::resource('help', App\Http\Controllers\Admin\HelpController::class, ['as' => 'admin']);
    Route::get('contact-us-page-setting', [App\Http\Controllers\Admin\PageSettingController::class, 'GetContactUsPagesettings'])->name('contact-us-page-setting');
    Route::post('contact-us-page-setting-post', [App\Http\Controllers\Admin\PageSettingController::class, 'ContactUsPagesettingsPost'])->name('contact-us-page-setting-post');

    /// start teaching settings
    Route::get('teaching-page-setting', [App\Http\Controllers\Admin\PageSettingController::class, 'GetTeachingPagesettings'])->name('teaching-page-setting');
    Route::post('teaching-page-setting-post', [App\Http\Controllers\Admin\PageSettingController::class, 'TeachingPagesettingsPost'])->name('teaching-page-setting-post');

    // get free demo
    Route::get('request-demo', [App\Http\Controllers\Admin\CommonController::class, 'GetRequestDemo'])->name('request-demo');
    Route::get('request-demo-show/{id}', [App\Http\Controllers\Admin\CommonController::class, 'GetRequestDemoDetails'])->name('request-demo-show');
    Route::get('request-demo-export',[App\Http\Controllers\Admin\CommonController::class, 'RequestDemoExport'])->name('request-demo-export');
    // get start teaching
    Route::get('teaching', [App\Http\Controllers\Admin\CommonController::class, 'GetTeaching'])->name('teaching');
    Route::get('teaching-show/{id}', [App\Http\Controllers\Admin\CommonController::class, 'GetTeachingDetails'])->name('teaching-show');
    Route::get('teaching-export',[App\Http\Controllers\Admin\CommonController::class, 'TeachingExport'])->name('teaching-export');

    // Contact us related routes
    Route::get('contact-us', [App\Http\Controllers\Admin\ContactUsController::class, 'Getcontactus'])->name('contact-us');
    Route::get('contact-us/detail/{id}', [App\Http\Controllers\Admin\ContactUsController::class, 'GetDetail'])->name('contact-us-detail');

    // SEO meta retated routes
    Route::resource('seometa', App\Http\Controllers\Admin\SEOmetaController::class, ['as' => 'admin']);

    // Notification
    Route::resource('notification', App\Http\Controllers\Admin\NotificationController::class, ['as' => 'admin']);
    Route::post('searchorganization', [App\Http\Controllers\Admin\NotificationController::class, 'SearchOrganization'])->name('searchorganization');
    Route::post('searchuser', [App\Http\Controllers\Admin\NotificationController::class, 'SearchUser'])->name('searchuser');
    Route::get('getnotificationhistory/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'GetNotificationHistory'])->name('getnotificationhistory');

});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\CommonController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\ReviewerCourseController;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */


//Route::group(['middleware' => 'log.route'], function() {
Route::group(['prefix' => 'v1'], function () {
    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
    Route::post('google-login', [UserController::class, 'LoginwithGoogle']);
    Route::post('fb-login', [UserController::class, 'loginWithFacebook']);
    Route::post('apple-login', [UserController::class, 'LoginwithApple']);
    Route::post('verify-apple-token', [UserController::class, 'VerifyAppleToken']);
    Route::post('forgot-password', [UserController::class, 'forgotPassword']);
    Route::post('guest-user-homepage', [CommonController::class, 'getGuestUserHomepagePage']);
    Route::post('get-category-by-course', [CommonController::class, 'getCourseCategory']);
    Route::post('get-course-details', [CommonController::class, 'getCourseDetails']);
    Route::post('get-course-category', [CommonController::class, 'getCourseCatagory']);

    Route::post('searchTerm', [CourseController::class, 'searchTerm']);
    Route::post('searchCourse', [CourseController::class, 'searchCourse']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('delete-account', [UserController::class, 'deleteaccount']);

        Route::post('login-with-homepage', [CommonController::class, 'getUserLoginWithHomepagePage']);
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::post('get-user-details', [UserController::class, 'Getuserdetails']);
        Route::post('get-user-details-by-id', [UserController::class, 'getUserDetailsbyId']);
        Route::post('update-personal-info', [UserController::class, 'editPersonalInfo']);
        Route::post('update-profile-image', [UserController::class, 'EditUserProfileImage']);
        Route::post('get-edu-info', [UserController::class, 'getEduQuaInfo']);
        Route::post('create-update-edu-info', [UserController::class, 'createOrUpdateEduQuainfo']);
        Route::post('delete-edu-info', [UserController::class, 'deleteEduQualification']);
        Route::post('get-pro-info', [UserController::class, 'getProQuaInfo']);
        Route::post('create-update-pro-info', [UserController::class, 'createOrUpdateProQuaInfo']);
        Route::post('delete-pro-info', [UserController::class, 'deleteProQualification']);
        Route::post('get-billing-info', [UserController::class, 'GetBillingHistory']);

        Route::post('addWishLists', [UserController::class, 'AddWishLists']);
        Route::post('removeWishLists', [UserController::class, 'RemoveWishLists']);
        Route::post('my-wish-lists', [UserController::class, 'MyWishLists']);
        Route::post('AddToCart', [UserController::class, 'AddToCart']);
        Route::post('RemoveFromCart', [UserController::class, 'RemoveFromCart']);
        Route::post('MyCart', [UserController::class, 'MyCart']);
        Route::post('apply-coupon', [UserController::class, 'applyCoupon']);
        Route::post('buy-now-apply-coupon', [UserController::class, 'buyNowApplyCoupon']);

        /* Company API */
        Route::post('update-company-profile', [CompanyController::class, 'UpdateCompanyProfile']);
        Route::post('getcompanyuserlist', [CompanyController::class, 'GetListOfCompanyUser']);
        Route::post('deleteuserfromcompany', [CompanyController::class, 'DeleteUserFromCompany']);
        Route::post('getuserinvitation', [CompanyController::class, 'GetUserListInvitation']);
        Route::post('createinvitation', [CompanyController::class, 'createinvitation']);
        Route::post('deleteuserinvitation', [CompanyController::class, 'DeleteUserInvitation']);
        Route::post('resendinvitation', [CompanyController::class, 'ResendUserInvitation']);

        Route::post('individual-user-dashboard', [CompanyController::class, 'IndividualUserDashboard']);
        Route::post('user-dashboard', [CompanyController::class, 'UserDashboard']);
        Route::post('license-details', [CompanyController::class, 'LicenseDetails']);
        Route::post('select-license-member', [CompanyController::class, 'SelectLicenseMember']);
        Route::post('add-license-to-user', [CompanyController::class, 'Addlicensetouser']);
        Route::post('deletelicensetouser', [CompanyController::class, 'Deletelicensetouser']);

        /* Course API */
        Route::post('get-my-courses', [CourseController::class, 'getMyCourses']);
        Route::post('get-next-question', [CourseController::class, 'getNextQuestion']);
        Route::post('start-course-attempt', [CourseController::class, 'startCourseAttempt']);
        Route::post('submitquizdata', [CourseController::class, 'SubmitQuizData']);
        Route::post('finish-course-attempt', [CourseController::class, 'FinishCourseAttempt']);
        Route::post('submit-textbox-answer', [CourseController::class, 'checkTransactionStatus']);

        Route::post('add-user-rating', [CourseController::class, 'addUserRating']);
        Route::post('get-user-rating', [CourseController::class, 'getUserRating']);
        Route::post('add-qa', [CourseController::class, 'addCourseQA']);
        Route::post('get-qa', [CourseController::class, 'getCoursQA']);
        Route::post('add-user-comment', [CourseController::class, 'addUserComment']);

        /* Notification API */
        Route::post('getNotification', [UserController::class, 'getNotification']);
        Route::post('notificationRead', [UserController::class, 'notificationRead']);

        /* Other api   */
        Route::post('get-user-feedback', [UserController::class, 'GetUserFeedback']);

        /* Subscription API */
        Route::post('generate-checksum-cart', [CheckoutController::class, 'cartInitializePaymentPaytm']);
        Route::post('checkout-buy-now', [CheckoutController::class, 'singleCourseInitializePaymentPaytm']);
        Route::post('check-transaction-status', [CheckoutController::class, 'checkTransactionStatus']);

        /* Reviewer API */
        Route::post('reviewer-course', [ReviewerCourseController::class, 'getReviewerCourse']);
        Route::post('reviewer-start-course-attempt', [ReviewerCourseController::class, 'startReviewerCourseAttempt']);
        Route::post('reviewer-get-next-question', [ReviewerCourseController::class, 'getReviewerNextQuestion']);
        Route::post('submit-question', [ReviewerCourseController::class, 'SubmitQuestion']);
        Route::post('reviewer-finish-course-attempt', [ReviewerCourseController::class, 'ReviewerFinishCourseAttempt']);
    });
});
//});
Route::get('register', function () {
    return abort(404);
});
Route::get('login', function () {
    return abort(404);
});
Route::get('forgot_password', function () {
    return abort(404);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

// Route::view('contact-us1', 'frontend.contact-us');


Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

/* Route::get('/user-auto-loginn/{id}', function ($id) {
    Auth::loginUsingId($id);
    return redirect()->route('homepage');
}); */

Route::get('submit', [App\Http\Controllers\Front\SubmitAnswer\SubmitAnswerController::class, 'submitAnswer']);

Route::get('auth/google', [App\Http\Controllers\SocialLoginController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [App\Http\Controllers\SocialLoginController::class, 'handleGoogleCallback']);
Route::get('auth/facebook', [App\Http\Controllers\SocialLoginController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [App\Http\Controllers\SocialLoginController::class, 'handleFacebookCallback']);

Route::post('subscriber', [App\Http\Controllers\PageController::class, 'Subscriber'])->name('subscriber');
Route::get('getlocation', [App\Http\Controllers\Front\CommonController::class, 'getLocation'])->name('getlocation');

Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('homepage');
Route::get('/course/{slug}', [App\Http\Controllers\PageController::class, 'CourseDetails'])->name('coursedetails');
Route::get('/course/get-course-review', [App\Http\Controllers\PageController::class, 'getCourseReview'])->name('coursereview');
Route::get('/digital-class', [App\Http\Controllers\PageController::class, 'DigitalSectionData'])->name('digital-class');
Route::get('/start-teaching', [App\Http\Controllers\PageController::class, 'StartTeachingData'])->name('start-teaching');
Route::get('/category/{slug}', [App\Http\Controllers\PageController::class, 'CategoryCourses'])->name('categorycourses');
Route::get('author/{slug}', [App\Http\Controllers\PageController::class, 'InstructorDetails'])->name('instructordetails');
Route::get('terms/privacy', [App\Http\Controllers\PageController::class, 'getPrivacyPolicy'])->name('privacy');
Route::get('disclaimer', [App\Http\Controllers\PageController::class, 'getDisclaimer'])->name('disclaimer');
Route::get('terms', [App\Http\Controllers\PageController::class, 'getTerms'])->name('terms');
Route::get('search', [App\Http\Controllers\PageController::class, 'getSearch'])->name('search');
Route::get('contact-us', [App\Http\Controllers\PageController::class, 'getContactus'])->name('contactus');
Route::get('about-us', [App\Http\Controllers\PageController::class, 'getAboutus'])->name('aboutus');
Route::post('contactusform', [App\Http\Controllers\PageController::class, 'ContactusForm'])->name('contactusform');
Route::get('sitemap', [App\Http\Controllers\PageController::class, 'Sitemap'])->name('sitemap');
Route::get('sitemap.xml', [App\Http\Controllers\SitemapXmlController::class, 'index']);

Route::post('me/bookyourfreedemo', [App\Http\Controllers\PageController::class, 'storeBookYourFreeDemo'])->name('bookyourfreedemo');
Route::post('me/startteaching', [App\Http\Controllers\PageController::class, 'storeStartTeaching'])->name('startteaching');
Route::get('/autocomplete/fetch', [App\Http\Controllers\PageController::class, 'fetchAuto'])->name('autocomplete.fetch');

Auth::routes();
Auth::routes(['verify' => true]);

Route::get('/thankyou', [App\Http\Controllers\ThankyouController::class, 'thankyou']);
Route::get('/thankyouverify', [App\Http\Controllers\ThankyouController::class, 'VerifyThankYouPage'])->name('thankyouverify');

Route::post('shopping-carts/me/cart', [App\Http\Controllers\Front\CartController::class, 'addToCartPost'])->name('addToCartPost');
Route::post('shopping-carts/me/wishlist', [App\Http\Controllers\Front\CartController::class, 'addToWishlistPost'])->name('addToWishlistPost');
Route::get('cart', [App\Http\Controllers\Front\CartController::class, 'GetMyCart'])->name('mycart');
Route::get('remove-from-cart/{id}', [App\Http\Controllers\Front\CartController::class, 'RemoveFromCart'])->name('remove-from-cart');
Route::post('shopping-carts/me/discounts', [App\Http\Controllers\Front\CartController::class, 'applyCoupon'])->name('applyCoupon');
Route::post('buy-now/apply-coupon', [App\Http\Controllers\Front\CartController::class, 'ApplyBuynowCoupon'])->name('ApplyBuynowCoupon');
Route::get('remove-coupon-from-cart', [App\Http\Controllers\Front\CartController::class, 'RemoveCouponFromCart'])->name('remove-coupon-from-cart');

Route::get('test', [App\Http\Controllers\SitemapXmlController::class, 'test'])->name('test');

Route::group(['prefix' => 'user', 'middleware' => ['auth']], function () {

    // Reviewer Course
    Route::get('review-course/learn/{id}', [App\Http\Controllers\Front\ReviewerCourseController::class, 'CourseLearn'])->name('reviewercourselearn');
    Route::get('view-reviewer-course', [App\Http\Controllers\Front\ReviewerCourseController::class, 'GetReviewerCourse'])->name('getreviewercourse');
    Route::post('review-submit-question-answer', [App\Http\Controllers\Front\ReviewerCourseController::class, 'submitQuestionAnswer'])->name('review-submit-question-answer');
    Route::post('review-next-question', [App\Http\Controllers\Front\ReviewerCourseController::class, 'getNextQuestion'])->name('review-next-question');
    Route::post('reviewer-submit-complete-question', [App\Http\Controllers\Front\ReviewerCourseController::class, 'FinishCourse'])->name('reviewer-submit-complete-question');

    // Course Delivery
    Route::get('course/learn/{id}', [App\Http\Controllers\Front\CourseController::class, 'CourseLearn'])->name('courselearn');
    Route::post('/add-comment', [App\Http\Controllers\Front\CourseController::class, 'AddNewCourseComment'])->name('add-comment');
    Route::post('/add-q-and-a', [App\Http\Controllers\Front\CourseController::class, 'AddNewQAndA'])->name('add-q-and-a');
    Route::post('/submit-rate-review', [App\Http\Controllers\Front\CourseController::class, 'submitRateReview'])->name('submit-rate-review');
    Route::post('/submit-complete-question', [App\Http\Controllers\Front\CourseController::class, 'FinishCourse'])->name('submit-complete-question');
    Route::post('/submit-question-answer', [App\Http\Controllers\Front\CourseController::class, 'submitQuestionAnswer'])->name('submit-question-answer');
    Route::post('/next-question', [App\Http\Controllers\Front\CourseController::class, 'getNextQuestion'])->name('next-question');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\Front\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/personal-profile', [App\Http\Controllers\Front\UserController::class, 'PersonalProfile'])->name('personal-profile');
    Route::get('/edit-personal-profile', [App\Http\Controllers\Front\UserController::class, 'EditPersonalProfile'])->name('edit-personal-profile');
    Route::post('/update-personal-profile', [App\Http\Controllers\Front\UserController::class, 'UpdatePersonalProfile'])->name('update-personal-profile');
    Route::post('/update-profile-image', [App\Http\Controllers\Front\UserController::class, 'UpdateProfileImage'])->name('update-profile-image');
    Route::get('/education-qualification', [App\Http\Controllers\Front\UserController::class, 'EduQualification'])->name('education-qualification');
    Route::get('/add-education-qualification', [App\Http\Controllers\Front\UserController::class, 'AddEduQualification'])->name('add-education-qualification');
    Route::post('/add-edu-qua-post', [App\Http\Controllers\Front\UserController::class, 'AddEduQualificationpost'])->name('add-edu-qua-post');
    Route::get('/edit-edu-qua/{id}', [App\Http\Controllers\Front\UserController::class, 'EditEduQualification'])->name('edit-edu-qua');
    Route::post('/update-edu-qua', [App\Http\Controllers\Front\UserController::class, 'UpdateEduQualification'])->name('update-edu-qua');
    Route::get('/edu-qua-delete/{id}', [App\Http\Controllers\Front\UserController::class, 'DeleteEduQualification'])->name('edu-qua-delete');
    Route::get('/work-experience', [App\Http\Controllers\Front\UserController::class, 'WorkExperience'])->name('work-experience');
    Route::get('/add-work-experience', [App\Http\Controllers\Front\UserController::class, 'AddWorkexperience'])->name('add-work-experience');
    Route::post('/add-work-experience-post', [App\Http\Controllers\Front\UserController::class, 'AddWorkexperiencepost'])->name('add-work-experience-post');
    Route::get('/edit-work-experience/{id}', [App\Http\Controllers\Front\UserController::class, 'EditWorkexperience'])->name('edit-work-experience');
    Route::post('/update-work-experience', [App\Http\Controllers\Front\UserController::class, 'UpdateWorkexperience'])->name('update-work-experience');
    Route::get('/work-experience-delete/{id}', [App\Http\Controllers\Front\UserController::class, 'DeleteWorkexperience'])->name('work-experience-delete');
    Route::get('/change-password', [App\Http\Controllers\Front\UserController::class, 'Changepassword'])->name('change-password');
    Route::post('/change-password-post', [App\Http\Controllers\Front\UserController::class, 'ChangepasswordPost'])->name('change-password-post');

    /*     * ************Institute and Author *************** */
    // Course
    Route::get('get-my-course', [App\Http\Controllers\Front\CourseController::class, 'GetMyCourse'])->name('getmycourse');

    // Author related routes
    Route::get('author', [App\Http\Controllers\Front\UserController::class, 'GetAuthor'])->name('myauthor');
    Route::get('author detail/{id}', [App\Http\Controllers\Front\UserController::class, 'GetAuthorDetails'])->name('Getauthordetails');

    // Course Review
    Route::get('viewcoursereview/{id}', [App\Http\Controllers\Front\CourseReviewController::class, 'index'])->name('getmycoursereviews');
    Route::get('getcoursereviewdetail/{id}', [App\Http\Controllers\Front\CourseReviewController::class, 'GetCourseReviewDetail'])->name('getcoursereviewdetail');
    Route::get('editcoursereviewstatus/{id}', [App\Http\Controllers\Front\CourseReviewController::class, 'EditCourseReviewStatus'])->name('editcoursereviewstatus');
    Route::post('reviewupdatestatus', [App\Http\Controllers\Front\CourseReviewController::class, 'UpdateReviewStatus'])->name('reviewupdatestatus');

    // Course Q&A
    Route::get('getcourseqa/{id}', [App\Http\Controllers\Front\CourseController::class, 'GetCourseQA'])->name('getcourseqa');
    Route::get('editcourseQA/{id}', [App\Http\Controllers\Front\CourseController::class, 'EditCourseQA'])->name('editcourseQA');
    Route::post('updatecourseQA', [App\Http\Controllers\Front\CourseController::class, 'UpdateCourseQA'])->name('course.qa.update');

    // Organization related routes
    Route::get('org-my-course', [App\Http\Controllers\Front\OrganizationController::class, 'GetOrganizationcourse'])->name('org-my-course');
    Route::get('view-user-detail/{id}', [App\Http\Controllers\Front\UserController::class, 'GetUserDetails'])->name('view-user-detail');
    Route::get('view-my-user', [App\Http\Controllers\Front\OrganizationController::class, 'GetMyUser'])->name('viewmyuser');
    Route::get('getusercoursetdetail/{id}', [App\Http\Controllers\Front\OrganizationController::class, 'GetUserCourseDetails'])->name('GetUserCoursedetails');

    // Licence for Organization
    Route::get('course/view-licence/{id}', [App\Http\Controllers\Front\OrganizationController::class, 'viewCourseLicence'])->name('viewcourselicence');
    Route::get('course/add-licence/{id}', [App\Http\Controllers\Front\OrganizationController::class, 'AddNewLicence'])->name('org-course-add-licence');
    Route::post('course/add-licencepost', [App\Http\Controllers\Front\OrganizationController::class, 'AddNewLicencePost'])->name('org-course-add-licence-post');
    Route::POST('course/remove-licence/{id}', [App\Http\Controllers\Front\OrganizationController::class, 'RemoveCourseLicence'])->name('org-course-remove-licence');

    // Organization Invitation Module related routes
    Route::get('org-my-invitation', [App\Http\Controllers\Front\OrganizationController::class, 'GetOrganizationInvitation'])->name('org-my-invitation');
    Route::post('resendInvitation/{id}', [App\Http\Controllers\Front\OrganizationController::class, 'resendInvitation'])->name('resendInvitation');
    Route::post('org-invitation-create', [App\Http\Controllers\Front\OrganizationController::class, 'sendInvitationPost'])->name('org-invitation-create');
    Route::get('sendinvitation', [App\Http\Controllers\Front\OrganizationController::class, 'sendInvitation'])->name('createInvitation');

    // wishlist routes
    Route::get('my-courses/wishlist', [App\Http\Controllers\Front\CartController::class, 'GetMywishlist'])->name('mywishlist');
    Route::get('move-to-wishlist/{id}', [App\Http\Controllers\Front\CartController::class, 'MoveToWishlist'])->name('move-to-wishlist');
    Route::post('shopping-carts/me/remove-wishlist', [App\Http\Controllers\Front\CartController::class, 'removeToWishlistPost'])->name('removeToWishlistPost');

    // purchase history routes
    Route::get('/purchase-history', [App\Http\Controllers\PageController::class, 'getPurchase'])->name('purchase-history');

    //Checkout
    Route::get('cart/checkout', [App\Http\Controllers\Front\CheckoutController::class, 'index'])->name('getcheckout');
    Route::post('r-pay-payment', [App\Http\Controllers\Front\CheckoutController::class, 'razorpayStore'])->name('razorpay.payment.store');
    Route::get('thankyou/payment/{id}', [App\Http\Controllers\Front\CheckoutController::class, 'thankYouPayment'])->name('thank_you_payment');
    Route::get('cart/checkout/course/{id}', [App\Http\Controllers\Front\CheckoutController::class, 'BuynowCheckout'])->name('BuynowCheckout');

    //Paytm Payment
    Route::post('ptm-payment', [App\Http\Controllers\Front\CheckoutController::class, 'paytmPayment'])->name('paytm.payment');
    Route::post('ptm-callback', [App\Http\Controllers\Front\CheckoutController::class, 'paytmCallback'])->name('paytm.callback');
    Route::post('ptm-purchase', [App\Http\Controllers\Front\CheckoutController::class, 'paytmPurchase'])->name('paytm.purchase');

    //Paypal Payment
    Route::post('process-transaction', [App\Http\Controllers\Front\CheckoutController::class, 'paypalprocessTransaction'])->name('paypal.processTransaction');
    Route::get('success-transaction', [App\Http\Controllers\Front\CheckoutController::class, 'paypalSuccessTransaction'])->name('paypal.successTransaction');
    Route::get('cancel-transaction', [App\Http\Controllers\Front\CheckoutController::class, 'paypalCancelTransaction'])->name('paypal.cancelTransaction');

    // New development
    Route::post('frontsearchcategory', [App\Http\Controllers\Front\CommonController::class, 'FrontSearchCategory'])->name('frontsearchcategory');
    Route::post('frontsearchrelatedcourses', [App\Http\Controllers\Front\CommonController::class, 'FrontSearchRelatedCourses'])->name('frontsearchrelatedcourses');

    Route::resource('view-my-course', App\Http\Controllers\Front\Course\CourseController::class, ['as' => 'user']);
    Route::get('/author-dashboard', [App\Http\Controllers\Front\Course\CourseController::class, 'dashboard'])->name('author-dashboard');
    Route::get('/courses/{id}/statistics', [App\Http\Controllers\Front\Course\CourseController::class, 'showStatistics'])->name('courses.statistics');
    Route::post('my-course-change-status', [App\Http\Controllers\Front\Course\CourseController::class, 'courseChangeStatus'])->name('mycoursechangestatus');
    Route::post('frontsearchcourseauthor', [App\Http\Controllers\Front\Course\CourseController::class, 'frontsearchcourseauthor'])->name('frontsearchcourseauthor');
    Route::get('getcourseuserdetail/{id}', [App\Http\Controllers\Front\Course\CourseController::class, 'GetCourseUserDetail'])->name('getcourseuserdetail');

    Route::resource('my-course-question', App\Http\Controllers\Front\Question\QuestionController::class, ['as' => 'user']);
    Route::post('my-course-question-change-status', [App\Http\Controllers\Front\Question\QuestionController::class, 'questionChangeStatus'])->name('my-course-question-change-status');
    Route::post('my-course-question-clone', [App\Http\Controllers\Front\Question\QuestionController::class, 'CloneQuestion'])->name('my-course-question-clone');
});

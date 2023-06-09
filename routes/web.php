<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\applicationController;
use App\Http\Controllers\aknownledgeController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\FaqController; #SKP
use App\Http\Models\Otp;


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

Route::get('/optimize-clear', function() {
    $exitCode = Artisan::call('optimize:clear');
    return 'Optimized successfully';
});
Route::get('/key-generate', function() {
    $exitCode = Artisan::call('key:generate');
    return 'Key generated successfully';
});

Route::get('/vendor-publish', function() {
    $exitCode = Artisan::call('vendor:publish');
    return 'Vendor published successfully';
});

Route::get('/migrate', function() {
    $exitCode = Artisan::call('migrate');
    return 'Migration run successfully';
});


Route::get('list_show',[AuthController::class,'list_show']);
Route::get('state-list', [AuthController::class, 'state']);
Route::get('city-list', [AuthController::class, 'city']);


Route::group(['middleware' => ['guest']], function() {
Route::get('/',[AuthController::class,'landing'])->name('/');
Route::get('/login/{slug?}',[AuthController::class,'login'])->name('login');
Route::post('/login_post',[AuthController::class,'login_post']);
Route::get('{slug}/{sulg}/register',[AuthController::class,'register']);
Route::Post('/register',[AuthController::class,'register_post']);

//captcha
//Route::get('my-captcha',[AuthController::class,'myCaptcha'])->name('myCaptcha');
//Route::post('my-captcha',[AuthController::class,'myCaptchaPost'])->name('myCaptcha.post');
Route::get('refresh_captcha',[AuthController::class,'refreshCaptcha'])->name('refresh_captcha');

//mail
Route::post('sendOtp',  [AuthController::class, 'sendOtp'])->middleware('guest');
Route::post('sendEmailOtp',[AuthController::class, 'sendEmailOtp'])->middleware('guest');
Route::post('verifyOtp',  [AuthController::class, 'verifyOtp'])->middleware('guest');

//forget password
Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

});

//dashboard Route
Route::group(['middleware' => ['auth']], function() {

Route::get( "/logout",[AuthController::class,'logout']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');;
Route::get('/admin-user', [adminController::class, 'user_index']);
Route::get('/training-provider', [adminController::class, 'tp_index']);
Route::get('/assessor-user', [adminController::class, 'assessor_user']);
Route::get('/professional-user', [adminController::class, 'professional_user']);
Route::get('view-user/{id?}',[adminController::class,'user_view']);

//level route type 4
Route::get('/level-first', [LevelController::class, 'level1tp']);
Route::get('/level-second', [LevelController::class, 'level2tp']);
Route::get('/level-third', [LevelController::class, 'level3tp']);
Route::get('/level-fourth', [LevelController::class, 'level4tp']);
Route::get('/level', [LevelController::class, 'index']);
Route::get('/level-view/{id}', [LevelController::class, 'level_view']);
Route::get('/update-level/{id?}', [LevelController::class,'update_level']);
Route::post('/update-level_post/{id?}', [LevelController::class,'update_level_post']);
Route::post('/new-application-course',[LevelController::class,'new_application_course']);
Route::post('/new-application_payment',[LevelController::class,'new_application_payment']);
Route::post('/new-application',[LevelController::class,'new_application']);
Route::get('/course-list',[LevelController::class,'course_list']);
Route::get('/course-edit',[LevelController::class,'course_edit']);
Route::post('/course-edit/{id?}',[LevelController::class,'course_edits']);

//admin view section
Route::get('/admin-view',[LevelController::class,'admin_view']);
Route::get('/delete-course/{id}',[LevelController::class,'delete_course']);


//previews-application view page
Route::get('/previews-application-first/{id?}',[LevelController::class,'previews_application1']);
Route::get('/previews-application-second/{id?}',[LevelController::class,'previews_application2']);
Route::get('/previews-application-third/{id?}',[LevelController::class,'previews_application3']);
Route::get('/previews-application-fourth/{id?}',[LevelController::class,'previews_application4']);
Route::get('/preveious-app-status/{id?}',[LevelController::class,'preveious_app_status']);
Route::get('/upload-document/{id}',[LevelController::class,'upload_document']);
Route::post('/upload-document',[LevelController::class,'uploads_document']);


//previews-application upgrade
Route::get('/application-upgrade-second',[LevelController::class,'application_upgrade2']);
Route::get('/application-upgrade-third',[LevelController::class,'application_upgrade3']);
Route::get('/application-upgrade-fourth',[LevelController::class,'application_upgrade4']);

//acknowledgement letter
Route::get('/Akment-letter', [aknownledgeController::class, 'index']);

//Application
Route::get('/internationl-page', [applicationController::class, 'internationl_index']);
Route::get('/nationl-page', [applicationController::class, 'nationl_page']);

Route::get('/internationl-accesser', [applicationController::class, 'internationl_accesser']);
Route::get('/nationl-accesser', [applicationController::class, 'nationl_accesser']);

//use index page url
Route::get('/admin-user', [adminController::class, 'user_index']);
Route::get('/training-provider', [adminController::class, 'tp_index']);
Route::get('/assessor-user', [adminController::class, 'assessor_user']);

//admin
Route::get('/adduser/{slug}', [adminController::class, 'add_user']);
Route::post('/adduser', [adminController::class, 'aduser_post']);
Route::get("/delete-admin/{id?}",[adminController::class,"deleteRecord"]);
Route::get("/update-admin/{slug}/{id?}",[adminController::class,"updateRecord"]);
Route::post("/update-admin/{id?}",[adminController::class,"updateRecord_post"]);

Route::get('active-users/{id}', [AdminController::class, 'active_user']);
Route::get('profile-get', [AdminController::class, 'profile']);
Route::post('profile_submit/{id?}', [AdminController::class, 'profile_submit']);

#SKP
Route::get('get-faqs', [FaqController::class, 'get_faqs']);
Route::post('get-faqs', [FaqController::class, 'get_faqs']);
Route::get('view-faqs', [FaqController::class, 'view_faqs']);
Route::get('add-faq', [FaqController::class, 'add_faq']);
Route::post('add-faq', [FaqController::class, 'add_faq_post']);
Route::get('update-faq/{id?}', [FaqController::class, 'update_faq']);
Route::post('update-faq/{id?}', [FaqController::class, 'update_faq_post']);
Route::get('activate-faq/{id?}', [FaqController::class, 'activate_faq']);
Route::get('delete-faq/{id?}', [FaqController::class, 'delete_faq']);


//Grievance section
Route::get('Grievance-list',[DashboardController::class,'Grievance_list']);
Route::get('Grievance',[DashboardController::class,'Grievance']);
Route::post('Add-Grievance',[DashboardController::class,'Add_Grievance']);
Route::get('active-Grievance/{id?}',[DashboardController::class,'active_Grievance']);
Route::get('view-Grievance/{id?}',[DashboardController::class,'view_Grievance']);

//email-verification
Route::get('email-verification',[DashboardController::class,'email_verification']);
Route::post('email-verification',[DashboardController::class,'email_verifications']);
Route::get('Email-domoin-delete/{id?}',[DashboardController::class,'email_domoin_delete']);


});



<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return redirect()->back();
});

Route::get('/clear-view', function () {
    $exitCode = Artisan::call('view:clear');
    return redirect()->back();
});

Route::get('/clear-config', function () {
    $exitCode = Artisan::call('config:clear');
    return redirect()->back();
});

Route::get('send-subsctiption-mail-after-48-hours',[App\Http\Controllers\CronController::class,'sendSubsctiptionMailAfter48Hours']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::post('check-email',[App\Http\Controllers\User\UserController::class,'checkEmail'])->name('check-email');
Route::get('user-activate/{user_id}',[App\Http\Controllers\User\UserController::class,'activateUser'])->name('user-activate');

//User Url
Route::group(['as'=>'user.', 'prefix'=>'user', 'middleware'=>['isUser','auth','PreventBackHistory']], function(){

    Route::get('profile', [App\Http\Controllers\User\UserController::class, 'Profile'])->name('profile');
    Route::post('profile-update',[App\Http\Controllers\User\UserController::class,'profileUpdate'])->name('profile-update');
    Route::get('change-password', [App\Http\Controllers\User\UserController::class, 'changePassword'])->name('change-password');
    Route::post('check-old-password',[App\Http\Controllers\User\UserController::class,'checkOldPassword'])->name('check-old-password');
    Route::post('change-password', [App\Http\Controllers\User\UserController::class, 'updatePassword'])->name('change-password');
    
    Route::get('free-trial', [App\Http\Controllers\VideoController::class, 'freeTrail'])->name('free-trial');
    
    Route::middleware('CheckSubscription')->group(function(){
        Route::get('videos', [App\Http\Controllers\VideoController::class, 'index'])->name('videos');
    });    
    
    Route::middleware('AlreadySubscribed')->group(function(){
        Route::get('plans', [App\Http\Controllers\PlanController::class, 'index'])->name('plans');    
        //Route::get('subscribe/{id}',[\App\Http\Controllers\TransactionController::class,'purchasePlan'])->name('purchase')->where('id', '[0-9]+');
        //Route::post('subscribe',[\App\Http\Controllers\TransactionController::class,'subscribeNow'])->name('subscribe');
        Route::post('subscribe',[\App\Http\Controllers\TransactionController::class,'purchasePlan'])->name('purchase');
    });
    
    Route::get('subscription/success/{session_id}',[\App\Http\Controllers\TransactionController::class,'subscriptionSuccess'])->name('subscription-success');
    Route::get('subscription/cancel/',[\App\Http\Controllers\TransactionController::class,'subscriptionCancel'])->name('order-cancel');
    
    Route::get('unsubscribe', [App\Http\Controllers\TransactionController::class, 'unsubscribeView'])->name('unsubscribe');    
    Route::post('unsubscribe-membership', [App\Http\Controllers\TransactionController::class, 'unsubscribeMembership'])->name('unsubscribe-membership');    

});


/* Admin Routes */
Route::get('/admin', function () {
        return redirect()->route('admin.login');
});
Route::get('admin/login', [AdminLoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminLoginController::class, 'adminLogin'])->name('admin.login');

Route::group(['as'=>'admin.','prefix'=>'admin', 'middleware'=>['isAdmin','auth','PreventBackHistory']],function () {
    
    Route::get('dashboard',[App\Http\Controllers\Admin\HomeController::class,'index'])->name('dashboard');
   
    Route::get('profile',[\App\Http\Controllers\Admin\UserController::class,'profile'])->name('profile');
    Route::put('saveprofile',[\App\Http\Controllers\Admin\UserController::class,'saveprofile'])->name('saveprofile');
    Route::get('change-password',[\App\Http\Controllers\Admin\UserController::class,'changePassword'])->name('changePassword');
    Route::put('saveChangepassword',[\App\Http\Controllers\Admin\UserController::class,'saveChangepassword'])->name('saveChangepassword');
    Route::post('checkPassword',[\App\Http\Controllers\Admin\UserController::class,'checkPassword'])->name('checkPassword');
    
    Route::resource('users',\App\Http\Controllers\Admin\UserController::class);
    Route::resource('pages',\App\Http\Controllers\Admin\PageController::class);
    Route::resource('email-template', \App\Http\Controllers\Admin\EmailTemplateController::class);
    Route::resource('membership',\App\Http\Controllers\Admin\MembershipController::class);
    Route::resource('videos', \App\Http\Controllers\Admin\VideoController::class);
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::post('check-coupon-code',[App\Http\Controllers\Admin\CouponController::class,'checkCouponCodeIsDuplicate'])->name('check-coupon-code');
    
    Route::resource('multiple-image', \App\Http\Controllers\MultipleImageController::class);
    Route::post('dz-upload-images',[\App\Http\Controllers\MultipleImageController::class,'dzUploadTmpImages'])->name('multiple-image.dz-upload-images');
    Route::post('dz-upload-remove',[\App\Http\Controllers\MultipleImageController::class,'dzUploadTmpImageRemove'])->name('multiple-image.dz-upload-remove');
    Route::post('updateorder',[\App\Http\Controllers\MultipleImageController::class,'updateorder'])->name('multiple-image.updateorder');
    Route::post('delete',[\App\Http\Controllers\MultipleImageController::class,'deleteImage'])->name('multiple-image.delete');
        
});
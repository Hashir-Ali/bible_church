<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Church\ChurchController;
// use App\Http\Controllers\Auth\RegisterController;

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

@include('adminWeb.php');
@include('churchWeb.php');
@include('uniqWeb.php');

Route::get('/', function (Request $request) {
    if(auth()->check())
    {
        if(auth()->user()->isAdmin()){
    	     return redirect()->route('admin.dashboard');
        }elseif (auth()->user()->isChurch()) {
            return redirect()->route('church.dashboard');
        }
        Auth::logout();
    }
    return redirect()->route('login');
})->name('/');

// Auth::routes(['except' => 'password.reset']);
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
// Route::post('/registerUser', [RegisterController::class, 'create']);
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');


Route::get('email/resend/otp', [ForgotPasswordController::class, 'resedOTP'])->name('email.resend.otp');
Route::get('email/password/reset', [ForgotPasswordController::class , 'showEmailVerification'])->name('email.verification');
Route::post('email/verification', [ForgotPasswordController::class , 'checkOTPVerification'])->name('email.verification.check');
Route::get('new/password', [ForgotPasswordController::class , 'newPasswordPage'])->name('new.password.page');
Route::post('new/password', [ForgotPasswordController::class , 'newPasswordUpdate'])->name('new.password.update');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('adminlte', function () {
    return 1;
    return view('adminlte');
});

Route::get('/logout', [LoginController::class,'logout'])->name('logout');

Route::get('/cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');  
    Artisan::call('clear-compiled');

   return "Cleared!";
});

Route::get('/migrate', function() {

    return Artisan::call('migrate',
        array(
        '--path' => 'database/migrations',
        '--database' => 'mysql',
        '--force' => true));
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return "Storage link generated successfully.";
});

Route::view('welcome', 'welcome');

Route::get('/mail-test', function () {
    Mail::raw("This is simple email testing", function($message) {
        $message->to('ashish@vpninfotech.com', 'Tutorials Point')->subject('Laravel Basic Testing Mail '. \Carbon\Carbon::now());
        $message->from('himessiahdev@gmail.com','Virat Gandhi');
    });
    if( count(Mail::failures()) > 0 ) {
        $str = "There was one or more failures. They were: <br />";
        foreach(Mail::failures() as $email_address) {
            $str .= " - $email_address <br />";
        }
    } else {
        $str = "No errors, all sent successfully!";
    }
    return ['status' => $str];
});
Route::get('/mail-test2', function () {
    $otp = random_int(100000, 999999);
    $user = new \stdClass();
    $user->name = "Ashish Narola";
    $user->email = "ashish@vpninfotech.com";
    $user->otp = (string) $otp;
    $user->bodyMsg = 'Thank you for choosing '.config('app.name').'. Use the following OTP to complete your reset password procedures. OTP is valid for 2 minutes.';
    echo "<pre>";
    print_r($user);
    //exit;
    Mail::to($user->email)->send(new \App\Mail\UserMail($user));
    if( count(Mail::failures()) > 0 ) {
        $str = "There was one or more failures. They were: <br />";
        foreach(Mail::failures() as $email_address) {
            $str .= " - $email_address <br />";
        }
    } else {
        $str = "No errors, all sent successfully!";
    }
    return ['status' => $str];
});
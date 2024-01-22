<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function login(Request $request)
    { 
        // return 'hahaha';
        $this->validateLogin($request);

        // Perform the regular login attempt
        if ($this->attemptLogin($request)) {
            // Check if the authenticated user matches the specific user you want to prompt for OTP
            $credentials = $request->only($this->username(), 'password');

            
            if ($this->shouldAskForOTP($credentials)) {
                return redirect()->route('verify-otp');
            }else{
                $user = Auth::user();

                return $this->sendLoginResponse($request);
            }
           
        }

        // Handle failed login attempts
        return $this->sendFailedLoginResponse($request);
    }

    // Add the shouldAskForOTP() method to check if the user requires OTP verification
    protected function shouldAskForOTP($credentials)
    {
        // Define the condition to check if the user requires OTP verification
        // You can compare the user's role, email, or any other identifier
        $specificEmails = [
            'admin@test.com',
            'adam@yopmail.com',
            'eve@yopmail.com',
            'hashishah00@gmail.com'
        ];

        return in_array($credentials['email'], $specificEmails);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showCustomerRegisterForm()
    {
        return view('auth.register', ['type' => 'customer']);
    }

    public function showAdminRegisterForm()
    {
        return view('auth.register', ['type' => 'admin']);
    }

    public function registerCustomer(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        $verificationCode = rand(100000, 999999);
        $user->verification_code = $verificationCode;
        $user->verification_code_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new VerificationMail($verificationCode));

        return redirect()->route('verification.form')->with('status', 'A verification code has been sent to your email.');
    }

    public function registerAdmin(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        $verificationCode = rand(100000, 999999);
        $user->verification_code = $verificationCode;
        $user->verification_code_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        Mail::to($user->email)->send(new VerificationMail($verificationCode));

        return redirect()->route('verification.form')->with('status', 'A verification code has been sent to your email.');
    }

    public function showVerificationForm()
    {
        return view('auth.verify');
    }

    public function verifyCode(Request $request)
{
    // Validate the verification code input
    $validated = $request->validate([
        'verification_code' => 'required|string|min:6|max:6',
    ]);

    // Get the currently authenticated user
    $user = Auth::user();

    // If no user is logged in
    if (!$user) {
        return redirect()->route('login')->withErrors([
            'email' => 'You must be logged in to verify your email.',
        ]);
    }

    // Check if the code matches
    if ($user->verification_code === $validated['verification_code']) {

        // Check if the code has not expired
        if ($user->verification_code_expires_at && $user->verification_code_expires_at > now()) {

            // Update the user with verified email and clear the code
            $user->update([
                'email_verified_at' => now(),
                'verification_code' => null,
                'verification_code_expires_at' => null,
            ]);

            return redirect()->route('home')->with('status', 'Your email has been verified!');
        } else {
            return back()->withErrors([
                'verification_code' => 'The verification code has expired.',
            ]);
        }
    }

    // If the code is incorrect
    return back()->withErrors([
        'verification_code' => 'The verification code is incorrect.',
    ]);
}
}

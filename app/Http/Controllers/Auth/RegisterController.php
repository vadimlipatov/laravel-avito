<?php

namespace App\Http\Controllers\Auth;

use App\Entity\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\Auth\VerifyMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'verify_token' => str_random(),
            'status' => User::STATUS_WAIT,
        ]);

        Mail::to($user->email)->send(new VerifyMail($user));
        event(new Registered($user));

        return redirect()->route('login')
            ->with('success', 'Check your email and click on the link to verify.');
    }

    public function verify($token)
    {
        if (!$user = User::where('verify_token', $token)->first()) {
            return redirect()->route('login')->with('error', 'Sorry your link cannot be identified.');
        }

        if ($user->status != User::STATUS_WAIT) {
            return redirect()->route('login')->with('error', 'Your email has already been verified.');
        }

        $user->status = User::STATUS_ACTIVE;
        $user->verify_token = null;
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Your email has been verified. You can now login.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Hash;


class ForgotPasswordController extends Controller
{
    //

      public function reset_password()
    {
        return view('auth.reset_password');
    }

    public function send_reset_link(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found in our system!');
        }

        // ✅ Token generate
        $token = Str::random(64);

        // ✅ Save token in password_reset_tokens (Laravel 9/10)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetUrl = url('/reset-password/' . $token) . '?email=' . urlencode($request->email);

        // ✅ Send mail
        Mail::to($request->email)->send(new ResetPasswordMail($user, $resetUrl));

        return back()->with('success', 'Reset password link sent to your email!');
    }

    public function show_reset_form(Request $request, $token)
    {
        return view('auth.reset_password_form', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->with('error', 'Invalid reset request!');
        }

        if ($record->token !== $request->token) {
            return back()->with('error', 'Invalid token!');
        }

        // ✅ Optional: token expiry (30 mins)
        $createdAt = Carbon::parse($record->created_at);
        if ($createdAt->diffInMinutes(Carbon::now()) > 30) {
            return back()->with('error', 'Token expired! Please request again.');
        }

        // ✅ Update password in users table
        DB::table('users')->where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        // ✅ Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password changed successfully! Please login.');
    }
}

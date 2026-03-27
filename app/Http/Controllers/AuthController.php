<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegisterForm() { return view('auth.register'); }
    public function register(Request $req) {
        $req->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
        DB::table('users')->insert([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('login.form')->with('success', 'Registration successful. Please login.');
    }

    public function showLoginForm() { return view('auth.login'); }
    public function login(Request $req) {
        $credentials = $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials, $req->has('remember'))) {
            $req->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        return back()->withErrors(['email'=>'Invalid credentials'])->onlyInput('email');
    }

    public function showForgotForm() { return view('auth.forgot'); }
    public function sendResetCode(Request $req) {
        $req->validate(['email'=>'required|email|exists:users']);
        $token = Str::upper(Str::random(6));
        DB::table('password_resets')->updateOrInsert(
            ['email'=>$req->email],
            ['token'=>$token, 'created_at'=>now()]
        );
        // Kirim email (bisa gunakan Mail::raw dsb.)
        Mail::raw("Your reset code: $token", function($m) use($req){
            $m->to($req->email)->subject('Password Reset Code');
        });
        return redirect()->route('password.verify.form')->with(['email'=>$req->email]);
    }

    public function showVerifyForm(Request $req) {
        return view('auth.verify')->with('email', session('email'));
    }
    public function verifyCode(Request $req) {
        $req->validate(['email'=>'required|email','token'=>'required']);
        $record = DB::table('password_resets')->where('email',$req->email)
                    ->where('token',$req->token)->first();
        if (!$record) {
            return back()->withErrors(['token'=>'Invalid code']);
        }
        return redirect()->route('password.reset.form')->with('email',$req->email);
    }

    public function showResetForm(Request $req) {
        return view('auth.reset')->with('email', session('email'));
    }
    public function resetPassword(Request $req) {
        $req->validate([
            'email'=>'required|email|exists:users',
            'password'=>'required|confirmed|min:6'
        ]);
        DB::table('users')->where('email',$req->email)
            ->update(['password'=>Hash::make($req->password), 'updated_at'=>now()]);
        DB::table('password_resets')->where('email',$req->email)->delete();
        return redirect()->route('login.form')->with('success','Your password has been reset successfully.');
    }

    public function logout(Request $req) {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}

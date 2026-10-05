<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if ((bool) session()->get('preventia_admin', false)) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:80'],
            'password' => ['required', 'string', 'max:120'],
        ]);

        $expectedUsername = (string) config('preventia.admin_username', 'nica');
        $expectedPassword = (string) config('preventia.admin_password', 'preventia-admin');

        if (
            ! hash_equals($expectedUsername, $credentials['username'])
            || ! hash_equals($expectedPassword, $credentials['password'])
        ) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'The administrator credentials are not recognized.']);
        }

        $request->session()->regenerate();
        $request->session()->put('preventia_admin', true);
        $request->session()->put('preventia_admin_name', $expectedUsername);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['preventia_admin', 'preventia_admin_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
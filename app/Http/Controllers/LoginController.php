<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /** 
     * @return Application|Factory|View
     */
    public function login(): Factory|View|Application
    {
        $logo = Helpers::get_business_settings('logo');
        $logo = '/public/images/' . $logo;
        return view('admin-views.auth.login', compact('logo'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function submit(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if (auth('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            if (Auth::check() && Auth::guard('web')->user()->user_type == 3) {
                return redirect()->route('admin.dashboard');
            } else if (Auth::check() && Auth::guard('web')->user()->user_type == 4) {
                return redirect()->route('admin.pos.index');
            } else {
                return redirect()->route('admin.order.list');
            }
        }
        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['Credentials does not match.']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        auth()->guard('web')->logout();
        return redirect()->route('admin.auth.login');
    }
}

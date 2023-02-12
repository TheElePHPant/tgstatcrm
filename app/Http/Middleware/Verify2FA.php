<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class Verify2FA
{

    protected $except = [
        'admin/auth/login',
        'admin/auth/logout',
    ];

    public function handle(Request $request, Closure $next)
    {
        if ($this->inExceptArray($request)) {
            return $next($request);
        }
        $user = auth('admin')->user();
        if (null === $user) {
            return redirect(admin_url('auth/login'));
        }
        if ($user->enable_2fa) {
            if (null === $user->token_2fa || null === $user->token_2fa_expires) {
                return redirect()->route('admin.2fa.form');
            }
            // dd($user);
            if ($user->token_2fa_expires->lt(Carbon::now())) {
                $user->token_2fa = null;
                $user->teken_2fa_expires = null;
                $user->save();
                return $next($request);
            }
        }

        return $next($request);
    }


    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}

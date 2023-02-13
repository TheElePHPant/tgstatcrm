<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use Encore\Admin\Controllers\AuthController as BaseAuthController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Psy\Util\Str;

class AuthController extends BaseAuthController
{
    protected function settingForm()
    {
        $class = config('admin.database.users_model');

        $form = new Form(new $class());

        $form->display('username', trans('admin.username'));
        $form->text('name', trans('admin.name'))->rules('required');
        $form->image('avatar', trans('admin.avatar'));
        $form->password('password', trans('admin.password'))->rules('confirmed|required');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });
        $form->html('<br><hr><br>Дфухфакторная авторизация');
        if (!auth('admin')->user()->enable_2fa) {
            $form->html(\Socialite::driver('telegram')->getButton())->help('Авторизуйтесь через телеграм, обязательно отметив чекбокс');
        } else {
            $form->html('<a href="' . route('admin.2fa.disable') . '" class="btn btn-danger">Отключить двухфакторную авторизацию</a>');
        }

        $form->setAction(admin_url('auth/setting'));

        $form->ignore(['password_confirmation']);

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        $form->saved(function () {
            admin_toastr(trans('admin.update_succeeded'));
            return redirect(admin_url('auth/setting'));
        });

        return $form;
    }

    public function enable2fa()
    {
        $user = auth('admin')->user();
        $user->telegram_id_2fa = request('id');
        $user->enable_2fa = true;
        $user->telegram_username = request('username');
        $user->save();
        return redirect()->route('admin.home');
    }

    public function form2fa()
    {
        if (!request('error')) {
            $code = \Str::random(8);
            $user = auth('admin')->user();
            $user->token_2fa = $code;
            $user->save();
            $res = \Http::post('https://api.telegram.org/bot' . config('services.telegram.client_secret') . '/sendMessage', [
                'chat_id' => $user->telegram_id_2fa,
                'text' => 'Ваш код подтверждения входа: ' . $code,
            ]);
        } else {
            $user = null;
        }

        return view('admin::2fa', ['username' => $user?->telegram_username]);
    }

    public function disable2faConfirm()
    {
        $data = \request()->all();
        $user = auth('admin')->user();
        if($user->{'2fa_disable_token'}===$data['code']) {
            $user->{'2fa_disable_token'} = null;
            $user->token_2fa = null;
            $user->enable_2fa = false;
            $user->token_2fa_expires = null;
            $user->save();
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('admin.2fa.disable', ['error' => 1]);
        }
    }

    public function disable2fa()
    {
        if (!\request('error')) {
            $code = \Str::random(8);
            $user = auth('admin')->user();
            $user->{'2fa_disable_token'} = $code;
            $user->save();
            $res = \Http::post('https://api.telegram.org/bot' . config('services.telegram.client_secret') . '/sendMessage', [
                'chat_id' => $user->telegram_id_2fa,
                'text' => 'Для отключения 2FA введите код: ' . $code,
            ]);
        }
        return view('admin::2fa-disable', ['username' => auth('admin')->user()->telegram_username]);
    }

    public function verify2fa()
    {
        $data = request()->all();
        $code = $data['code'];
        $user = auth('admin')->user();
        if ($user->token_2fa === $code) {
            $user->token_2fa_expires = Carbon::now()->addHours(12);
            $user->save();
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('admin.2fa.form', ['error' => 1])->withErrors([
                'username' => 'Неправильный код подтверждения'
            ]);
        }
    }

    public function getLogout(Request $request)
    {
        $user = auth('admin')->user();
        if (null !== $user) {
            $user->token_2fa = null;
            $user->token_2fa_expires = null;
            $user->save();
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(admin_url('/'));
    }
}

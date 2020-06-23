<?php

namespace LaravelEnso\Core\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\LoginController as Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use LaravelEnso\Core\App\Events\Login;
use LaravelEnso\Core\App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->maxAttempts = config('enso.auth.maxLoginAttempts');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();
    }

    protected function attemptLogin(Request $request)
    {
        $user = $this->loggableUser($request);

        if (! $user) {
            return false;
        }

        Auth::login($user, $request->input('remember'));

        Login::dispatch($user, $request->ip(), $request->header('User-Agent'));

        return true;
    }

    protected function authenticated(Request $request, $user)
    {
        return response()->json([
            'auth' => Auth::check(),
            'csrfToken' => csrf_token(),
        ]);
    }

    private function loggableUser(Request $request)
    {
        $user = User::whereEmail($request->input('email'))->first();

        if (! optional($user)->currentPasswordIs($request->input('password'))) {
            return;
        }

        if ($user->passwordExpired()) {
            throw ValidationException::withMessages([
                'email' => 'Password expired. Please set a new one.',
            ]);
        }
        if ($user->isInactive()) {
            throw ValidationException::withMessages([
                'email' => 'Account disabled. Please contact the administrator.',
            ]);
        }

        return $user;
    }
}

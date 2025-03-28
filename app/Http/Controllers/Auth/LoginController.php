<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use App\DatabaseConnectionManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cache;

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

    use AuthenticatesUsers, ThrottlesLogins;

    protected $maxAttempts = 5;

    protected $decayMinutes = 1;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    public function username()
    {
        return 'NOM';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (session('error')) toast(session('error'), 'error');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $connection = Connection::whereRaw('LOWER(Company) = ?', [strtolower(request('company'))])->first();

        if ($connection) {

            $status = (new DatabaseConnectionManager)->setConnectionConfig($connection);

            if ($status !== true) return $status;

            $user = User::where('CLA', $request->input('password'))
            ->whereRaw('LOWER(NOM) = LOWER(?) COLLATE Latin1_General_CI_AI', [$request->input('NOM')])
            ->first();

            if ($user) {
                Cache::put('company', $connection->Company);
                Auth::login($user);
                return $this->sendLoginResponse($request);
            }

            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        }

        throw ValidationException::withMessages([
            'company' => 'La empresa no existe.',
        ]);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'company' => 'required|string'
        ]);
    }

    private function updatePasswordConfirmationTimestamp(Request $request)
    {
        if ($request->session()->has('auth.password_confirmed_at')) {
            $request->session()->put('auth.password_confirmed_at', time());
        }
    }
}

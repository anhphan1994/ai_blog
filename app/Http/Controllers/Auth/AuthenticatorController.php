<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AuthenticatorController extends Controller
{

    use AuthenticatesUsers;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    protected function redirectTo()
    {
        return '/dashboard'; // Redirect đến trang dashboard sau khi đăng nhập thành công
    }

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function signin(){
        return view('auth.signin');
    }

    public function signUp()
    {
        return view('auth.signup');
    }


    public function register(UserRequest $request)
    {
        // create user
        $data = $request->only([
            'email',
            'password',
            'password_confirm'
        ]);

        // set role
        $data['role'] = User::USER;
        $data['uuid'] = Str::uuid()->toString();

        // create and set role
        $user = $this->userRepository->createUser($data);

        $user->assignRole(User::USER);

        if ($user) {
            // send mail
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard');
            }
        }

        // send mail
        return redirect(route('login'));
    }

    public function verify(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            if($user){
                $user->is_verified = true;
                $user->save();
                Auth::login($user);
            }
            return redirect()->route('dashboard');
        } catch (\Throwable $th) {
            return redirect()->route('login');
        }

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function forgot()
    {
        return view('auth.forgot');
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = $this->userRepository->getByEmail($request->email);
        if ($user) {
            $newPass = Str::random(10);
            $user = $this->userRepository->updateNewPassword($user, $newPass);
            if ($user) {
                Mail::to($request->email)->send(new ForgotPasswordMail($newPass));
            }
        }

        return redirect('/login');
    }
}

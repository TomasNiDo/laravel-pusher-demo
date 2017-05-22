<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;    
use Illuminate\Support\Facades\Session;

use Socialite;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Redirect the user to the GitHub authentication page.
     * Also passes a `redirect` query param that can be used
     * in the handleProviderCallback to send the user back to
     * the page they were originally at.
     *
     * @param Request $request
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        return Socialite::driver('github')
            ->with(['redirect_uri' => env('GITHUB_CALLBACK_URL' ) . '?redirect=' . $request->input('redirect')])
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     * If a "redirect" query string is present, redirect
     * the user back to that page.
     *
     * @param Request $request
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        $user = Socialite::driver('github')->user();


        Session::put('user', $user);


        $redirect = $request->input('redirect');
        if($redirect)
        {
            return redirect($redirect);
        }

        return 'GitHub auth successful. Now navigate to a demo.';
    }
}

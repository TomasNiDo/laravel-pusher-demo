<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ActivityController extends Controller
{
    var $pusher;
    var $user;

    public function __construct()
    {
        $this->pusher = App::make('pusher');
    }

    /**
     * Serve the example activities view
     */
    public function index()
    {
    	$user = session('user');

        // If there is no user, redirect to GitHub login
        if(!$user) {
            return redirect('auth/github?redirect=/activities');
        }

        // TODO: provide some useful text
        $activity = [
            'text' => $user->getNickname() . ' has visited the page',
            'username' => $user->getNickname(),
            'avatar' => $user->getAvatar(),
            'id' => str_random()
        ];

        $this->pusher->trigger('activities', 'user-visit', $activity);

        // TODO: trigger event

        return view('activities');
    }

    /**
     * A new status update has been posted
     * @param Request $request
     */
    public function statusUpdate(Request $request)
    {
    	$user = session('user');
        $statusText = e($request->input('status_text'));

        // TODO: trigger event
        $activity = [
            'text' => $statusText,
            'username' => $user->getNickname(),
            'avatar' => $user->getAvatar(),
            'id' => str_random(),
        ];

        $this->pusher->trigger('activities', 'new-status-update', $activity);
    }

    /**
     * Like an exiting activity
     * @param $id The ID of the activity that has been liked
     */
    public function like(Request $request, $id)
    {
    	$user = session('user');

        // TODO: trigger event
        $activity = [
            'text' => $user->getNickname() . ' liked a status update.',
            'username' => $user->getNickname(),
            'avatar' => $user->getAvatar(),
            'id' => str_random(),
            'likedActivityId' => $id
        ];

        $this->pusher->trigger('activities', 'status-update-liked', $activity);
    }
}

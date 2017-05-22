<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    var $pusher;
    var $user;
    var $chatChannel;

    const DEFAULT_CHAT_CHANNEL = 'private-chat';

    public function __construct()
    {
        $this->pusher = App::make('pusher');
        $this->user = Session::get('user');
        $this->chatChannel = self::DEFAULT_CHAT_CHANNEL;
    }

    public function index()
    {
    	$user = session('user');

        if(!$user)
        {
            return redirect('auth/github?redirect=/chat');
        }

        return view('chat', ['chatChannel' => $this->chatChannel]);
    }

    public function message(Request $request)
    {
    	$user = session('user');

        $message = [
            'text' => e($request->input('chat_text')),
            'username' => $user->getNickname(),
            'avatar' => $user->getAvatar(),
            'timestamp' => (time()*1000)
        ];
        $this->pusher->trigger($this->chatChannel, 'new-message', $message);
    }

    public function auth(Request $request)
    {
    	$user = session('user');

    	if (!$user) {
    		abort(401);
    	}

    	$socketId = $request->input('socket_id');
    	$channelName = $request->input('channel_name');

    	return $this->pusher->socket_auth($channelName, $socketId);

    }
}

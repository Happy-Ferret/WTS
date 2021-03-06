<?php

class user extends \BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth', array('only' => array('getDashboard')));
    }

    public function getRegister()
    {
        $this->layout->content = View::make('users.register');
    }

    public function postCreate()
    {
        $validator = Validator::make(Input::all(), User::$rules);

        if ($validator->passes()) {
            $user = new User;
            $user->firstname = Input::get('firstname');
            $user->lastname = Input::get('lastname');
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->save();

            return Redirect::to('users/login')->with('message', 'Thanks for registering!');
        } else {
            return Redirect::to('users/register')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();
        }
    }

    public function getLogin()
    {
        //$this->layout->content = View::make('users.login');
        $view = array();
        // home.index will look up the path 'app/views/home/index.php'
        return $this->theme->of('users.login', $view)->render();
    }

    public function postSignin()
    {
        if (Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')))) {
            return Redirect::to('user/dashboard')->with('message', 'You are now logged in!');
        } else {
            return Redirect::to('user/login')
                ->with('message', 'Your username/password combination was incorrect')
                ->withInput();
        }
    }

    public function getDashboard()
    {
        $this->layout->content = View::make('users.dashboard');
    }

    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('users/login')->with('message', 'Your are now logged out!');
    }
}
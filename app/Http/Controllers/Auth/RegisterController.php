<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Carbon\Carbon;
use Mail;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
     protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
       // return User::create([
         \DB::transaction(function() use ($data) { 

               $user = User::create([ 
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'has_newsletters' => 0,
                    'hidden' => 0,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => Carbon::now('Asia/Beirut'),
                    'updated_at' => Carbon::now('Asia/Beirut')
                ]);


                // update the created_by/ update_by user_id
                \DB::table('users')
                  ->where('id', '=', $user->id)
                  ->update(
                     array('created_by' => $user->id,
                           'updated_by' => $user->id
                          )
                );

                $name = $data['name'];
                $email = $data['email'];
                $subject = 'Welcome to EIDEAL';

   
                if (! empty($_POST)) 
                { 
                    Mail::send('emails.signup', array('name' => $name, 'email' => $email, 'subject' => $subject), function($message) use ($name, $email, $subject)
                    {
                        $message->from('no-reply@eideal.com', 'EIDEAL');
                        $message->to($email)->subject($subject);
                    });
                }

                session(['user' => $user]); 

        });

        // return the user to login 
        return session('user');      
    }

}

<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Models\Applicant;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\ApplicationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    const FIELD_PASSWORD = 'password';
    const FIELD_PHONE = 'phone';
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
    protected $redirectTo = '/customer/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
   


   protected function guard()
    {
        return auth()->guard('applicant');
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
            'user_name' => 'required|alpha_dash|string|min:4|max:255|unique:applicants,user_name',
            'Applicant_Full_Name' => 'required|string|min:4|max:255',
            'email' => 'required|string|email|max:255|unique:applicants',
            self::FIELD_PHONE => 'required|string|max:20|unique:applicants',
            self::FIELD_PASSWORD => 'required|string|min:6|confirmed',
            'role' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        if(!empty($data['role'])){
            $applicant = Applicant::create([
                'name' => $data['Applicant_Full_Name'],
                'user_name' => $data['user_name'],
                'email' => $data['email'],
                self::FIELD_PHONE => $data[self::FIELD_PHONE],
                'role' => $data['role'],
                self::FIELD_PASSWORD => Hash::make($data[self::FIELD_PASSWORD]),
            ]);
        }
        return $applicant;
    }


    public function showRegistrationForm()
    {
        return view('customer-auth.register');
    }
    public function register(Request $request)
    {


    

            $rules = [
            'user_name'=>'required',
            'Applicant_Full_Name'=>'required',
            'email'=>'nullable|email|unique:applicants',
            self::FIELD_PHONE=>'required|unique:applicants',
            self::FIELD_PASSWORD=>'required',
            'role'=>'required',
        ];
        $this->validate($request, $rules);
        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

      return $this->registered($request, $user)
            ?: redirect($this->redirectPath())
                ->with('message', 'Registration successful! Please log in to continue.')
                ->with('alert-class', 'alert-success');
    }
   

}

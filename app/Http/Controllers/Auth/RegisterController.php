<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\SecurityAnswer;
use Illuminate\Support\Facades\Storage;

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
    protected $redirectTo = '/customer/products';

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
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'first_answer' => 'required|string|max:255',
            'second_answer' => 'required|string|max:255',
            'third_answer' => 'required|string|max:255',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        $name = $data['name'];
        $email = $data['email'];
        $password = bcrypt($data['password']);
        $is_admin = 0;
        $is_active = 1;
        $address = $data['address'];
        $phone = $data['phone'];
        $first_answer = $data['first_answer'];
        $second_answer = $data['second_answer'];
        $third_answer = $data['third_answer'];
        $photo = $data['photo'];
        
        $photo_name = 'P'.time().'.'.$photo->extension();

        Storage::disk('public')->put('/uploads/users/'.$photo_name, file_get_contents($photo));
        $user_photo_post = $photo_name;
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'is_admin' => $is_admin,
            'is_active' => $is_active,
            'address' => $address,
            'phone' => $phone,
            'photo' => $user_photo_post,
        ];
        User::create($data);

        // CREATE SECURITY ANSWERS FOR THE NEW USER
        $user = User::where('email', $email)->first();
        $data_create = [
            'user_id' => $user->id,
            'first_answer' => $first_answer,
            'second_answer' => $second_answer,
            'third_answer' => $third_answer,
        ];
        SecurityAnswer::create($data_create);
        return $user;
    }
}

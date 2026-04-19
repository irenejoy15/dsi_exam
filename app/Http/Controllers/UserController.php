<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SecurityAnswer;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        if($search){
            $users = User::where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->paginate(5);
        } else {
            $users = User::paginate(5);
        }
        return view('users.index', compact('users', 'search'));
    }

    public function store(UserCreateRequest $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));
        $is_admin = $request->input('is_admin');
        $is_active = $request->input('is_active');
        $address = $request->input('address');
        $phone = $request->input('phone');

        $photo = $request->file('photo');
        
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
        $data = [
            'user_id' => $user->id,
        ];
        SecurityAnswer::create($data);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function update(UserEditRequest $request)
    {
        $user = User::findOrFail($request->input('id'));

        $user->name = $request->input('name_update');
        $user->email = $request->input('email_update');
        if($request->input('password_update')):
            $user->password = bcrypt($request->input('password_update'));
        endif;
        $user->is_admin = $request->input('is_admin_update');
        $user->is_active = $request->input('is_active_update');
        $user->address = $request->input('address_update');
        $user->phone = $request->input('phone_update');

        if($request->hasFile('photo_update')):
            Storage::disk('public')->delete('/uploads/users/'.$user->photo);
            $photo = $request->file('photo_update');
            $photo_name = 'P'.time().'.'.$photo->extension();
            Storage::disk('public')->put('/uploads/users/'.$photo_name, file_get_contents($photo));
            $user->photo = $photo_name;
        endif;

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->input('id'));
        if(empty($user)):
            return redirect()->route('users.index')->with('error', 'User not found.');
        endif;
        Storage::disk('public')->delete('/uploads/users/'.$user->photo);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function getSecurityQuestions($user_id)
    {
        $user = User::findOrFail($user_id);
        if(empty($user)):
            return response()->json(['error' => 'User not found.'], 404);
        endif;
        $securityAnswers = $user->securityAnswers;
        return response()->json($securityAnswers);
    }
    public function updateSecurityQuestions(Request $request)
    {
        $user_id = $request->input('id');
        $user = User::findOrFail($user_id);
        if(empty($user)):
            return redirect()->route('users.index')->with('error', 'User not found.');
        endif;
        $securityAnswers = $user->securityAnswers;
        $securityAnswers->first_answer = strtolower($request->input('first_answer'));
        $securityAnswers->second_answer = strtolower($request->input('second_answer'));
        $securityAnswers->third_answer = strtolower($request->input('third_answer'));
        $securityAnswers->save();
        return redirect()->route('users.index')->with('success', 'Security questions updated successfully.');
    }
}

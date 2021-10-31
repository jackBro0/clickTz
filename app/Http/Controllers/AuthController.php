<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // validate request

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $userRole = $user->role()->first();

            $token = $user->createToken($user->email . '_' . now(), [$userRole->role]);

            return $this->responseSuccess(
                [
                    'user' => $user,
                    'toke' => $token->accessToken
                ]
            );
        } else {
            return $this->responseFail(401);
        }
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'min:6|max:12|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responseFail(422, $validator->messages());
        }

        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => 2,
            'password' => Hash::make($request->password),
            'phone' => $request->phone
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $userRole = $user->role()->first();

            $token = $user->createToken($user->email . '_' . now(), [$userRole->role]);

            return $this->responseSuccess(
                [
                    'user' => $user,
                    'token' => $token->accessToken
                ]
            );
        } else {
            return $this->responseFail(401);
        }
    }


    public function userInfo($id)
    {
        return User::findOrfail($id);
    }
}

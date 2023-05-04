<?php

namespace App\Http\Controllers;

use App\Models\Carowner;
use App\Models\DriverTime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:15',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:5',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|max:12|unique:users,phone_number',
            'type' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return self::myresponse(false, $validator->errors()->first());
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone_number' => $request->phone_number,
                'type' => $request->type,
            ]);

            $token = $user->createToken('RegirterToekn')->accessToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];

            $message = 'Register Success';
            return $this->myresponse(true, $message, $response);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return self::myresponse(false, $validator->errors()->first());
        } else {
            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];
            if (auth()->attempt($data)) {
                $user = User::find(auth()->user()->id);
                $token = $user->createToken('LoginToekn')->accessToken;
                $response = [
                    'user' => $user,
                    'token' => $token
                ];
                $message = 'Login success';
                return $this->myresponse(true, $message, $response);
            } else
                return $this->myresponse(false, 'Login Failed');
        }
    }

    public function account(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'location' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return self::myresponse(false, $validator->errors()->first());
        } else {

            $user = User::find(auth()->user()->id);
            if ($user->type == 1) {
                $user->location = $request->location;
                $user->save();
            } else {

                Carowner::Create([
                    'location' => $request->location,
                    'driver_id' => auth()->user()->id,
                ]);
            }
        }
        return self::myresponse(true, 'Preparing Account Done!');
    }
}

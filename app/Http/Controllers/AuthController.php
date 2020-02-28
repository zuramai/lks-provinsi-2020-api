<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\LoginToken;
use Hash;
use Illuminate\Support\Str;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'invalid login'], 401);
        }

        $check = Auth::attempt(['username' => $request->username, 'password' => $request->password]);

        if(!$check) {
            return response()->json(['status' => false, 'message' => 'invalid login'], 401);
        }

        $user_id = Auth::user()->id;
        
        $token = new LoginToken;
        $token->user_id = $user_id;
        $token->token = Hash::make(Str::random(10));
        $token->save();

        return response()->json(['status' => true, 'message' => 'Successfully logged in!', 'token' => $token->token]);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|alpha|min:2|max:20',
            'last_name' => 'required|alpha|min:2|max:20',
            'username' => "required|string|min:5|max:12|unique:users|regex:'^[a-zA-Z0-9_\.]*$'",
            'password' => 'required|min:5|max:12'
        ],[
            'username.regex' => "Username must be only alphabetic, numeric, underscores and dot character"
        ]);

        if($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'invalid field', 'errors' => $validator->messages()], 422);
        }

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = new LoginToken;
        $token->user_id = $user->id;
        $token->token = Hash::make(Str::random(10));
        $token->save();

        return response()->json(['status' => true, 'message' => "Successfully Register!", 'token' => $token->token]);

    }

    public function logout(Request $request) {
        $token = $request->get('token');

        $find = LoginToken::where('token', $token)->first();
        $find->delete();

        return response()->json(['status' =>true, 'message' => 'Logout Success!']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function signup(Request $request) {
        $validated = $request->validate( [
            'full_name' => 'required',
            'bio' => 'required|max:100',
            'username' => 'required|min:3|unique:users,username|regex:/[a-zA-Z0-9._]+$/',
            'password' => 'required|min:6'
        ]);
        $user = User::create([
            'full_name' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'username' => $validated['username'],
            'bio' => $validated['bio'],
         ]);

         $token = $user->createToken('auth')->plainTextToken;
         return response()->json([ 
            'message' => 'Register Success',
            'token' => $token,
            'user' => [$user]
         ], 201);
        }

        public function Login(Request $request) {
            $validated = $request->validate([
                'password' => 'required|min:5|max:20',
                'username' => 'required'
            ]);

            $user = User::where('username', $validated['username'])->first();
            if($user &&(Hash::check($validated['password'], $user->password) ||$validated['password'] === $user->password)) {
                $user->save();

                $token = $user->createToken('auth')->plainTextToken;

               

                return response()->json([ 
                    'message'=> 'Login Success',
                    'token' => $token,
                    'user' => [$user]
                ], 200);

                // return response()->json([
                //     'hello' => 'world'
                // ]);
            } else {
                return response()->json([
                    'message'=> 'Wrong Username or Password'
                ], 401);
            }
        }
             
                
        public function logout(Request $request) { 
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message'=> 'Logout Success'
            ], 200);
        }
}

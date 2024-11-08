<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;

use function Laravel\Prompts\error;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        //   $request->validate([
        //     'email'=>'required|email',
        //     'password'=>'required',
        //   ]);
        try{
            $user = User::where('email',$request->email)->first();
            return $this->makeToken($user);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => "unauthorized!",
            ]);
        }
            
          
          
        }
    
        
        public function register(RegisterRequest $request ){
            // return response()->json([$request]);
            // dd('sfsdfsd');
            $user = User::create($request->validated());
            return $this->makeToken($user);
          
        }
    
        public function makeToken($user){
            $token = $user->createToken('myToken')->plainTextToken;
    
            return AuthResource::make([
                'token'=>$token,
                'user'=>[
                    'name'=>$user->name,
                    'email'=>$user->email
                ]
                ]);    
        }
    
        public function logout(Request $request){
            // $request->user()->tokens()->delete();
            return response()->json(['message'=>'logout sucess'],200);
        }
    }

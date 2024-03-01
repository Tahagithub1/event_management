<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function Login(Request $request) {

       $request->validate([

         'email' => 'required|email',
         'password' => 'required'

       ]);
    //    ,['email.required' => 'مقادیر را کامل وارد کنیید' , 'email.email' => 'ایمیل را ایمیل وارد کنیید']

       $user = \App\Models\User::where('email', $request->email)->first();

       if(!$user){
         throw ValidationException::withMessages(['email' => 'مقادیر را درست وارد کنیید']);
       }

       if(!Hash::check($request->password,$user->password)){
         throw ValidationException::withMessages(['email' => 'مقادیر را درست وارد کنیید']);
       }

       $Token = $user->createToken('api-token')->plainTextToken;

       return response()->json([
            'token' => $Token
       ]);

    }


    public function Logout() {




    }

}

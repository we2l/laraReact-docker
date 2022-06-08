<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRegisterRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function Login(GetLoginRequest $request)
    {
        if(!Auth::attempt($request->validated()))
            return $this->error(401, 'Credênciais inválidas');
    
        $user = User::where('email', $request->email)->firstOrFail();
        
        return $this->success([
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer'
        ]);
    }

    public function register(StoreRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return $this->success($user->createToken('auth_token')->plainTextToken, 201);
    }

    public function me(Request $request)
    {
        return $request->user();
    }
}

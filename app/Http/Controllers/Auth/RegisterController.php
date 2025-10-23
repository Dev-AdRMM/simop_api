<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\User;
use App\Models\UserVerification;

use App\Mail\VerifyUserMail;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_key' => null,
        ]);

        $code = random_int(100000, 999999);

        UserVerification::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        Mail::to($user->email)->send(new VerifyUserMail($code));

        return response()->json([
            'status' => 'success',
            'message' => 'Utilizador registado. Um código foi enviado para o seu email para verificação.'
        ]);
    }
}

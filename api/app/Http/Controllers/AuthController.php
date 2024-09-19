<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserProfile;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

use App\Http\Requests\Auth\AuthRequest;


class AuthController extends Controller
{

    // public function register(RegisterRequest $request)
    // {
    //     //\Log::info($request);
    //     $user = User::create([
    //         'name' => $request->input('name'),
    //         'email' => $request->input('email'),
    //         'password' => Hash::make($request->input('password')),
    //     ]);

    //     $user->assignRole('user'); // default role is user
    //     return response()->json(['message' => 'success']);
    // }

    public function login(AuthRequest $request)
    {
        // attempt to authenticate
        $request->authenticate(); // check AuthRequest->authenticate

        // find user
        $user = User::where('id', Auth::user()->id)
                ->first();

        if ($user) {
            // create token in User Model
            $token = Auth::user()->createToken('API Token')->plainTextToken;
            //$user['role'] = $user->roles->pluck('name')[0];

            //\Log::info('login-' . Auth::user()->email);
            return response()->json([
                'message' => 'Authentication Success',
                'token' => $token,
                'user' => $user
            ]);

        } else {
            return response()->json([
                'message' => 'User not approved or not found',
            ], 401);
        }
    }

    public function logout()
    {
        //\Log::info('logout-' . Auth::user()->email);
        // revoke the token
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out',
        ],200);

    }
   
    // public function email(EmailRequest $request)
    // {

    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return $status === Password::RESET_LINK_SENT
    //     ? response()->json($status,200) // success
    //     : response()->json($status,422); // failed
    // }

    // public function resetPassword(ResetRequest $request)
    // {

    //     // success in validation, now reset the password
    //     $status = Password::reset(
    //         //$request->only('email', 'password', 'password_confirmation', 'token'),
    //         $request->only('email', 'password', 'password_confirmation', 'token'),
    //         function ($user) use ($request) {
    //             $user->forceFill([
    //                 'password' => Hash::make($request->password),
    //                 'remember_token' => Str::random(60),
    //             ])->save();

    //             // dev purpose
    //             //$user->tokens()->delete(); // delete the token in DB
    //         }
    //     );

    //     // success
    //     if($status == Password::PASSWORD_RESET){
    //         return response([
    //             'message' => 'Password reset successfully'
    //         ]);
    //     }

    //     // error
    //     return response([
    //         'message' => __($status)
    //     ],422);
    // }
}
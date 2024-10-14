<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Homepage
use App\Http\Controllers\Homepage\{
    BannerController,
    DeejayController,
    TopicController,
    ChoiceController,
};

// role = Guest
use App\Http\Controllers\{
    RegisterController,
    AuthController
};

// role = User
use App\Http\Controllers\User\{
    AccountController,
};




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    $user = $request->user(); // Get the authenticated user
    
    // Retrieve the user's role using Spatie
    $role = $user->roles->pluck('name')->first();

    $user['role'] = $role;

    return response()->json([
        'message' => 'Logged user info',
        'user' => $user,
        'role' => $role,
    ]);

});

// homepage
Route::get('/homepage/banners', [BannerController::class, 'show']);
Route::get('/homepage/deejays', [DeejayController::class, 'show']);
Route::get('/homepage/topics', [TopicController::class, 'show']);
Route::get('/homepage/choices/{topicId}', [ChoiceController::class, 'index']);
//Route::get('/homepage/deejay', [DeejayController::class, 'show']);

// Account Management ( logged in users )
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/account', [AccountController::class, 'show']);
    Route::put('/account/update', [AccountController::class, 'update']);
    Route::put('/account/change_password', [AccountController::class, 'changePassword']);
});


// Auth 
Route::post('/frontend/register', [RegisterController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


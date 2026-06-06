<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest as StoreUserRequest;
use App\Http\Requests\AuthUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        User::create($data);
        return response()->json(
            ["message" => "Account created successfully"],
            201,
        );
    }

    public function auth(AuthUserRequest $request)
    {
        $data = $request->validated();
        $user = User::where("email", $data["email"])->first();

        if (!$user || !Hash::check($data["password"], $user->password)) {
            return response()->json(["message" => "wrong credentials"], 401);
        }

        return response()->json(
            [
                "user" => UserResource::make($user),
                "token" => $user->createToken("auth_token")->plainTextToken,
            ],
            200,
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "Logged out successfully"]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\ChangeUserRequest;
use App\Http\Requests\RegRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function reg(RegRequest $request): JsonResponse
    {
        $user = User::query()->make($request->all());
        $user->role_id = Role::query()->where("name", $request->get("role"))->firstOrNew()->id;

        $user->save();

        return response()->json(["message" => "success", "response" => $user]);
    }

    public function change(ChangeUserRequest $request): JsonResponse
    {
        $user = auth()->user();
        if ($request->exists("role"))
            $user->role_id = Role::query()->where("name", $request->get("role"))->firstOrNew()->id;
        $user->update($request->all());

        return response()->json(["message" => "success", "response" => $user]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $credentials = $request->only(["login", "password"]);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

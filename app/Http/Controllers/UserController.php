<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeUserRequest;
use App\Http\Requests\RegRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(["message" => "success", "response" => User::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RegRequest $request
     * @return JsonResponse
     */
    public function store(RegRequest $request): JsonResponse
    {
        $user = User::query()->make($request->all());
        $user->role_id = Role::query()->where("name", $request->get("role"))->firstOrNew()->id;
        $user->setImgSrcIfNotEmpty($request->file("avatar"));

        $user->save();

        return response()->json(["message" => "success", "response" => $user]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json(["message" => "success", "response" => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ChangeUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(ChangeUserRequest $request, User $user): JsonResponse
    {
        if ($request->exists("role"))
            $user->role_id = Role::query()->where("name", $request->get("role"))->firstOrNew()->id;

        $user->setImgSrcIfNotEmpty($request->file("avatar"));
        $user->update($request->all());

        return response()->json(["message" => "success", "response" => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $result = $user->delete();
        return response()->json(["message" => $result ? "success" : "error"], $result ? 200 : 500);
    }
}

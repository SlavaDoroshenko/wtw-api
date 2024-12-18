<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get the authenticated user details",
     *     tags={"User "},
     *     @OA\Response(
     *         response=200,
     *         description="User  details",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show()
    {
        return response()->json(Auth::user());
    }

    /**
     * @OA\Put(
     *     path="/api/user",
     *     summary="Update the authenticated user details",
     *     tags={"User "},
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User  updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(UpdateUserRequest $request)
    {
        $params = $request->safe()->except('file');

        $user = Auth::user();
        $path = false;

        if($request->hasFile('file')) {
            $oldFile = $user->avatar;
            $result = $request->file('file')->store('avatars', 'public');
            $path = $result ? $request->file('file')->hashName() : false;
            $params['avatar'] = $path;
        }

        $user->update($params);

        if($path && $oldFile) {
            Storage::disk('public')->delete($oldFile);
        }

        return response()->json(Auth::user()->makeVisible('email'));
    }
    /**
     * @OA\Schema(
     *     schema="User ",
     *     type="object",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="avatar", type="string"),
     *     @OA\Property(property="email", type="string"),
     *     @OA\Property(property="created_at", type="string", format="date-time"),
     *     @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     */

    /**
     * @OA\Schema(
     *     schema="UpdateUser Request",
     *     type="object",
     *     required={"name"},
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="file", type="string", format="binary")
     * )
     */
}

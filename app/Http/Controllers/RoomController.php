<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRoomRequest;
use App\Http\Requests\JoinToRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class RoomController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/rooms",
     *     summary="Get a list of rooms",
     *     tags={"Rooms"},
     *     @OA\Response(
     *         response=200,
     *         description="List of rooms",
     *
     *     )
     * )
     */
    public function index()
    {
        return Room::all();
    }

    /**
     * @OA\Post(
     *     path="/api/rooms",
     *     summary="Create a new room",
     *     tags={"Rooms"},
     *
     *     @OA\Response(
     *         response=500,
     *         description="Error while creating room"
     *     )
     * )
     */
    public function store(AddRoomRequest $request)
    {
        $room = Room::create($request->validated());
        try {
            $room->users()->attach(Auth::user());
            return response()->json($room, 201);
        } catch (Exception $e) {
            $room->delete();
            return response()->json(['message' => 'Error while creating room'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/rooms/{room}",
     *     summary="Get a specific room by ID",
     *     tags={"Rooms"},
     *     @OA\Parameter(
     *         name="room",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Room not found"
     *     )
     * )
     */
    public function show(Room $room)
    {
        return response()->json($room);
    }

    /**
     * @OA\Put(
     *     path="/api/rooms/{room}",
     *     summary="Update a specific room",
     *     tags={"Rooms"},
     *     @OA\Parameter(
     *         name="room",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Room not found"
     *     )
     * )
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return response()->json($room, 203);
    }

    /**
     * @OA\Delete(
     *     path="/api/rooms/{room}",
     *     summary="Delete a specific room",
     *     tags={"Rooms"},
     *     @OA\Parameter(
     *         name="room",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Room deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Room is deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Room not found"
     *     )
     * )
     */
    public function destroy(Room $room)
    {
        $room->users()->detach();
        $room->delete();

        return response()->json(['message' => 'Room is deleted']);
    }

    /**
     * @OA\Post(
     *     path="/api/rooms/{room}/join",
     *     summary="Join a room",
     *     tags={"Rooms"},
     *     @OA\Parameter(
     *         name="room",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User  added to the room",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User  is added")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Incorrect password"
     *     )
     * )
     */
    public function joinToRoom(JoinToRoomRequest $request, Room $room)
    {
        if (!Hash::check($request->validated()['password'], $room->password)) {
            return response()->json(['message' => 'Password uncorrected'], 403);
        }

        $room->users()->attach(Auth::user());
        return response()->json(['message' => 'User  is added']);
    }
}

/**
 * @OA\Schema(
 *     schema="Room",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="password", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *     schema="AddRoomRequest",
 *     type="object",
 *     required={"name", "password"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="password", type="string")
 * )
 */

/**
 * @OA\Schema(
 *     schema="UpdateRoomRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="name", type="string")
 * )
 */

/**
 * @OA\Schema(
 *     schema="JoinToRoomRequest",
 *     type="object",
 *     required={"password"},
 *     @OA\Property(property="password", type="string")
 * )
 */

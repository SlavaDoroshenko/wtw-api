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
     * Display a listing of the resource.
     */
    public function index()
    {
        return Room::all();
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return response()->json($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return response()->json($room, 203);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->users()->detach();
        $room->delete();

        return response()->json(['message' => 'Room is deleted']);
    }

    public function joinToRoom(JoinToRoomRequest $request, Room $room)
    {
        if (!Hash::check($request->validated()['password'], $room->password)) {
            return response()->json(['message' => 'Password uncorrected'], 403);
        }

        $room->users()->attach(Auth::user());
        return response()->json(['message' => 'User is added']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Sprint;

class SprintController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Store a newly created sprint in storage.
     */
    public function store(Request $request)
    {
        if (Sprint::where('is_active', true)->exists()) {
            return response()->json(['error' => 'An active sprint already exists.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'startDate' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $user = auth('api')->user();
        // return response()->json(["sprint" => $user['id']], 201);
        $sprint = new Sprint([
            'startDate' => $validator->validated()['startDate'],
            'user_id' => $user->id
        ]);
        
        return response()->json($sprint, 201);
    }

    /**
     * Deactivate the specified sprint.
     */
    public function deactivate($id)
    {
        $sprint = Sprint::find($id);

        if (!$sprint) {
            return response()->json(['error' => 'Sprint not found.'], 404);
        }

        $sprint->is_active = false;
        $sprint->save();

        return response()->json($sprint, 200);
    }

    /**
     * Remove the specified sprint from storage.
     */
    public function destroy($id)
    {
        $sprint = Sprint::find($id);

        if (!$sprint) {
            return response()->json(['error' => 'Sprint not found.'], 404);
        }

        $sprint->delete();
        return response()->json(null, 204);
    }
}


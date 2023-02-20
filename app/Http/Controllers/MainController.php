<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

use App\Models\User;
use App\Models\Event;
use App\Models\Enrollment;

class MainController extends Controller
{
    // Controller method to handle enrollment API endpoint
public function enroll(Request $request)
{
    // Validate request data
    $validator = Validator::make($request->all(), [
        'event_id' => 'required|integer',
        'user_id' => 'sometimes|required|integer',
        // Add any other required fields
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first()
        ], 400);
    }

    // Begin transaction
    DB::beginTransaction();

    try {
        // Check if the user exists
        if ($request->has('user_id')) {
            $user = DB::table('users')
                ->select('*')
                ->where('id', $request->input('user_id'))
                ->first();
    
            if (!$user) {
                throw new Exception('User does not exist.');
            }
        }

        // Retrieve event information
        $event = DB::table('events')
            ->select('*')
            ->where('id', $request->input('event_id'))
            ->where('event_visibility', 1)
            ->sharedLock()
            ->first();

        // Check if event exists and is not full
        $enrollmentCount = DB::table('user_enrollment')
            ->where('id', $request->input('event_id'))
            ->count();

        if ($enrollmentCount >= $event->event_max_participants) {
            throw new Exception('This event is already full.');
        }

        // Insert new enrollment
        DB::table('user_enrollment')->insert([
            'event_id' => $request->input('event_id'),
            'user_id' => $request->input('user_id'),
            // Add any other necessary fields
        ]);

        // Commit transaction
        DB::commit();

        // Return success response
        return response()->json([
            'message' => 'Enrollment successful.'
        ]);
    } catch (Exception $e) {
        // Rollback transaction
        DB::rollback();

        // Return error response
        return response()->json([
            'message' => $e->getMessage()
        ], 400);
    }
}


public function enrolltwo(Request $request)
{
    // Validate request data
    $validator = Validator::make($request->all(), [
        'event_id' => 'required|integer',
        'user_id' => 'sometimes|required|integer',
        // Add any other required fields
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => $validator->errors()->first()
        ], 400);
    }

    // Begin transaction
    DB::beginTransaction();

    try {
        // Check if the user exists
        if ($request->has('user_id')) {
            $user = User::find($request->input('user_id'));
    
            if (!$user) {
                throw new Exception('User does not exist.');
            }
        }

        // Retrieve event information
        $event = Event::where('id', $request->input('event_id'))
            ->where('event_visibility', 1)
            ->lockForUpdate()
            ->first();

        // Check if event exists and is not full
        $enrollmentCount = $event->enrollments()->count();

        if ($enrollmentCount >= $event->event_max_participants) {
            throw new Exception('This event is already full.');
        }

        // Insert new enrollment
        $enrollment = new Enrollment;
        $enrollment->event_id = $request->input('event_id');
        $enrollment->user_id = $request->input('user_id');
        // Add any other necessary fields
        $enrollment->save();

        // Commit transaction
        DB::commit();

        // Return success response
        return response()->json([
            'message' => 'Enrollment successful.'
        ]);
    } catch (Exception $e) {
        // Rollback transaction
        DB::rollback();

        // Return error response
        return response()->json([
            'message' => $e->getMessage()
        ], 400);
    }
}


}
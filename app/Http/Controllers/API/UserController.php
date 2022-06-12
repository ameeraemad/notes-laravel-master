<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function getStatistics()
    {
        $user = request()->user('users_api');
        $catetogriesCount = $user->categories()->count();
        $waitingNotes = $user->notes()->where('status', 'Waiting')->count();
        $doneNotes = $user->notes()->where('status', 'Done')->count();
        return response()->json([
            'status' => true,
            'message' => 'success',
            'object' => [
                'categories_count' => $catetogriesCount,
                'waiting_note' => $waitingNotes,
                'done_notes' => $doneNotes,
            ]
        ]);
    }
}

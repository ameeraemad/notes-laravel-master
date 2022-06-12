<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllersService;
use App\Note;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $user = User::with('notes')->find(request()->user('users_api')->id);
        return ControllersService::generateArraySuccessResponse($user->notes, 'Success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $roles = [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|String|min:3|max:45',
            'details' => 'required|String|min:5|max:100',
        ];

        $validator = Validator::make($request->all(), $roles);

        if (!$validator->fails()) {
            $category = Category::find($request->get('category_id'));
            if ($category->user_id == $request->user('users_api')->id) {
                $note = new Note();
                $note->category_id = $request->get('category_id');
                $note->title = $request->get('title');
                $note->details = $request->get('details');
                $isSaved = $note->save();
                if ($isSaved) {
                    return ControllersService::generateProcessResponse($isSaved, 'CREATE_SUCCESS', 201);
                } else {
                    return ControllersService::generateProcessResponse($isSaved, 'CREATE_FAILED');
                }
            } else {
                return ControllersService::generateProcessResponse(false, 'NO_ACCESS_PERMISSION');
            }
        } else {
            return ControllersService::generateValidationErrorMessage($validator->errors()->first());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
        $request->request->add(['id' => $id]);
        $roles = [
            'id' => 'required|exists:notes,id',
            'title' => 'required|String|min:3|max:45',
            'details' => 'required|String|min:5|max:80',
        ];

        $validator = Validator::make($request->all(), $roles);

        if (!$validator->fails()) {
            $note = Note::find($id);
            $userId = $note->category->user->id;
            if ($userId == request()->user()->id) {
                //                $note->category_id = $request->get('category_id');
                $note->title = $request->get('title');
                $note->details = $request->get('details');
                $isSaved = $note->save();
                if ($isSaved) {
                    return ControllersService::generateProcessResponse($isSaved, 'UPDATE_SUCCESS');
                } else {
                    return ControllersService::generateProcessResponse($isSaved, 'UPDATE_FAILED');
                }
            } else {
                return ControllersService::generateProcessResponse(false, 'NO_ACCESS_PERMISSION');
            }
        } else {
            return ControllersService::generateValidationErrorMessage($validator->errors()->first());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        $note = Note::find($id);
        if ($note) {
            $userId = $note->category->user->id;
            if ($userId == request()->user()->id) {
                $isDestroyed = $note->delete();
                return ControllersService::generateProcessResponse($isDestroyed, $isDestroyed ? 'DELETE_SUCCESS' : 'DELETE_FAILED');
            } else {
                return ControllersService::generateProcessResponse(false, 'NO_ACCESS_PERMISSION');
            }
        } else {
            return ControllersService::generateProcessResponse(false, 'NOT_FOUND', 404);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $roles = [
            'id' => 'required|exists:notes,id',
        ];
        $validator = Validator::make($request->all(), $roles);

        if (!$validator->fails()) {
            $note = Note::find($id);
            $userId = $note->category->user->id;
            if ($userId == request()->user()->id) {
                $note->status = $note->status == 'Waiting' ? 'Done' : 'Waiting';
                $isSaved = $note->save();
                if ($isSaved) {
                    return ControllersService::generateProcessResponse($isSaved, 'UPDATE_SUCCESS');
                } else {
                    return ControllersService::generateProcessResponse($isSaved, 'UPDATE_FAILED');
                }
            } else {
                return ControllersService::generateProcessResponse(false, 'NO_ACCESS_PERMISSION');
            }
        } else {
            return ControllersService::generateValidationErrorMessage($validator->errors()->first());
        }
    }
}

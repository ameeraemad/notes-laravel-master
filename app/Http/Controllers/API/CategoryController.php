<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $categories = Category::where('user_id', request()->user('users_api')->id)->get();
        return ControllersService::generateArraySuccessResponse($categories, 'Success');
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
            'title' => 'required|String|min:2|max:45',
            'details' => 'required|String|min:5|max:100',
        ];
        $validator = Validator::make($request->all(), $roles);

        if (!$validator->fails()) {
            $category = new Category();
            $category->user_id = $request->user('users_api')->id;
            $category->title = $request->get('title');
            $category->details = $request->get('details');
            $isSaved = $category->save();
            if ($isSaved) {
                return ControllersService::generateProcessResponse($isSaved, 'CREATE_SUCCESS', 201);
            } else {
                return ControllersService::generateProcessResponse($isSaved, 'CREATE_FAILED');
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

    public function showCategoryNotes(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $roles = [
            'id' => 'required|exists:categories,id',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $category = Category::find($id);
            if ($category->user_id == $request->user('users_api')->id) {
                $notes = $category->notes;
                return ControllersService::generateArraySuccessResponse($notes, "Success");
            } else {
                return ControllersService::generateProcessResponse(false, 'NO_ACCESS_PERMISSION');
            }
        } else {
            return ControllersService::generateValidationErrorMessage($validator->errors()->first());
        }
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
            'id' => 'required|exists:categories,id',
            'title' => 'required|String|min:3|max:45',
            'details' => 'required|String|min:5|max:100'
        ];

        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $category = Category::find($id);
            if ($category->user_id == $request->user('users_api')->id) {
                $category->user_id = $request->user('users_api')->id;
                $category->title = $request->get('title');
                $category->details = $request->get('details');
                $isSaved = $category->save();
                if ($isSaved) {
                    return ControllersService::generateProcessResponse($isSaved, 'UPDATE_SUCCESS', 200);
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
        $category = Category::find($id);
        if ($category) {
            if ($category->user_id == request()->user('users_api')->id) {
                $isDestroyed = $category->delete();
                return ControllersService::generateProcessResponse($isDestroyed, $isDestroyed ? 'DELETE_SUCCESS' : 'DELETE_FAILED', 204);
            } else {
                return ControllersService::generateProcessResponse(false, 'NO_ACCESS_PERMISSION',403);
            }
        } else {
            return ControllersService::generateProcessResponse(false, 'NOT_FOUND', 404);
        }
    }
}

<?php

namespace App\Http\Controllers\CMS\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Note;
use App\Student;
use App\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('read-categories')) {
                $categories = Category::paginate(10);
                return view('cms.admin.categories.index', ['categories' => $categories]);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('read-categories')) {
                $categories = Student::find(auth('student')->user()->id)->categories()->paginate(10);
                return view('cms.admin.categories.index', ['categories' => $categories]);
            }
        }
        return view('cms.no-content');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
        $users = $this->getUsersForAuth();
        if (!is_null($users)) {
            return view('cms.admin.categories.create', ['users' => $users]);
        } else {
            return view('cms.no-content');
        }
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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|String|min:5|max:45',
            'details' => 'required|String|min:5|max:100',
        ]);

        $category = new Category();
        $category->user_id = $request->get('user_id');
        $category->title = $request->get('title');
        $category->details = $request->get('details');
        $isSaved = $category->save();
        if ($isSaved) {
            return response()->json(['icon' => 'success', 'title' => 'Category created successfully'], 200);
        } else {
            return response()->json(['icon' => 'success', 'title' => 'Category create failed!'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //
        $category = null;
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('read-notes')) {
                $category = Category::find($id);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('read-notes')) {
                $category = auth('student')->user()->categories()->find($id);
            }
        }

        if (!is_null($category)) {
            $notes = $category->notes()->paginate(10);
            return view('cms.admin.notes.index', ['notes' => $notes]);
        } else {
            return view('cms.no-content');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //
        $users = $this->getUsersForAuth();
        $category = null;
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('update-category')) {
                $category = Category::find($id);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('update-category')) {
                $category = auth('student')->user()->categories()->find($id);
            }
        }

        if (!is_null($category)) {
            return view('cms.admin.categories.edit', ['category' => $category, 'users' => $users]);
        } else {
            return view('cms.no-content');
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
        $request->request->add(['category_id' => $id]);
        $request->validate([
            'category_id' => 'required|exists:categories,id|integer',
            'user_id' => 'required|exists:users,id',
            'title' => 'required|String|min:5|max:45',
            'details' => 'required|String|min:5|max:100',
        ]);

        $category = Category::find($id);
        $category->user_id = $request->get('user_id');
        $category->title = $request->get('title');
        $category->details = $request->get('details');
        $isUpdated = $category->save();
        if ($isUpdated) {
            return response()->json(['icon' => 'success', 'title' => 'Category updated successfully'], 200);
        } else {
            return response()->json(['icon' => 'Failed', 'title' => 'Category update failed'], 400);
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
        $isDestroyed = false;
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('destroy-category')) {
                $isDestroyed = Category::destroy($id);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('destroy-category')) {
                $isDestroyed = auth('student')->user()
                    ->categories()
                    ->find($id)
                    ->delete();
            }
        }

        if ($isDestroyed) {
            return response()->json([
                'icon' => 'success',
                'title' => 'Category deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'icon' => 'error',
                'title' => 'Category delete failed'
            ], 400);
        }
    }

    public function showUserCategories($id)
    {
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('read-categories')) {
                $categories = User::find($id)->categories()->paginate(10);
                return view('cms.admin.categories.index', ['categories' => $categories]);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('read-categories')) {
                $categories = auth('student')->user()
                    ->categories()->where('user_id', $id)->paginate(10);
                return view('cms.admin.categories.index', ['categories' => $categories]);
            }
        }
        return view('cms.no-content');
    }

    /**
     * @return array|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUsersForAuth()
    {
        $users = null;
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('create-category')) {
                $users = User::where('status', 'Active')->get();
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('create-category')) {
                $users = User::with('student')
                    ->where('status', 'Active')
                    ->where('student_api_uuid', auth('student')->user()->api_uuid)
                    ->paginate(10);
            }
        }
        return $users;
    }
}

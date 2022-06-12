<?php

namespace App\Http\Controllers\CMS\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('read-users')) {
                $users = User::with('student')->paginate(10);
                return view('cms.admin.users.index', ['users' => $users]);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('read-users')) {
                $users = User::with('student')
                    ->where('student_api_uuid', auth('student')->user()->api_uuid)
                    ->paginate(10);
                return view('cms.admin.users.index', ['users' => $users]);
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
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('create-user')) {
                $students = Student::where('status', 'Active')->get();
                return view('cms.admin.users.create', ['students' => $students]);
            }

        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('create-user')) {
                $students = [auth('student')->user()];
                return view('cms.admin.users.create', ['students' => $students]);
            }
        }
        return view('cms.no-content');
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
            'first_name' => 'required|string|min:3|max:15',
            'last_name' => 'required|string|min:3|max:15',
            'email' => 'required|string|email|unique:users,email',
            'mobile' => 'required|numeric|digits:9|unique:users,mobile',
            'password' => 'required|string|min:4',
            'status' => 'string|in:Active,Blocked',
            'student' => 'required|uuid|exists:students,api_uuid',
        ]);

        $user = new User();
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->mobile = $request->get('mobile');
        $user->password = Hash::make($request->get('password'));
        $user->status = $request->get('status');
        $user->student_api_uuid = $request->get('student');
        $isSaved = $user->save();
        if ($isSaved) {
            return response()->json(['icon' => 'success', 'title' => 'User created successfully'], 200);
        } else {
            return response()->json(['icon' => 'success', 'title' => 'User create failed!'], 400);
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //
        $students = [];
        $user = null;
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('update-user')) {
                $students = Student::where('status', 'Active')->get();
                $user = User::find($id);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('update-user')) {
                $students = [auth('student')->user()];
                $user = auth('student')->user()->users()->find($id);
                if (!is_null($user)) {
                    return view('cms.admin.users.edit', ['user' => $user, 'students' => $students]);
                }
            }
        }
        if (!is_null($user)) {
            return view('cms.admin.users.edit', ['user' => $user, 'students' => $students]);
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
        //
        $request->request->add(['id' => $id]);
        $request->validate([
            'id' => 'required|exists:users,id',
            'first_name' => 'required|string|min:3|max:15',
            'last_name' => 'required|string|min:3|max:15',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'mobile' => 'required|numeric|digits:9|unique:users,mobile,' . $id,
            'status' => 'string|in:Active,Blocked',
            'student' => 'required|uuid|exists:students,api_uuid',
        ]);

        $user = User::find($id);
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->mobile = $request->get('mobile');
        $user->status = $request->get('status');
        $user->student_api_uuid = $request->get('student');
        $isUpdated = $user->save();
        if ($isUpdated) {
            return response()->json(['icon' => 'success', 'title' => 'User updated successfully'], 200);
        } else {
            return response()->json(['icon' => 'Failed', 'title' => 'User update failed'], 400);
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
            if (auth('admin')->user()->hasPermissionTo('delete-user')) {
                $isDestroyed = User::destroy($id);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('delete-user')) {
                $isDestroyed = User::where('student_api_uuid', auth('student')->user()->api_uuid)
                    ->find($id)
                    ->delete($id);
            }
        }
        if ($isDestroyed) {
            return response()->json([
                'icon' => 'success',
                'title' => 'User deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'icon' => 'error',
                'title' => 'User delete failed'
            ], 400);
        }
    }
}

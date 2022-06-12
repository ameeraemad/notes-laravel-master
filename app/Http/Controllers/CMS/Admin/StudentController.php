<?php

namespace App\Http\Controllers\CMS\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StudentRegistration;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
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
            if (auth('admin')->user()->hasPermissionTo('read-students')) {
                $students = Student::paginate(10);
                return view('cms.admin.students.index', ['students' => $students]);
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
            if (auth('admin')->user()->hasPermissionTo('create-student')) {
                return view('cms.admin.students.create');
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
            'name' => 'required|string|min:3',
            'email' => 'required:string|email|unique:students,email',
            'mobile' => 'required|string|numeric|unique:students,mobile',
            'password' => 'required|string|min:4',
            'gender' => 'required|string|in:Male,Female',
            'account_status' => 'string|in:Active,Blocked'
        ]);

        $student = new Student();
        $student->name = $request->get('name');
        $student->email = $request->get('email');
        $student->mobile = $request->get('mobile');
        $student->gender = $request->get('gender');
        $student->status = $request->get('status');
        $student->api_uuid = Str::uuid()->toString();
        $student->password = Hash::make($request->get('password'));
        $isSaved = $student->save();
        if ($isSaved) {
            // $this->sendEmail($student);
            $student->assignRole('Student');
            return response()->json(['icon' => 'success', 'title' => 'Student created successfully'], 200);
        } else {
            return response()->json(['icon' => 'success', 'title' => 'Student created successfully'], 400);
        }
    }

    private function sendEmail(Student $student)
    {
        Mail::send(new StudentRegistration($student));
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

    public function showUsers($id)
    {
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('read-users')) {
                $users = Student::with('users')->find($id)->users()->paginate(10);
                return view('cms.admin.users.index', ['users' => $users]);
            }
        }
        return view('cms.no-content');
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
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('update-student')) {
                $student = Student::find($id);
                return view('cms.admin.students.edit', ['student' => $student]);
            }
        } elseif (auth('student')->check()) {
            if ($id == auth('student')->user()->id) {
                $student = Student::find($id);
                return view('cms.admin.students.edit', ['student' => $student]);
            }
        }
        return view('cms.no-content');
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
            'id' => 'required|exists:students,id',
            'name' => 'required|string|min:3',
            'email' => 'required:string|email|unique:students,email,' . $id,
            'mobile' => 'required|string|numeric|unique:students,mobile,' . $id,
            'gender' => 'required|string|in:Male,Female',
            'status' => 'string|in:Active,Blocked'
        ]);

        $student = Student::find($id);
        $student->name = $request->get('name');
        $student->email = $request->get('email');
        $student->mobile = $request->get('mobile');
        $student->gender = $request->get('gender');
        if ($request->has('status')) {
            $student->status = $request->get('status');
        }
        $isSaved = $student->save();

        if ($isSaved) {
            return response()->json(['icon' => 'success', 'title' => 'Student updated successfully'], 200);
        } else {
            return response()->json(['icon' => 'Failed', 'title' => 'Student update failed'], 400);
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
            if (auth('admin')->user()->hasPermissionTo('delete-student')) {
                $isDestroyed = Student::destroy($id);
            }
        }

        if ($isDestroyed) {
            return response()->json([
                'icon' => 'success',
                'title' => 'Student deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'icon' => 'error',
                'title' => 'Student delete failed'
            ], 400);
        }
    }

    public function editPermissions(Request $request, $id)
    {
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('update-permission')) {
                $student = Student::find($id);
                $studentPermissions = $student->permissions()->get();
                $permissions = Permission::where('guard_name', 'student')->get();
                return view('cms.admin.students.edit-student-permissions', [
                    'student' => $student,
                    'permissions' => $permissions,
                    'studentPermissions' => $studentPermissions
                ]);
            }
        }
        return view('cms.no-content');
    }

    public function updatePermissions(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $request->validate([
            'id' => 'required|exists:students,id',
            'permissions' => 'array',
        ]);

        $student = Student::find($request->get('id'));
        $permissions = Permission::findMany($request->permissions);
        $isSynced = $student->syncPermissions($permissions);
        if ($isSynced) {
            return response()->json(['icon' => 'success', 'title' => 'Student permissions updated successfully'], 200);
        } else {
            return response()->json(['icon' => 'Failed', 'title' => 'Student permissions update failed'], 400);
        }
    }
}

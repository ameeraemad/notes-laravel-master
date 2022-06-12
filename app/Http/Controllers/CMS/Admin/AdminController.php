<?php

namespace App\Http\Controllers\CMS\Admin;

use App\Admin;
use App\Field;
use App\Http\Controllers\Controller;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $admins = Admin::paginate(10);
        return view('cms.admin.admins.index', ['adminsData' => $admins]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
        return view('cms.admin.admins.create');
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
            'email' => 'required:string|email|unique:admins,email',
            'mobile' => 'required|string|numeric|unique:admins,mobile',
            'password' => 'required|string|min:4',
            'gender' => 'required|string|in:Male,Female',
            'account_status' => 'string|in:Active,Blocked'
        ]);

        $admin = new Admin();
        $admin->name = $request->get('name');
        $admin->email = $request->get('email');
        $admin->mobile = $request->get('mobile');
        $admin->gender = $request->get('gender');
        $admin->status = $request->get('status');
        $admin->password = Hash::make($request->get('password'));
        $isSaved = $admin->save();
        if ($isSaved) {
            $admin->assignRole('Admin');
            return response()->json(['icon' => 'success', 'title' => 'Admin created successfully'], 200);
        } else {
            return response()->json(['icon' => 'success', 'title' => 'Admin created successfully'], 400);
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
        $admin = Admin::find($id);
        return view('cms.admin.admins.edit', ['admin' => $admin]);
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
            'id' => 'required|exists:admins,id',
            'name' => 'required|string|min:3',
            'email' => 'required:string|email|unique:admins,email,' . $id,
            'mobile' => 'required|string|numeric|unique:admins,mobile,' . $id,
            'gender' => 'required|string|in:Male,Female',
            'account_status' => 'string|in:Active,Blocked'
        ]);

        $admin = Admin::find($id);
        $admin->name = $request->get('name');
        $admin->email = $request->get('email');
        $admin->mobile = $request->get('mobile');
        $admin->gender = $request->get('gender');
        $admin->status = $request->get('status');
        $isSaved = $admin->save();
        if ($isSaved) {
            $admin->assignRole('Admin');
            return response()->json(['icon' => 'success', 'title' => 'Admin updated successfully'], 200);
        } else {
            return response()->json(['icon' => 'Failed', 'title' => 'Admin update failed'], 400);
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
        $isDestroyed = Admin::destroy($id);
        if ($isDestroyed) {
            return response()->json([
                'icon' => 'success',
                'title' => 'Admin deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'icon' => 'error',
                'title' => 'Admin delete failed'
            ], 400);
        }
    }

    public function editPermissions(Request $request, $id)
    {
        $admin = Admin::find($id);
        $adminPermissions = $admin->permissions()->get();
        $permissions = Permission::where('guard_name', 'admin')->get();
        return view('cms.admin.admins.edit-admin-permissions', [
            'admin' => $admin,
            'permissions' => $permissions,
            'adminPermissions' => $adminPermissions]);
    }

    public function updatePermissions(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $request->validate([
            'id' => 'required|exists:admins,id',
            'permissions' => 'array',
        ]);

        $admin = Admin::find($request->get('id'));
        $permissions = Permission::findMany($request->permissions);
        $isSynced = $admin->syncPermissions($permissions);
        if ($isSynced) {
            return response()->json(['icon' => 'success', 'title' => 'Admin permissions updated successfully'], 200);
        } else {
            return response()->json(['icon' => 'Failed', 'title' => 'Admin permissions update failed'], 400);
        }
    }
}

<?php

namespace App\Http\Controllers\CMS\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllersService;
use App\Notification;
use App\Student;
use App\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    protected $fcmController;

    public function __construct()
    {
        $this->fcmController = new UserFcmTokenController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('read-notifications')) {
                $notifications = Notification::withCount('notificationStatistics')->paginate(10);
                return view('cms.admin.notifications.index', ['notifications' => $notifications]);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('read-notifications')) {
                $notifications = Student::find(auth('student')->user()->id)->notifications()->withCount('notificationStatistics')->paginate(10);
                return view('cms.admin.notifications.index', ['notifications' => $notifications]);
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
        if (ControllersService::checkAuthForPermission('admin', 'create-notification')) {
            $users = User::where('status', 'Active')->get();
            return view('cms.admin.notifications.send_single_notification', ['users' => $users]);
        } else if (ControllersService::checkAuthForPermission('student', 'create-notification')) {
            $notifications = Student::find(auth('student')->user()->id)->users()->where('status', 'Active')->get();
            return view('cms.admin.notifications.send_single_notification', ['notifications' => $notifications]);
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
        $authName = auth('admin')->check() ? 'admin' : 'student';

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|min:5|max:25',
            'sub_title' => 'required|string|min:5|max:50',
            'body' => 'required|string|min:10|max:100',
        ]);

        $notification = new Notification();
        $notification->title = $request->get('title');
        $notification->sub_title = $request->get('sub_title');
        $notification->body = $request->get('body');
        $isSaved = auth($authName)->user()->notifications()->save($notification);

        if ($isSaved) {
            $isStatisticsSaved = $notification->notificationStatistics()->create([
                'user_id' => $request->get('user_id'),
                'send_status' => 'Success',
            ]);
            if ($isStatisticsSaved) {
                return response()->json(['icon' => 'success', 'title' => 'Notification Sent Successfully'], 200);
            } else {
                return response()->json(['icon' => 'error', 'title' => 'Failed to send notification'], 400);
            }
        } else {
            return response()->json([
                'icon' => 'error',
                'title' => 'Failed to send notification...'
            ], 400);
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
        if (ControllersService::checkAuthForPermission('admin', 'read-notifications')) {
            $notificationStatistics = auth('admin')->user()->notifications()->findOrFail($id)->notificationStatistics()->paginate(10);
            return view('cms.admin.notifications.statistics.index', ['notificationStatistics' => $notificationStatistics]);

        } else if (ControllersService::checkAuthForPermission('admin', 'read-notifications')) {
            $notificationStatistics = auth('student')->user()->notifications()->findOrFail($id)->notificationStatistics()->paginate(10);
            return view('cms.admin.notifications.statistics.index', ['notificationStatistics' => $notificationStatistics]);
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
        if (ControllersService::checkAuthForPermission('admin', 'update-notification')) {
            $notification = Notification::findOrFail($id);
            return view('cms.admin.notifications.edit', ['notification' => $notification]);

        } else if (ControllersService::checkAuthForPermission('student', 'update-notification')) {
            $notification = Student::find(auth('student')->user()->id)->notifications()->findOrFail($id);
            return view('cms.admin.notifications.edit', ['notification' => $notification]);
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
        $authName = auth('admin')->check() ? 'admin' : 'student';

        $request->validate([
            'id' => 'required|exists:notifications,id',
            'title' => 'required|string|min:5|max:25',
            'sub_title' => 'required|string|min:5|max:50',
            'body' => 'required|string|min:10|max:100',
        ]);

        $notification = auth($authName)->user()->notifications()->findOrFail($id);
        $notification->title = $request->get('title');
        $notification->sub_title = $request->get('sub_title');
        $notification->body = $request->get('body');
        $isSaved = $notification->save();

        if ($isSaved) {
            return response()->json(['icon' => 'success', 'title' => 'Notification updated successfully'], 200);
        } else {
            return response()->json([
                'icon' => 'error',
                'title' => 'Notification update failed...'
            ], 400);
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
        if (ControllersService::checkAuthForPermission('admin', 'delete-notification')) {
            $isDestroyed = Notification::destroy($id);

        } else if (ControllersService::checkAuthForPermission('admin', 'delete-notification')) {
            $isDestroyed = auth('student')->user()
                ->notifications()
                ->find($id)
                ->delete();
        }

        if ($isDestroyed) {
            return response()->json([
                'icon' => 'success',
                'title' => 'Notification deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'icon' => 'error',
                'title' => 'Notification delete failed'
            ], 400);
        }
    }
}

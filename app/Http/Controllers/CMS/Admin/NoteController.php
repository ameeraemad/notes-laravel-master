<?php

namespace App\Http\Controllers\CMS\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Note;
use App\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('read-notes')) {
                $notes = Note::paginate(10);
                return view('cms.admin.notes.index', ['notes' => $notes]);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('read-notes')) {
                $notes = Note::whereHas('category.user.student', function ($query) {
                    return $query->where('api_uuid', auth('student')->user()->api_uuid);
                })->paginate(10);
                return view('cms.admin.notes.index', ['notes' => $notes]);
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
            if (auth('admin')->user()->hasPermissionTo('create-note')) {
                $categories = Category::all();
                return view('cms.admin.notes.create', ['categories' => $categories]);
            }

        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('create-note')) {
                $categories = auth('student')->user()->categories()->get();
                return view('cms.admin.notes.create', ['categories' => $categories]);
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
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|String|min:5|max:45',
            'details' => 'required|String|min:5|max:100',
        ]);

        $note = new Note();
        $note->category_id = $request->get('category_id');
        $note->title = $request->get('title');
        $note->details = $request->get('details');
        $isSaved = $note->save();
        if ($isSaved) {
            return response()->json(['icon' => 'success', 'title' => 'Note created successfully'], 200);
        } else {
            return response()->json(['icon' => 'success', 'title' => 'Note create failed!'], 400);
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
        $categories = [];
        $note = null;
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('update-note')) {
                $categories = Category::all();
                $note = Note::find($id);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('update-note')) {
                $categories = auth('student')->user()->categories()->get();
                $note = Note::whereHas('category.user.student', function ($query) {
                    return $query->where('api_uuid', auth('student')->user()->api_uuid);
                })->find($id);
            }
        }
        if (!is_null($note)) {
            return view('cms.admin.notes.edit', ['note' => $note, 'categories' => $categories]);
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
        $request->request->add(['note_id' => $id]);
        $request->validate([
            'note_id' => 'required|exists:notes,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|String|min:5|max:45',
            'details' => 'required|String|min:5|max:100',
        ]);

        $note = Note::find($id);
        $note->category_id = $request->get('category_id');
        $note->title = $request->get('title');
        $note->details = $request->get('details');
        $isUpdated = $note->save();
        if ($isUpdated) {
            return response()->json(['icon' => 'success', 'title' => 'Note updated successfully'], 200);
        } else {
            return response()->json(['icon' => 'Failed', 'title' => 'Note update failed'], 400);
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
            if (auth('student')->user()->hasPermissionTo('delete-note')) {
                $isDestroyed = Note::destroy($id);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('delete-note')) {
                $isDestroyed = Note::whereHas('category.user.student', function ($query) {
                    return $query->where('api_uuid', auth('student')->user()->api_uuid);
                })->find($id)->delete();
            }
        }

        if ($isDestroyed) {
            return response()->json([
                'icon' => 'success',
                'title' => 'Note deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'icon' => 'error',
                'title' => 'Note delete failed'
            ], 400);
        }
    }

    public function showUserNotes($userId)
    {
        $notes = [];
        if (auth('admin')->check()) {
            if (auth('admin')->user()->hasPermissionTo('read-notes')) {
                $notes = User::find($userId)->notes()->paginate(10);
                return view('cms.admin.notes.index', ['notes' => $notes]);
            }
        } elseif (auth('student')->check()) {
            if (auth('student')->user()->hasPermissionTo('read-notes')) {
                $user = auth('student')->user()->users()->find($userId);
                if (!is_null($user)) {
                    $notes = $user->notes()->paginate(10);
                    return view('cms.admin.notes.index', ['notes' => $notes]);
                }
            }
        }
        return view('cms.no-content');
    }
}

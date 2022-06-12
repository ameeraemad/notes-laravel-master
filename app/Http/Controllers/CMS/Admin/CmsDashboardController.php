<?php

namespace App\Http\Controllers\CMS\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Note;
use App\Student;
use App\User;
use Illuminate\Http\Request;

class CmsDashboardController extends Controller
{
    //
    public function show()
    {
        $latestTenStudents = [];

        if (auth('admin')->check()) {
            $usersCount = User::count();
            $categoriesCount = Category::count();
            $notesCount = Note::count();
            $studentsCount = Student::count();
            $latestTenStudents = Student::orderBy('created_at', 'DESC')->take(10)->get();
        } elseif (auth('student')->check()) {
            $usersCount = User::where('student_api_uuid', auth('student')->user()->api_uuid)->count();
            $categoriesCount = Student::find(auth('student')->user()->id)->categories()->count();
            $notesCount = Note::whereHas('category.user.student', function ($query) {
                return $query->where('api_uuid', auth('student')->user()->api_uuid);
            })->count();
            $studentsCount = 1;
        }

        return view('cms.admin.dashboard', [
            'usersCount' => $usersCount,
            'categoriesCount' => $categoriesCount,
            'notesCount' => $notesCount,
            'studentsCount' => $studentsCount,
            'students' => $latestTenStudents,
        ]);
    }
}

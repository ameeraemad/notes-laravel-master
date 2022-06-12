<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Mail\PasswordReset;
use App\Mail\StudentRegistration;
use App\Student;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use mysql_xdevapi\SqlStatement;

class StudentAuthController extends Controller
{
    //

    use AuthenticatesUsers;

    protected $guardName = 'student';
    protected $maxAttempts = 4;
    protected $decayMinutes = 2;

    public function __construct()
    {
        $this->middleware('guest:student')->except(['logout', 'showResetPasswordView', 'resetPassword']);
    }

    public function showLoginView()
    {
        return view('cms.student.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string|min:3|max:10',
            'remember_me' => 'string|in:on'
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $rememberMe = $request->remember_me == 'on' ? true : false;

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('student')->attempt($credentials, $rememberMe)) {
            $user = Auth::guard('student')->user();
            if ($user->status == "Active") {
                $this->clearLoginAttempts($request);
                return redirect()->route('cms.admin.dashboard');
            } else {
                Auth::guard('student')->logout();
                return redirect()->guest(route('cms.admin.blocked'));
            }
        } else {
            $this->incrementLoginAttempts($request);
            return redirect()->back()->withInput();
        }
    }

    public function showRegisterView()
    {
        return view('cms.student.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required:string|email|unique:students,email',
            'mobile' => 'required|string|numeric|unique:students,mobile|digits:9',
            'password' => 'required|string|min:4',
        ]);

        $student = new Student();
        $student->name = $request->get('name');
        $student->email = $request->get('email');
        $student->mobile = $request->get('mobile');
        $student->password = Hash::make($request->get('password'));
        $student->api_uuid = Str::uuid()->toString();
        $isSaved = $student->save();
        if ($isSaved) {
            $this->sendRegistrationEmail($student);
            $student->assignRole('Student');
            return $this->login($request);
        } else {
        }
    }

    private function sendRegistrationEmail(Student $student)
    {
        Mail::send(new StudentRegistration($student));
    }

    public function showResetPasswordView()
    {
        return view('cms.student.settings.reset_password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|password:student',
            'new_password' => 'required|string',
            'new_password_confirmation' => 'required|string|same:new_password'
        ], ['current_password.password' => 'Your current password is not correct']);

        $user = Auth::user();
        $user->password = $request->get('new_password');
        $isSaved = $user->save();
        if ($isSaved) {
            return response()->json(['icon' => 'success', 'title' => 'Password changed successfully'], 200);
        } else {
            return response()->json(['icon' => 'error', 'title' => 'Password change failed!'], 400);
        }
    }

    public function showForgetPassword()
    {
        return view('cms.student.auth.forgot-password');
    }

    public function requestNewPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:students,email|email',
        ], ['email.exists' => 'This is email is not registered before']);

        $newPassword = Str::random(8);

        $student = Student::where('email', $request->get('email'))->first();
        $student->password = Hash::make($newPassword);
        $isSaved = $student->save();
        if ($isSaved) {
            $this->sendResetPasswordEmail($student, $newPassword);
            return redirect()->route('cms.student.login_view');
        } else {
        }
    }

    private function sendResetPasswordEmail(Student $student, $newPassword)
    {
        Mail::queue(new PasswordReset($student, $newPassword));
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        return redirect()->guest(route('cms.student.login_view'));
    }
}

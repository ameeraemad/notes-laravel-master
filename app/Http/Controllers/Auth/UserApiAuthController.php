<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Messages;
use App\Http\Controllers\ControllersService;
use App\Mail\NewUserRegistered;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserApiAuthController extends AuthBaseController
{
    //php artisan passport:client --personal
    public function login(Request $request)
    {
        $roles = [
            'email' => 'required|string|email',
            'password' => 'required|min:3',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $user = User::where("email", $request->get('email'))->first();
            if ($user) {
                $this->revokePreviousTokens($user->id);
                if (Hash::check($request->password, $user->password)) {
                    return $this->generateToken($user, 'LOGGED_IN_SUCCESSFULLY');
                } else {
                    return ControllersService::generateProcessResponse(false, 'ERROR_CREDENTIALS');
                }
            } else {
                return ControllersService::generateProcessResponse(false, 'ERROR_CREDENTIALS');
            }
        } else {
            return ControllersService::generateValidationErrorMessage($validator->getMessageBag()->first());
        }
    }

    public function register(Request $request)
    {
        $roles = [
            'first_name' => 'required|string|min:3|max:10',
            'last_name' => 'required|string|min:3|max:10',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|numeric|unique:users|digits:9',
            'password' => 'required|min:3',
            'api_uuid' => 'required|exists:students,api_uuid',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $user = new User();
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->email = $request->get('email');
            $user->mobile = $request->get('mobile');
            $user->password = Hash::make($request->get('password'));
            $user->student_api_uuid = $request->get("api_uuid");
            $user->status = 'Active';
            $isSaved = $user->save();
            if ($isSaved) {
                $student = $user->student;
                Mail::queue(new NewUserRegistered($student, $user));
                return $this->generateToken($user, 'REGISTERED_SUCCESSFULLY');
            } else {
                return ControllersService::generateProcessResponse(false, 'LOGIN_IN_FAILED');
            }
        } else {
            return ControllersService::generateValidationErrorMessage($validator->getMessageBag()->first());
        }
    }

    public function update(Request $request)
    {
        $userId = $request->user('users_api')->id;
        $roles = [
            'first_name' => 'required|string|min:3|max:10',
            'last_name' => 'required|string|min:3|max:10',
            'email' => 'required|string|email|unique:users,email,' . $userId,
            'mobile' => 'required|numeric|digits:9|unique:users,mobile,' . $userId,
        ];
        $validator = Validator::make($request->all(), $roles);

        if (!$validator->fails()) {
            $user = User::find($request->user()->id);
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->email = $request->get('email');
            $user->mobile = $request->get('mobile');
            $isUpdated = $user->save();
            if ($isUpdated) {
                return ControllersService::generateObjectSuccessResponse($user, Messages::getMessage('USER_UPDATED_SUCCESS'));
            } else {
                return ControllersService::generateObjectSuccessResponse($user, Messages::getMessage('USER_UPDATED_FAILED'));
            }
        } else {
            return ControllersService::generateValidationErrorMessage($validator->getMessageBag()->first());
        }
    }

    private function generateToken($user, $message)
    {
        $tokenResult = $user->createToken('Notes-User');
        $token = $tokenResult->accessToken;
        $user->setAttribute('token', $token);
        return response()->json([
            'status' => true,
            'message' => Messages::getMessage($message),
            'object' => $user,
        ]);
    }
}

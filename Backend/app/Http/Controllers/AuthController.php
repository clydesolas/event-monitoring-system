<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    
    public function register(Request $request)
    {
        $data = $request->validate([
            'student_number' => 'required|string|unique:users,student_number',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'course' => 'required|string',
            'sex' => 'required|string',
            'birth_date' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'student_number'=> $data['student_number'],
            'first_name'=> $data['first_name'],
            'middle_name'=> $data['middle_name'],
            'last_name'=> $data['last_name'],
            'course'=> $data['course'],
            'sex'=> $data['sex'],
            'birth_date'=> $data['birth_date'],
            'email'=> $data['email'],
            'password'=> bcrypt($data['password'])
        ]);

        $token = $user->createToken('sanctum-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();
        
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                "message" => "Bad Credentials"
            ], 401);
        }

        if ($user->status === 'ARCHIVED') {
            return response([
                "message" => "You are not currently enrolled."
            ], 401);
        }

        $token = $user->createToken('sanctum-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            "message" => "Log out successfully"
        ];
    }

    public function update(Request $request)
    {
        $user = auth()->user();
    
        $data = $request->validate([
            'first_name' => 'sometimes|required|string',
            'middle_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'sex' => 'sometimes|required|string',
            'birth_date' => 'sometimes|required|string',
            'email' => 'sometimes|required|string|email|unique:users,email,' . $user->id,
        ]);
    
        $user->update($data);
    
        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }
    
    public function changePassword(Request $request)
    {
        // Ensure the user is authenticated
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);
    
        // Verify the current password
        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }
    
        // Update the password
        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);
    
        return response()->json(['message' => 'Password changed successfully']);
    }
    

    public function sendOtpEmail(Request $request)
    {
        // Validate request data (you can add more validation as needed)
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
        ]);

        $email = $request->input('email');
        $otp = $request->input('otp');

        $subject = 'Your OTP for Authentication';
        $message = 'Your OTP is: ' . $otp;

        Mail::raw($message, function ($mail) use ($email, $subject) {
            $mail->to($email)->subject($subject);
        });

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function checkExistence(Request $request)
    {
        $request->validate([
            'student_number' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
        ]);

        $studentNumberExists = User::where('student_number', $request->input('student_number'))->exists();
        $emailExists = User::where('email', $request->input('email'))->exists();

        return response()->json([
            'student_number_exists' => $studentNumberExists,
            'email_exists' => $emailExists,
        ]);
    }

    public function getRegisteredStudentsCount()
    {
        try {
            // Assuming 'status' and 'role' are columns in your 'students' table
            $count = User::where('status', 'ENROLLED')
                             ->where('role', 'user')
                             ->count();

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching student count'], 500);
        }
    }

}

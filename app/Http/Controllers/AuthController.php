<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function showRegister() {
        return view('Auth.register');
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'contact_number' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $validated = $validator->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'password' => $validated['password'],
        ]);

        return redirect()->route('login')->with(['success', "Successfully Created Account!"]);
    }

    public function showLogin() {
        return view('Auth.login');
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        $validated = $validator->validated();

        if(Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return redirect()->route('user-dashboard')->with(['success', "Successfully Login"]);
        }

        return redirect()->back()->with('error', "Login Credentials Incorrect!");
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout Success!');
    }

    public function updateAccount(Request $request)
{
    $user = Auth::user();

    try {
        // Validate the request
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'required_with:current_password', 'confirmed', 'min:8'],
        ]);

        // Update username and email
        $user->name = $validated['username'];
        $user->email = $validated['email'];

        // Update password if provided
        if ($request->filled('current_password') && $request->filled('new_password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Current password is incorrect'
                ], 422);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Account successfully updated!'
        ]);


    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}
}

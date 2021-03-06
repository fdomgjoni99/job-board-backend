<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function registerCompany(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'about' => 'required|min:50|max:5000',
            'location' => 'required|min:3|max:400',
            'profile_image' => 'nullable|file|mimes:jpg,png',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'password_confirmation' => 'required|same:password',
        ]);
        $password = Hash::make($request->password);
        $user = User::make(
            array_merge($request->only(['name', 'email']), [
                'password' => $password,
            ])
        );
        $company = Company::create(
            $request->only(['name', 'about', 'location', 'profile_image'])
        );
        $company->user()->save($user);
        $request->file('profile_image')->storePublicly('profile-images');
        return response()->json($user->with('userable')->first(), 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' =>
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/',
        ]);
        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials!'], 401);
        }
        $token = $request->user()->createToken('');
        return ['token' => $token];
    }
}

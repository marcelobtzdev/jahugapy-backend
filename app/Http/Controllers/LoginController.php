<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = null;
        $userExist = User::where('username', $request->username)->orWhere('activision_id', $request->username)->first();

        if ($userExist) {
            if (!$userExist->active) {
                return response()->json([
                    'success' => 'error', 
                    'message' => 'El usuario no esta validado.'], 
                400);
            };

            $credentials = $request->only('username', 'password');
            $successfulLogin = false;

            $firstAttemp = Auth::attempt($credentials, true);
            
            if ($firstAttemp) {
                $successfulLogin = $firstAttemp;
            } else {
                $successfulLogin = Auth::attempt(['activision_id' => $request->username, ...$request->only('password')], true);
            }

            if (!$successfulLogin) {
                return response()->json([
                    'success' => 'error', 
                    'message' => 'La contraseña es incorrecta.'], 
                400);
            }

            $user = auth()->user();
            $accesToken = $user->createToken($request->device_name)->plainTextToken;
            
            return response()->json([
                'user' => $user, 
                'accessToken' => $accesToken
            ]);
        } else {
            return response()->json([
                'success' => 'error', 
                'message' => 'No se encontró ningún usuario.'], 
            404);
        }
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
  
        return response()->json([
            'success' => 'success', 
            'message' => ''], 
        200);
    }
}

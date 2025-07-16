<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ResponseHelper;
use App\Helpers\UserActivityHelper;
use App\Http\Controllers\Controller;
use App\Models\DeviceVerification;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     try {
    //         $data = $request->validate([
    //             'name' => 'required|max:255',
    //             'email' => 'required|email|unique:users',
    //             'password' => 'required'
    //         ]);

    //         $data['password'] = Hash::make($request->password);

    //         $user = User::create($data);

    //         $token = $user->createToken('appToken')->accessToken;

    //         // return response(['user' => $user, 'token' => $token]);

    //         return response()->json([
    //             'user' => $user,
    //             'token' => $token,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to authenticate. | ' . $e->getMessage(),
    //         ], 401);
    //     }
    // }

    //EMERGENCY EXIT
    public function login(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'identity' => 'required|string', // Bisa email atau username
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cari user berdasarkan email
            $identity = $request->identity;
            $user = User::where('email', $identity)
                ->orWhere('name', $identity)
                ->first();

            // Jika user tidak ditemukan atau password salah
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed | Email atau Password salah.',
                ], 401);
            }

            // Hapus token lama jika ada
            $user->tokens()->delete();

            // Buat token baru dengan Passport
            $token = $user->createToken('AuthToken')->accessToken;

            // Ambil permissions dan roles
            $permissions = $user->getAllPermissions()->pluck('name');
            $roles = $user->getRoleNames();

            // Return response dengan token dan data user
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'area_id' => $user->area_id,
                    // tambahkan field lain yang dibutuhkan
                ],
                'permissions' => $permissions,
                'roles' => $roles,
                'token' => $token,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed | Email atau Password salah.',
                'error' => $th->getMessage() // hapus baris ini di production
            ], 401);
        }
    }


    private function successResponse($user, $token)
    {
        return response()->json([
            'success' => true,
            'token' => ['token' => $token],
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        if (Auth::user()) {
            // UserActivityHelper::logLoginActivity(auth()->user()->uuid, 'User Logout');
            $request->user()->token()->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);
        }
    }

    private function sendNotif($user)
    {
        $admins = User::where('isAdmin', 1)->get();
        // foreach ($admins as $admin) {
        //     Mail::send('email.notif', ['user' => $user], function ($message) use ($admin) {
        //         $message->from('systemkma@bankarthaya.com', 'Administrator');
        //         $message->to($admin->email, 'Admin');
        //         $message->subject('Notifikasi Email');
        //     });
        // }

        return ResponseHelper::successRes('Berhasil', $admins);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RiwayatExport;
use App\Helpers\ResponseHelper;
use App\Helpers\UserActivityHelper;
use App\Http\Controllers\Controller;
use App\Models\DeviceVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function getAllrole()
    {
        try {
            $roles = Role::all();

            return ResponseHelper::successRes('Berhasil mendapatkan data', $roles);
        } catch (\Throwable $th) {
            return ResponseHelper::errorRes('Failed. | ' . $th->getMessage());
        }
    }
    public function getAllUser()
    {
        try {
            $users = User::with('area')->get();

            return ResponseHelper::successRes('Berhasil mendapatkan data', $users);
        } catch (\Exception $e) {
            return ResponseHelper::errorRes($e->getMessage());
        }
    }
    public function addUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:users,name',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'area_id' => 'required|exists:areas,id',
                'role_id' => 'required|integer|in:1,2,3',
            ], [
                'name.required' => 'User name is required.',
                'name.unique' => 'The user name has already been taken.',
                'email.required' => 'Email is required.',
                'email.unique' => 'The email has already been taken.',
                'email.email' => 'The email must have @.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 6 characters.',
                'area_id.required' => 'Area Wajib Di isi',
                'area_id.exists' => 'Area yang dipilih tidak valid.',
                'role_id.required' => 'Role is required.',
                'role_id.in' => 'Role tidak valid.',
            ]);

            switch ($request->role_id) {
                case 1:
                    $roleName = 'superadmin';
                    $permissions = Permission::all();
                    break;
                case 2:
                    $roleName = 'admin';
                    $permissions = Permission::whereNotIn('name', [
                        'create-users',
                        'update-users',
                        'delete-users'
                    ])->get();
                    break;
                case 3:
                default:
                    $roleName = 'user';
                    $permissions = Permission::whereIn('name', [
                        'view-buildings',
                        'view-areas',
                        'view-findings',
                        'view-dashboard'
                    ])->get();
                    break;
            }

            // Buat user baru
            $user = User::create([
                'area_id' => $request->area_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($roleName);

            if ($permissions->isNotEmpty()) {
                $user->givePermissionTo($permissions);
            }

            return ResponseHelper::successRes('Berhasil menambahkan user baru', [
                'user' => $user,
                'role' => $roleName,
                'permissions_count' => $permissions->count()
            ]);
        } catch (ValidationException $e) {
            return ResponseHelper::errorRes('Validation failed', $e->errors());
        } catch (\Exception $e) {
            return ResponseHelper::errorRes('Failed to create user: ' . $e->getMessage());
        }
    }

    public function userPermission($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (!$user) {
                return ResponseHelper::errorRes('User not found');
            }

            $allPermissions = Permission::all();
            $userPermissions = $user->getAllPermissions();
            $unassignedPermissions = $allPermissions->diff($userPermissions);

            $response = [
                'assigned_permissions' => $userPermissions,
                'unassigned_permissions' => $unassignedPermissions,
                'all_permissions' => $allPermissions,
            ];

            return ResponseHelper::successRes('Berhasil mendapatkan data', $response);
        } catch (\Throwable $th) {
            return ResponseHelper::errorRes('Failed to authenticate. | ' . $th->getMessage());
        }
    }
    public function updateUser(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->area_id = $request->area_id;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            if ($request->has('assignedPermissions')) {
                $assignedPermissions = is_string($request->assignedPermissions) ?
                    json_decode($request->assignedPermissions, true) : $request->assignedPermissions;

                $permissionIds = !empty($assignedPermissions) ?
                    collect($assignedPermissions)->pluck('id')->toArray() : [];
                if ($user->hasRole(['superadmin', 'admin', 'user'])) {
                    $userRoles = $user->getRoleNames();
                    $user->syncPermissions($permissionIds);
                } else {
                    $user->syncPermissions($permissionIds);
                }
            }

            return ResponseHelper::successRes('User updated successfully', $user);
        } catch (\Throwable $th) {
            return ResponseHelper::errorRes('Failed to update user. | ' . $th->getMessage());
        }
    }

    public function deleteUser($id)
    {
        try {
            if (!$id || !is_numeric($id)) {
                return ResponseHelper::errorRes('Invalid user ID provided');
            }

            $user = User::with('roles', 'permissions')->find($id);

            if (!$user) {
                return ResponseHelper::errorRes('User not found');
            }

            if (auth()->id() == $user->id) {
                return ResponseHelper::errorRes('You cannot delete your own account');
            }

            if ($user->hasRole('superadmin')) {
                return ResponseHelper::errorRes('Cannot delete superadmin user');
            }

            $deletedUserData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'permissions_count' => $user->permissions->count()
            ];

            $user->roles()->detach();

            $user->permissions()->detach();

            // Alternative: Menggunakan method Spatie
            // $user->syncRoles([]);
            // $user->syncPermissions([]);

            $user->delete();

            return ResponseHelper::successRes('User berhasil dihapus', $deletedUserData);
        } catch (\Exception $e) {
            return ResponseHelper::errorRes('Failed to delete user: ' . $e->getMessage());
        }
    }
}

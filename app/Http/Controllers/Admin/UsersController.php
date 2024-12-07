<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('warehouses')->paginate();
        $warehouses = Warehouse::select('id', 'name')->with('employees')->get();

        return view('admin.users.index', compact('users', 'warehouses'));
    }

    public function save(UserUpdateRequest $request, ?User $user)
    {
        $data = $request->validated();

        if (!$user->id || $request->password) {
            $request->validate([
                'password' => 'required|confirmed|string|min:6',
            ], [
                'password.required' => 'Parol bo\'sh bo\'lishi mumkin emas',
                'password.confirmed' => 'Parol tasdiqlash uchun qayta kiritilgan (noto\'gri takrorlangan)',
                'password.string' => 'Parol xarf yoki raqamlardan tashkil topishi kerak',
                'password.min' => 'Parol 6 xarfdan kam bo\'lmasligi kerak',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->fill($data);
        $user->save();

        if (!$user->isAdmin() && $request->warehouses) {
            $user->warehouses()->detach();

            foreach ($request->warehouses as $warehouseId => $actions) {
                foreach ($actions as $action) {
                    $user->warehouses()->attach($warehouseId, ['action' => $action]);
                }
            }
        }
        return redirect()->back()->with('success', 'Muvaffaqiyatli saqlandi');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $staffs = Staff::paginate(8);
        return view('routine.staff', [
            'staffs' => $staffs,
        ]);
    }
    public function edit($id)
    {
        // dd($id);
        $staff = staff::find($id);
        return view('routine.staffEdit', [
            'staff' => $staff,
        ]);
    }
    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);
        $this->validate($request, [
            'email' => Rule::unique('m_staff')->ignore($staff->id),
        ], [
            'email.unique' => 'このメールアドレスは登録済みです。',
        ]);
        $staff->family_name = $request->family_name;
        $staff->given_name = $request->given_name;
        $staff->email = $request->email;
        $staffs = Staff::get();
        $role_confirm = 1;
        foreach ($staffs as $val) {
            if ($val->role <= 5) {
                $role_confirm++;
            }
        }
        switch ($role_confirm) {
            case 2:
                if ($staff->role == 10 && $request->role ==5) {
                    $staff->role = $request->role;
                    $request->session()->flash('message', $request->family_name.$request->given_name.'さんの情報を編集しました。');
                } else {
                    $request->session()->flash('message', 'admin権限は剥奪できません。');
                }
                break;
            
            default:
                if (is_null($request->role)) {
                    $staff->role = 10;
                } else {
                    $staff->role = $request->role;
                }
                $request->session()->flash('message', $request->family_name.$request->given_name.'さんの情報を編集しました。');
                break;
        }
        // dd($staff->role);
        $staff->save();
        return redirect('/routine/staff');
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'email' => 'unique:m_staff,email',
            'password_confirm' => 'same:password'
        ], [
            'email.unique' => 'このメールアドレスは登録済みです。',
            'same.password_confirm' => '一致しません。',
        ]);
        $staff = new Staff();
        $staff->family_name = $request->family_name;
        $staff->given_name = $request->given_name;
        $staff->email = $request->email;
        $staff->password = bcrypt($request->password);
        if (is_null($request->role)) {
            $staff->role = 10;
        } else {
            $staff->role = $request->role;
        }
        $request->session()->flash('message', $request->family_name.$request->given_name.'さんを登録しました。');
        $staff->save();
        return redirect('/routine/staff');
    }
    public function destroy(Request $request, Staff $staff)
    {
        $staff->delete();
        $request->session()->flash('message', $staff->family_name.$staff->given_name.'さんを削除しました。');
        return redirect('/routine/staff');
    }
}

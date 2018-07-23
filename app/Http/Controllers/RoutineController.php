<?php

namespace App\Http\Controllers;

use App\Staff;
use App\Routine;
use App\Space;
use App\Frequency;
use App\DoneRoutine;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoutineRequest;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use DateTime;

class RoutineController extends Controller
{
    public function index(Request $request)
    {
        $space = Space::get();
        $frequency = Frequency::get();
        $routines = Routine::with('frequency')->get();
        if ($request->select) {
            $routines = $routines->where('m_space_id', $request->select);
        }
        $frequency_show = Frequency::get()->pluck('frequency', 'id');
        // dd($routines);
        return view('routine.index', [
            'routines' => $routines,
            'space' => $space,
            'frequency' => $frequency,

            'frequency_show' => $frequency_show,
        ]);
    }
    public function store(RoutineRequest $request)
    {
        // dd($request->name);
        $routine = new Routine();
        $routine->routine_name = $request->name;
        $routine->m_space_id = $request->space;
        $routine->m_frequency_id = $request->frequency;
        $routine->point = $request->point;
        $routine->save();
        $request->session()->flash('message', '新しく"'.$routine->routine_name.'"を登録しました。');
        return redirect('/routine');
    }
    public function register(Request $request)
    {
        $routine = new DoneRoutine();
        $routine->m_staff_id = Auth::id();
        $routine->routine_name = $request->routine_name;
        $routine->point = $request->point;
        $routine->save();
        $request->session()->flash('message', '実施した"'.$routine->routine_name.'"を登録しました。');
        return redirect('/routine');
    }
    public function edit($id)
    {
        // dd($id);
        $routine = Routine::find($id);
        $space = Space::get();
        $frequency = Frequency::get();
        return view('routine.edit', [
            'routine' => $routine,
            'space' => $space,
            'frequency' => $frequency,
        ]);
    }
    public function update(Request $request, $id)
    {
        $routine = Routine::find($id);
        $routine->routine_name = $request->name;
        $routine->point = $request->point;
        $routine->m_space_id = $request->space;
        $routine->m_frequency_id = $request->frequency;
        $routine->save();
        $request->session()->flash('message', '"'.$request->name.'"を編集しました。');
        return redirect('/routine');
    }
    public function destroy(Request $request, Routine $routine)
    {
        $routine->delete();
        $request->session()->flash('message', '"'.$routine->routine_name.'"を削除しました。');
        return redirect('/routine');
    }
    public function rank()
    {
        if ($_GET) {
            $date = $_GET['date'];
        } else {
            $date = date('Y-m');
        }
        //getの時にm_staff_idカラムのデータも保持させる
        // 実施ルーチンテーブルに登録されてるstaffのみを得点とともに表示する処理
        $done_routine = DoneRoutine::select(DB::raw("m_staff_id,sum(point) as sum"))->where('created_at', 'like', $date.'%')->groupBy('m_staff_id')->orderBy('sum', 'desc')->with('staff')->get(['m_staff_id']);
        // 0ポイントの人をランキングに表示させる処理
        $staffs = Staff::get();
        $zero_family = [];
        // $zero_given = [];
        $nofind;
        foreach ($staffs as $staff) {
            foreach ($done_routine as $val) {
                if ($staff->id == $val->m_staff_id) {
                    if (isset($nofind)) {
                        unset($nofind);
                    }
                    break;
                } else {
                    $nofind = 1;
                }
            }
            if (isset($nofind)) {
                array_push($zero_family, $staff->family_name);
            }
        }
        $min = DoneRoutine::min('created_at');
        $min = date_create($min);
        $min = date(date_format($min, 'Y-m')); //日付型
        $max = DoneRoutine::max('created_at');
        $max = date_create($max);
        $max = date(date_format($max, 'Y-m')); //日付型
        $select_date = [$min];
        $min_while = $min; //日付型にしたい
        
        while ($min_while < $max) {
            $min_while = new DateTime($min_while);
            $min_while = $min_while->modify('+1 months')->format('Y-m');
            $select_date[] = $min_while;
        }
        // dd($select_date);
        return view('routine.ranking', [
            'done_routine' => $done_routine,
            'zero_family' => $zero_family,
            'select_date' => $select_date, //日付型でとんでない
            'max' => $max,
        ]);
    }
    public function rankYear()
    {
        if ($_GET) {
            $date = $_GET['date'];
        } else {
            $date = date('Y');
        }
        //getの時にm_staff_idカラムのデータも保持させる
        // 実施ルーチンテーブルに登録されてるstaffのみを得点とともに表示する処理
        $done_routine = DoneRoutine::select(DB::raw("m_staff_id,sum(point) as sum"))->where('created_at', 'like', $date.'%')->groupBy('m_staff_id')->orderBy('sum', 'desc')->with('staff')->get(['m_staff_id']);
        // 0ポイントの人をランキングに表示させる処理
        $staffs = Staff::get();
        $zero_family = [];
        // $zero_given = [];
        $nofind;
        foreach ($staffs as $staff) {
            foreach ($done_routine as $val) {
                // dd($staff->id, $val->m_staff_id);
                if ($staff->id == $val->m_staff_id) {
                    if (isset($nofind)) {
                        unset($nofind);
                    }
                    break;
                } else {
                    $nofind = 1;
                }
            }
            if (isset($nofind)) {
                array_push($zero_family, $staff->family_name);
                // array_push($zero_given, $staff->given_name);
            }
        }
        $min = DoneRoutine::min('created_at');
        $min = date_create($min);
        $min = date(date_format($min, 'Y')); //日付型
        $max = DoneRoutine::max('created_at');
        $max = date_create($max);
        $max = date(date_format($max, 'Y')); //日付型
        $select_date = [$min];
        $min_while = $min; //日付型にしたい
        
        while ($min_while < $max) {
            $min_while = new DateTime($min_while);
            $min_while = $min_while->modify('+1 years')->format('Y');
            $select_date[] = $min_while;
        }
        return view('routine.ranking-year', [
            'done_routine' => $done_routine,
            'zero_family' => $zero_family,
            'select_date' => $select_date, //日付型でとんでない
            'max' => $max,
        ]);
    }
    public function rankDate(Request $request)
    {
        if ($_GET) {
            // dd($_GET);
            $from = $_GET['from'];
            $to = $_GET['to'];
            $done_routine = DoneRoutine::select(DB::raw("m_staff_id,sum(point) as sum"))->wherebetween('created_at', [$from, $to])->groupBy('m_staff_id')->orderBy('sum', 'desc')->with('staff')->get(['m_staff_id'])->toArray();
            if ($from >= $to) {
                $request->session()->flash('message', '日付の設定が正しくありません。');
            }
            $staffs = Staff::get();
            $zero_family = [];
            // $zero_given = [];
            $nofind;
            foreach ($staffs as $staff) {
                foreach ($done_routine as $val) {
                    if ($staff->id == $val->m_staff_id) {
                        if (isset($nofind)) {
                            unset($nofind);
                        }
                        break;
                    } else {
                        $nofind = 1;
                    }
                }
                if (isset($nofind)) {
                    array_push($zero_family, $staff->family_name);
                }
            }
            return view('routine.ranking-date', [
                'done_routine' => $done_routine,
                'zero_family' => $zero_family,
                'from' => $from,
                'to' => $to,
            ]);
        } else {
            return view('routine.ranking-date');
        }
        // //getの時にm_staff_idカラムのデータも保持させる
        // // 実施ルーチンテーブルに登録されてるstaffのみを得点とともに表示する処理
        // // 0ポイントの人をランキングに表示させる処理
        
        // $min = DoneRoutine::min('created_at');
        // $min = date_create($min);
        // $min = date(date_format($min, 'Y-m')); //日付型
        // $max = DoneRoutine::max('created_at');
        // $max = date_create($max);
        // $max = date(date_format($max, 'Y-m')); //日付型
        // $select_date = [$min];
        // $min_while = $min; //日付型にしたい
        
        // while ($min_while < $max) {
        //     $min_while = new DateTime($min_while);
        //     $min_while = $min_while->modify('+1 months')->format('Y-m');
        //     $select_date[] = $min_while;
        // }
        // dd($select_date);
    }
    public function staff()
    {
        $staff = Staff::get();
        return view('routine.staff', [
            'staff' => $staff,
        ]);
    }
    public function staffEdit($id)
    {
        // dd($id);
        $staff = staff::find($id);
        return view('routine.staffEdit', [
            'staff' => $staff,
        ]);
    }
    public function staffUpdate(Request $request, $id)
    {
        $staff = Staff::find($id);
        $staff->family_name = $request->family_name;
        $staff->given_name = $request->given_name;
        $staff->email = $request->email;
        if (is_null($request->role)) {
            $staff->role = 10;
        } else {
            $staff->role = $request->role;
        }
        // dd($staff->role);
        $staff->save();
        $request->session()->flash('message', $request->family_name.$request->given_name.'さんの情報を編集しました。');
        return redirect('/routine/staff');
    }
    public function create(Request $request)
    {
        $staff = new Staff();
        $staff->family_name = $request->family_name;
        $staff->given_name = $request->given_name;
        $staff->email = $request->email;
        $staff->password = bcrypt($request->password);
        $staff->role = $request->role;
        $request->session()->flash('message', $request->family_name.$request->given_name.'さんを登録しました。');
        $staff->save();
        return redirect('/routine/staff');
    }
}

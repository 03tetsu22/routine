<?php

namespace App\Http\Controllers;

use App\Staff;
use App\Routine;
use App\Space;
use App\Point;
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
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function home()
    {
        $id = Auth::id();
        $today = date('Y-m');
        $score_month = DoneRoutine::select(DB::raw("m_staff_id,sum(point) as sum"))->where('created_at', 'like', $today.'%')->where('m_staff_id', $id)->get();
        $today = date('Y');
        $score_year = DoneRoutine::select(DB::raw("m_staff_id,sum(point) as sum"))->where('created_at', 'like', $today.'%')->where('m_staff_id', $id)->get();
        return view('routine.home', [
            'score_month' => $score_month,
            'score_year' => $score_year,
        ]);
    }
    public function index(Request $request)
    {
        $space = Space::get();
        $point = Point::orderBy('point', 'asc')->get();
        $frequency = Frequency::get();
        $routines = Routine::with('frequency')->with('space')->with('pt')->get();
        if ($request->select) {
            $routines = $routines->where('m_space_id', $request->select);
        }
        $today = date('Y-m-d', strtotime('+1 day', time()));
        $weekago = new DateTime($today);
        $weekago = $weekago->modify('-8 days')->format('Y-m-d');
        $weekdone = DoneRoutine::wherebetween('created_at', [$weekago, $today])->get();
        return view('routine.index', [
            'routines' => $routines,
            'space' => $space,
            'point' => $point,
            'frequency' => $frequency,
            'weekdone' => $weekdone,
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
        $routine->space = $request->space;
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
        $point = Point::get();
        $frequency = Frequency::get();
        return view('routine.edit', [
            'routine' => $routine,
            'space' => $space,
            'point' => $point,
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
    public function history(Request $request)
    {
        $staffs = Staff::get();
        if ($request->id) {
            $select_staff = $request->id;
        } else {
            $select_staff = Auth::id();
        }
        $history = DoneRoutine::where('m_staff_id', $select_staff)->orderBy('created_at', 'desc')->with('pt')->paginate(10);
        return view('/routine/history', [
            'staffs' => $staffs,
            'select_staff' => $select_staff,
            'history' => $history,
        ]);
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
        $mes = 1;
        if ($_GET) {
            $from = $request->from;
            $to = $request->to;
            if ($from && $to && $from == $to) {
                $done_routine = DoneRoutine::select(DB::raw("m_staff_id,sum(point) as sum"))->where('created_at', 'like', $from.'%')->groupBy('m_staff_id')->orderBy('sum', 'desc')->with('staff')->get(['m_staff_id']);
            } else {
                $done_routine = DoneRoutine::select(DB::raw("m_staff_id,sum(point) as sum"))->wherebetween('created_at', [$from, $to])->groupBy('m_staff_id')->orderBy('sum', 'desc')->with('staff')->get(['m_staff_id']);
            }
            if ($from > $to || $done_routine->isEmpty()) {
                // $message = $request->session()->flash('message', '日付の設定が正しくありません。');
                $mes = '日付の設定が正しくありません。';
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
                'mes' => $mes,
            ]);
        } else {
            return view('routine.ranking-date', [
                'mes' => $mes,
            ]);
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
        $staffs = Staff::paginate(8);
        return view('routine.staff', [
            'staffs' => $staffs,
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
    public function staffDestroy(Request $request, Staff $staff)
    {
        $staff->delete();
        $request->session()->flash('message', $staff->family_name.$staff->given_name.'さんを削除しました。');
        return redirect('/routine/staff');
    }
    public function data(Request $request)
    {
        $space = Space::get();
        $point = Point::orderBy('point', 'asc')->get();
        $frequency = Frequency::get();
        return view('/routine/data', [
            'space' => $space,
            'point' => $point,
            'frequency' => $frequency,
        ]);
    }
    public function createSpace(Request $request)
    {
        $this->validate($request, [
            'space' => 'required|max:10',
        ], [
            'space.required' => '入力してください',
            'space.max' => '最大10文字です',
        ]);
        $space = new Space();
        $space->space = $request->space;
        $space->save();
        $request->session()->flash('message', 'ルーチンスペースに"'.$space->space.'"を追加しました。');
        return redirect('/routine/data');
    }
    public function destroySpace(Request $request, Space $space)
    {
        $space->delete();
        $request->session()->flash('message', 'ルーチンスペースの"'.$space->space.'"を削除しました。');
        return redirect('/routine/data');
    }
    public function createPoint(Request $request)
    {
        $this->validate($request, [
            'point' => 'required|max:10|numeric',
        ], [
            'point.required' => '入力してください',
            'point.max' => '桁が大きすぎます',
            'point.numeric' => '数字を入力してください',
        ]);
        $point = new Point();
        $point->point = $request->point;
        $point->save();
        $request->session()->flash('message', 'ポイントに"'.$point->point.'pt"を追加しました。');
        return redirect('/routine/data');
    }
    public function destroyPoint(Request $request, Point $point)
    {
        $point->delete();
        $request->session()->flash('message', 'ポイントの"'.$point->point.'pt"を削除しました。');
        return redirect('/routine/data');
    }
    public function createFrequency(Request $request)
    {
        $this->validate($request, [
            'frequency' => 'required|max:10',
        ], [
            'frequency.required' => '入力してください',
            'frequency.max' => '最大10文字です',
        ]);
        $frequency = new Frequency();
        $frequency->frequency = $request->frequency;
        $frequency->save();
        $request->session()->flash('message', '目安頻度に"'.$frequency->frequency.'"を追加しました。');
        return redirect('/routine/data');
    }
    public function destroyFrequency(Request $request, Frequency $frequency)
    {
        $frequency->delete();
        $request->session()->flash('message', '目安頻度の"'.$frequency->frequency.'"を削除しました。');
        return redirect('/routine/data');
    }
}

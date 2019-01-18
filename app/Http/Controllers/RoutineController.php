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
use Illuminate\Validation\Rule;

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
        $routines = Routine::with('frequency')->with('space')->with('pt');
        if ($request->select) {
            $routines = $routines->where('m_space_id', $request->select);
        }
        $routines = $routines->paginate(7) ->appends($request->only(['select']));
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
        $point = Point::orderBy('point', 'asc')->get();
        $frequency = Frequency::get();
        return view('routine.edit', [
            'routine' => $routine,
            'space' => $space,
            'point' => $point,
            'frequency' => $frequency,
        ]);
    }
    public function update(RoutineRequest $request, $id)
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
        $history = DoneRoutine::where('m_staff_id', $select_staff)->orderBy('created_at', 'desc')->paginate(10)->appends($request->only(['id']));
        return view('/routine/history', [
            'staffs' => $staffs,
            'select_staff' => $select_staff,
            'history' => $history,
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

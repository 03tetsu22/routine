<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DoneRoutine;
use App\Staff;
use DB;

class RankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            if (!$done_routine->isEmpty()) {
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
            } else {
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
    }
}

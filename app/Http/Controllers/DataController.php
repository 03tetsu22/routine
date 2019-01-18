<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Space;
use App\Point;
use App\Frequency;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
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
        // $request->space = trim(mb_convert_kana($request->space, "s"));
        $this->validate($request, [
            'space' => 'required|max:10',
        ], [
            'space.required' => '入力してください',
            'space.max' => 'ルーチンスペースは10文字以内で入力してください。',
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
            'frequency.max' => '目安頻度は10文字以内で入力してください。',
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

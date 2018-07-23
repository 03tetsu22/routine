<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoutineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'name' => 'required|max:255',
        'point' => 'required',
        'space' => 'required',
        'frequency' => 'required',
        ];
    }
    public function messages()
    {
        return[
        'name.required' => "ルーチン名は必須項目です。",
        "name.max" =>"２５５文字以内で入力してください。",
        'point.required' => "ポイントは必須項目です。",
        'space.required' => "スペースは必須項目です。",
        'frequency.required' => "目安頻度は必須項目です。",
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticlesRequest extends FormRequest
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
            //
            'title' => ['required'],
            'content' => ['required', 'min:10'],
            //'tags' => 'required|array'와 같다.
            //'tags' => ['required', 'array'],
            'files' => ['array'],
            //배열 유효성 검사 구문
            'files.*'=>['mimes:jpg,png,zip,tar', 'max:30000']
        ];
    }
    public function messages()
    {
        return [
            'required' => '제목은 필수 입력 사항입니다.',
            'min' => ':attribute은(는)최소 :min글자 이상이 필요합니다.'
        ];
    }
    public function attribute()
    {
        return [
            'title' => '제목',
            'content' => '본문'
        ];
    }
}

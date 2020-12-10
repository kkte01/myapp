<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentsRequest extends FormRequest
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
            'content'=> ['required', 'min:10'],
            //form data로 넘어온 parent_id 필드 값은 comment table에 있는 값이어야 한다.
            //예를 들어 답글을 쓰는 도중 그 원본의 글이 삭제될 수도 있기 때문에
            'parent_id'=>['numeric', 'exists:comments,id']
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ripple;

class RippleController extends Controller
{
    //ripple select
    public function select(Request $request){
        $ripples = DB::table('ripples')->where('ref_board_num',$request->id)->limit(3)->get();
        $ripple_arr = array();
        $row_array =array();
        
        foreach($ripples as $ripple){
            $row_array['id'] = $ripple['id'];
            $row_array['ref_num'] = $ripple['ref_board_num'];
            $row_array['content'] = $ripple['content'];
            $row_array['writer'] = $ripple['writer'];
            $row_array['created_at'] = $ripple['createrd_at'];

            array_push($ripple_arr, $row_array);
        }
        return response()->json($ripple_arr);
    }
    
    //ripple insert
    public function store(Requst $request){
        //request 객체를 통해 메모 내용을 가져온다.
        //사용자 정의 에러메세지
        $messages = [
            'writer.required' => "제목이 빈칸이거나 50자 이상입니다.",
            'content.required' =>"내용이 빈칸이거나 50자 이상입니다."
        ];
        $rules =[
            'content' =>['required', 'max:500','required','min3'],
            'writer' => ['required', 'max:50']
        ];

        //validate method를 통해 유효성 검사 진행 (500자가 넘어갈 경우 에러 반환 데이터 삽입 x)
        //2번째 인자로 메세지 커스텀이 가능하다.
        $ripple = $request->validate([
            'content' => 'required:max:500',
            'writer' => 'required:max:50'
        ],$messages);
        
        //board table insert data
        Ripple::create($ripple);

        $current_rp = DB::table('ripples')-lastest()->limit(1)->get();

        select($request->id);
    }
}

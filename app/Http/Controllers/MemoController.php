<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
class MemoController extends Controller
{
    //웹 최초 진입시 처리
    public function index(){
        //memos table에서 메모 생성 날짜 기준 내림차순으로 정렬해 가져온다.
        //쿼리문을 바로 실행가능
        $memos = Memo::orderBy('created_at', 'desc')->get();
        //index view 와 가져온 메모 데이터를 렌더링해 브라우저에 출력
        return view('layouts.memo',['memos'=> $memos]);
    }
    //create 요청을 받을 시
    public function create(){
        return view('layouts.create');
    }
    //create view에서 메모 삽입 요청시 처리
    public function store(Request $request){
        //request 객체를 통해 메모 내용을 가져온다.
        //validate method를 통해 유효성 검사 진행 (500자가 넘어갈 경우 에러 반환 데이터 삽입 x)
        $content = $request->validate(['content' => 'required:max:500']);
        //$content = Validate($request->content, ['content'=> ['required','max:500']]);
        
        //Memos table insert data
        Memo::create($content);
        
        //삽입이 끝나면 memo 메서드로 리다이렉트
        return redirect()->route('memo');
    }
    //메모 수정 요청
    public function edit(Request $request){
        //request 객체를 통해 수정하고 싶은 메모의 아이디 값을 얻는다.
        $memo = Memo::find($request->id);

        //edit view 와 해당 메모를 렌더링, 브라우저 출력
        return view('layouts.edit', ['memo'=> $memo]);
    }
    //edit view에서 수정된 메모를 적용하는 요청
    public function update(Request $request){
        //memo 테이블에서 요청받은 id값의 데이터를 호출
        $memo = Memo::find($request->id);

        //메모내용이 500자 이상인지 확인
        $content = $request->validate(['content' =>'required:max:500']);
        //$content = Validate($request->content, ['content'=> ['required','max:500']]);
        //수정된 메모를 테이블에 적용하고 save 한다.
        $memo ->fill($content)->save();

        //memo 로 다시 리다이렉트
        return redirect()->route('memo');
    }
    //memo delete
    public function delete(Request $request){
        //id를 통해 삭제할 row 를 찾는다.
        $memo = Memo::find($request->id);
        //row 삭제
        $memo->delete();
        //memo로 리다이렉트
        return redirect()->route('memo');
    }
}
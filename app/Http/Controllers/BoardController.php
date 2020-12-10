<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\Ripple;
use Illuminate\Support\Facades\DB;
class BoardController extends Controller
{
    //페이지 생성해보기
    //메인
    public function index(Request $request){
      //view 에서 넘어온 현재페이지의 파라미터 값
      $pageNum = $request->input('page');
      //한페이지 당 보여줄 글 개수
      $VIEWLIST = 5;
      //전체 페이지중 표시될 페이지 개수
      $VIEWPAGELIST = 5;
      //page가 있을 경우 그대로 이용 없을 경우 1로 지정
      $pageNum = (isset($pageNum) ? $pageNum : 1);
      //전체 게시글 개수
      $totalCount = Board::count();
      //전체 페이지 개수
      $totalPage = ceil($totalCount/$VIEWLIST);
      
      //페이지 그룹 번호
      $pageGroup = ceil($pageNum/$VIEWLIST);
      //페이지 시작
      $startPage = (($pageGroup-1)*$VIEWPAGELIST)+1;
      //페이지 그룹 내 마지막 페이지 번호
      $endPage = $startPage+$VIEWPAGELIST-1;
      //첫 게시판글 번호
      $startNum = ($pageNum-1)*$VIEWLIST;
      $number = $startNum+1;

      //페이지 그룹이 마지막일 때 마지막 페이지 번호
      if($endPage >= $totalPage){
          $endPage = $totalPage;
      }
      //페이지관련 정보를 담을 배열선언
      $pages = [
          'pageNum' => $pageNum,
          'startNum' => $startNum,
          'VIEWLIST' => $VIEWLIST,
          'VIEWPAGELIST' => $VIEWPAGELIST,
          'pageGroup' => $pageGroup,
          'startPage' => $startPage,
          'endPage' => $endPage,
          'totalCount' => $totalCount,
          'totalPage' => $totalPage
        ];
      //boards table 값을 생성일자 내림차순으로 가져온다.
      $boards = Board::latest()->paginate($VIEWLIST);
      //board 폴더 안 list 템플릿에 값을 전달
      return view('board.list', compact('boards','pageNum','startNum','VIEWLIST','VIEWPAGELIST','pageGroup','startPage','endPage','totalCount','totalPage','number'));
    }
    //create 입력 시
    public function create(){
        return view('board.create');
    }
    //게시글 삽입하는 할 때 호출
    public function store(Request $request){
        //request 객체를 통해 메모 내용을 가져온다.
        //사용자 정의 에러메세지
        $messages = [
            'title.required' => "제목이 빈칸이거나 50자 이상입니다.",
            'content.required' =>"내용이 빈칸이거나 50자 이상입니다."
        ];
        $rules =[
            'title' =>['required', 'max:50','required','min3'],
            'content' =>['required', 'max:500','required','min3'],
            'writer' => ['required', 'max:50']];
        //validate method를 통해 유효성 검사 진행 (500자가 넘어갈 경우 에러 반환 데이터 삽입 x)
        //2번째 인자로 메세지 커스텀이 가능하다.
        $board = $request->validate([
            'title' => 'required:min:3',
            'title' => 'required:max:50',
            'content' => 'required:max:500',
            'writer' => 'required:max:50'
        ],$messages);
        
    
        
        //board table insert data
        Board::create($board);
        
        //삽입이 끝나면 memo 메서드로 리다이렉트
        return redirect()->route('board_list');
    }
    //게시글 수정 폼으로 보내기 num로 호출
    public function edit(Request $request){

        $board = Board::find($request->id);

        //아이디 값 바인딩해 view 호출
        return view('board.edit', ['board' => $board]);
    }
    //게시글 수정하기
    public function update(Request $request){
        //num으로 찾기
        $board = Board::find($request ->id);
        //유효성 검사
        $up_con = $request->validate([
            'content' => 'required:max:500',
            'title' => 'required:max:50'
        ]);
        //저장하기
        $board->fill($up_con)->save();

        return redirect()->route('board_list');
    }
    //게시글 삭제
    public function delete(Request $request){
        //id으로 찾기
        $board = Board::find($request->id);
        //row 삭제
        $board->delete();
        return redirect()->route('board_list');
    }
    //게시글 보여주기
    public function view(Request $request){
        //id 으로 찾기
        $board = Board::find($request->id); 
        
        //값을 넘겨주기
        return view('board.view', compact('board'));
    }
}

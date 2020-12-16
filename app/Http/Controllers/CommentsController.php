<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Requests\CommentsRequest;
use Illuminate\Validation\ValidationException;

class CommentsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store(CommentsRequest $request, Article $article){
        //array_merge 폼으로 부터 받은 사용자 데이터 와 사용자 아이디를 합친다.
        //인자로 받은 배열에 같은 키가 있으면 나중에 받은 배열의 키 값을 사용한다.
        //ex) array_merge(['foo'=> 'bar'],['foo'=> 'baz']); 는 ['foo' =>'baz']를 반환한다.
        $comment = $article->comments()->create(array_merge(
            $request->all(),
            ['user_id'=> $request->user()->id]
        ));
        //이메일 알림을 위한 이벤트 설정
        event(new \App\Events\CommentsEvent($comment));

        // /articles/{articles}#comment={comments}를 반환
        //댓글마다 HTML id를 부여했기 때문에 페이즈를 로드한 후 해당 아이디로 화면을 자동 스크롤 한다. ㅈ\
        //즉 작성한 댓글을 보여준다.
        return redirect(route('articles.show', $article->id).'#comment_'.$comment->id);
    }
    //28.3.4 댓글 수정과 삭제 요청 처리 p.308

    //수정요청
    public function update(CommentsRequest $request, Comment $comment){
        $comment->update($request->all());
        return redirect(route('articles.show', $comment->commentable->id).'#comment_'.$comment->id);
    }
    //삭제요청
    public function destroy(Comment $comment){
        if($comment->replies()->count() > 0){
            $comment->delete();
        }else{
            $comment->votes()->delete();
            $comment->forceDelete();
        }
        return response()->json([],204);
    }

    //투표에 관한 함수 code 28-31
    public function vote(Request $request, Comment $comment){

        try {
            $this->validate($request, [
                'vote' => 'required|in:up,down',
            ]);
        } catch (ValidationException $e) {
        }

        if ($comment->votes()->whereUserId($request->user()->id)->exists()) {
            return response()->json(['error' => 'already_voted'], 409);
        }

        $up = $request->input('vote') == 'up';

        $comment->votes()->create([
            'user_id'  => $request->user()->id,
            'up'       => $up,
            'down'     => ! $up,
            'voted_at' => Carbon::now()->toDateTimeString(),
        ]);

        return response()->json([
            'voted' => $request->input('vote'),
            'value' => $comment->votes()->sum($request->input('vote')),
        ], 201, [], JSON_PRETTY_PRINT);
    }
}

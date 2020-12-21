<?php

namespace App\Http\Controllers;

use App\Events\ModelChanged;
use App\Models\Tag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use \App\Models\Article;
use \App\Models\User;
use \App\Http\Requests\ArticlesRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;

class ArticlesController extends Controller implements Cacheable
{
    //이것을 이용 사용자 인증 로그인한 사람만 작성,수정,삭제 가능하도록
    //그러나 로그인하지 않고 GET /articles/create URL 열 시 또 다른오류 발생 auth 미들웨어가 리디렉션하려는 GET /login 라우트가없어서 이다.
    //그렇기에 app/Exceptions/Handler.php에서 수정을 한다.
    public function __construct(){
        parent::__construct();
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    public function cacheTags(): string
    {
        // TODO: Implement cacheTags() method.
        return 'articles';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param null $slug
     * @return Application|Factory|View|Response
     */
    public function index(Request $request, $slug = null)
    {
        //p.340 캐시 키 만들기
        $cacheKey = cache_key('articles.index');
        //$slug 변수값이 있을 때와 없을 때의 쿼리를 분석한다.
        $query = $slug
        ? Tag::whereSlug($slug)->firstOrFail()->articles()
        //? DB::table('tags')->where('slug', $slug)->firstOrFail()->articles()
        : new Article;
       $query = $query->orderBy(
           $request->input('sort','created_at'),
           $request->input('order','desc')
       );

       if($keyword = $request->input('q')){
           $raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
           $query = $query->whereRaw($raw, [$keyword]);
       }

        //$articles = $query->latest()->paginate(3);
        $articles = $this->cache($cacheKey, 5,$query,'paginate',3);
        //compact 배열로 값을 넘긴다.
        //p 382 code32-6
        //return view('articles.index', compact('articles'));
        return $this->respondCollection($articles, $cacheKey);

    }

    protected function respondCollection(LengthAwarePaginator $articles, $cacheKey)
    {
        return view('articles.index', compact('articles','cacheKey'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        //return __METHOD__ . '은 Article 컬렉션을 만들기 위한 폼을 담은 뷰를 반환';
        //null object 패턴을 피하기 위해 더미 객체를 바인딩해 보낸다.
        $article = new Article;
        return view('articles.create', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticlesRequest $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    //사용자 정의 Request를 작성했을 경우 매개변수 클래스를 변경해준다.
    //p.116
    public function store(ArticlesRequest $request)
    {
        //
        //dd($request);
        //return __METHOD__.'은 사용자가 입력한 폼 데이터로 새로운 Article 컬렉션을 만듭니다.';
        //p.110 컨트롤러 로직 추가
        // $rules = [
        //     //유효성 검사
        //     'title'=> ['required'],
        //     'content'=> ['required', 'min:10']
        // ];

        // $message=[
        //     'title.required' => '제목은 필수 입력 항목입니다.',
        //     'content.required' => '본문은 필수 입력 항목입니다.',
        //     'content.min' => '본문은 최소 :min 글자 이상이 필요합니다.',
        // ];
        //3번째 인자로 사용자 정의 오류메세지를 지정 가능
        //$validator = \Validator::make($request->all(), $rules, $message);
        //트레이트 메서드 이용
        //$this->Validate($request, $rules, $message);

        // if($validator->fails()){
        //     //withInput 세션에 값을 저장해놓는다. redirect 시 old를 이용해 찾을 수 있다.
        //     return back()->withErrors($validator)->withInput();
        // }
       //$article = User::whereEmail($request->email)->articles()->create($request->all());
       //dd($request);


        //p. 330 코드 29-16
        $payload = array_merge($request->all(),[
            'notification'=> $request->has('notification')
        ]);

        //Illuminate\Http\Request $request 인스턴스는 로그인한 사용자 정보를 이미 가지고 있다.
       //게다가 auth미들웨어는 로그인하지 않은 사용자가 이 메서드에 들어오는것을 막아주므로 nullpoint 예외로부터도 안전하다.
       //$article = $request->user()->articles()->create($payload);

       //p.384 code 32-8
        $article = User::find(1)->articles()->create($payload);

        if(!$article){
            //dd($article);
            return back()->with('flash_message', '글이 저장되지 않았습니다.')->withInput();
            //p.119 이벤트 시스템
            //dump('event 던집니다.');
            //event('article.created',[$article]);
            //dump('event 던졌습니다.');
        }
        $article->tags()->sync($request->input('tags'));
        //event(new \App\Events\ArticlesEvent($article));

        //파일 저장하기
        if($request->hasFile('files')){
            $files = $request->file('files');
            foreach ($files as $file){
                //test_image폴더로  $file을 올린다.
                $savePath = file_upload('test_image', $file);
                $article->attachments()->create([
                    'filename'=> $savePath,
                    'bytes'=> $file->getSize(),
                    'mime'=> $file->getClientMimeType()
                ]);
            }
        }
        event(new ModelChanged(['articles']));
        //return redirect()->route('articles.index')->with('flash_message', '작성하신 글이 저장되었습니다.');
        return $this->respondCreate($article);
    }

    protected function respondCreate(Article $article)
    {
        return redirect(route('articles.index', $article->id));
    }

    /**
     * Display the specified resource.
     *
     * @param Article $article
     * @return Application|Factory|View|Response
     */
    public function show(Article $article)
    {
        if (!is_api_domain()){
            $article->view_count +=1;
            $article->save();
        }
        //코멘트 값 추가
        $comments = $article->comments()->with('replies')->withTrashed()->whereNull('parent_id')->latest()->get();

        //return view('articles.show', compact('article', 'comments'));
        return $this->respondInstance($article, $comments);
    }
    /**
     * @param Article $article
     * @param Collection $comments
     * @return Factory|\Illuminate\View\View
     */
    protected function respondInstance(Article $article, Collection $comments)
    {
        return view('articles.show', compact('article', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Article $article)
    {
        //
        //return __METHOD__ . '은 다음 기본키를 가진 Ariticle 모델을 수정하기 위한 폼을 담은 뷰를 반환합니다.' .$id;
        // app/provider/AuthServiceProvider에서 인가처리한것을 이용한다.
        $this->authorize('update', $article);

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArticlesRequest $request
     * @param $article
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(ArticlesRequest $request, $article)
    {
        //
        //return __METHOD__ . '은 사용자의 입력한 폼데이터로 다음 기본 키를 가진 Ariticle 모델을 수정합니다.' .$id;
        //수정한 글의 모든내용 업데이트하는 함수
        $article->update($request->all());
        $article->tags()->sync($request->input('tags'));
        return redirect(route('articles.show', $article->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Article $article): \Illuminate\Http\JsonResponse
    {
        //
        //return __METHOD__ . '은 다음 기본 키를 가진 Article  모델을 삭제합니다.' .$id;

        // app/provider/AuthServiceProvider에서 인가처리한것을 이용한다.
        $this->authorize('delete', $article);
        //게시판 글 삭제
        $article->delete();
        //204 = no content
        return response()->json([], 204);
    }
}

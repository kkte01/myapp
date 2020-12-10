<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome.welcome');
// });

//Route::pattern('foo','[0-9a-zA-Z가-힣]{3}');
// //첫번째 방법 with 함수로 데이터를 바인딩해 보내기
// Route::get('/', function () {
//     return view('welcome.welcome')->with('name','foo');
// });
//두번째 방법 view 함수의 두번째 인자로 넣는방법
// Route::get('/',function(){
//     $items = ['apple','banana','grape'];

//     return view('welcome2', ['items' => $items]);
// });

// Route::get('/{foo?}/{boo?}', function ($foo,$boo) {
//     //return $foo;
//     return view('welcome2',[
//         'name' => $foo,
//         'greeting'=>$boo
//     ]);
// });

// Route::get('/',['as' => 'home',
//     function(){
//         return '제 이름은 "home" 입니다.';
//     }]
// );

// Route::get('/home',function(){
//     return redirect(route('home'));
// });

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\PasswordsController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\CommentsController;
//Route::get('/','WelcomeController@index');
//8버전은 위와 같이 하면 안되고 밑에 것을 이용해야한다.
Route::get('/', [WelcomeController::class, 'index'])->name('/');

//또는 경로 직접입력
use App\Http\Controllers\ArticlesController;
Route::resource('articles','App\Http\Controllers\ArticlesController');
//사용자 인증 재구성 p 215
//로그인 루트 재설정
Route::get('login',[SessionsController::class, 'create'])->name('login');
// 사용자 가입
Route::get('auth/register',[UsersController::class, 'create'])->name('users.create');
Route::post('auth/register',[UsersController::class, 'store'])->name('users.store');
Route::get('auth/confirm/{code}',[UsersController::class, 'confirm'])->name('users.confirm');

//사용자 인증
Route::get('auth/login',[SessionsController::class, 'create'])->name('sessions.create');
Route::post('auth/login',[SessionsController::class, 'store'])->name('sessions.store');
Route::get('auth/logout',[SessionsController::class, 'destroy'])->name('sessions.destroy');

//비밀번호 초기화
Route::get('auth/remind',[PasswordsController::class, 'getRemind'])->name('remind.create');
Route::post('auth/remind',[PasswordsController::class, 'postRemind'])->name('remind.store');
Route::get('auth/reset/{token}', [PasswordsController::class, 'getReset'])->name('reset.create');
Route::post('auth/reset',[PasswordsController::class, 'postReset'])->name('reset.store');

// 소셜 로그인 기능
//github
Route::get('social/{provider}', [SocialController::class, 'execute'])->name('social.login');

//중첩라우트 와 쿼리 분기 p.272
Route::get('tags/{slug}/articles', [ArticlesController::class, 'index'])->name('tags.articles.index');

// 사진 드롭존 이용 ruote
Route::resource('attachments', AttachmentsController::class)->only(['store','destroy']);

//28.3 서버 측 구현 p.305
Route::resource('comments',CommentsController::class)->only(['update','destroy']);
//중첩 라우트
Route::resource('articles.comments', CommentsController::class)->only(['store']);
//튜표하기 라우팅
Route::post('comments/{comment}/votes',[CommentsController::class, 'vote'])->name('comments.vote');

Route::get('/home', function(){
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');




//이벤트 실험용 자제척
Route::get('/event', [EventController::class, 'index'])->name('event.index');

//p.146 이메일 보내기
Route::get('/mail',function(){

    $article = App\Models\Article::with('user')->find(1);

    return Mail::send(
        'emails.created',
        compact('article'),
        function($message) use ($article){
            //보내는사람
            $message->from('kkte03@gmail.com','KKW');
            //받을 도메인 설정 (여러명일 경우 배열로 보낸다.)
            $message->to('kkte03@gmail.com');
            $message->subject('새 글이 등록되었습니다 -' . $article->title);
            //참조
            //$message->cc('kkte03@gmail.com'):
            //숨은 참조
            $message->bcc('kkte03@gmail.com');
            //파일첨부 storage안에 적절한 파일을 넣고storage_path함수 안에 그 파일명을 넣어주면 된다.
            $message->attach(storage_path('download.jpeg'));
        }
    );
});
//학생추가 실습관련 라우팅
//메인화면
Route::get('/students', [StudentController::class, 'index'])->name('student_index');
//학생추가
Route::post('/add-student', [StudentController::class, 'addStudent'])->name('addStudent');


//memo 실습 관련 route setting
//name 설정이 되면 간편하게 view 나 컨트롤러에서 route('name') 으로 호출할 수 있다.
//메인
Route::get('/memo', [MemoController::class, 'index'])->name('memo');
//create
Route::get('/create', [MemoController::class, 'create'])->name('create');
//post store
Route::post('/store', [MemoController::class, 'store'])->name('store');
//edit
Route::get('/edit', [MemoController::class, 'edit'])->name('edit');
//post update
Route::post('/update', [MemoController::class, 'update'])->name('update');
//delete
Route::get('/delete', [MemoController::class, 'delete'])->name('delete');

//board 실습 관련 route setting

//게시판 메인
Route::get('/board', [BoardController::class, 'index'])->name('board_list');
//create
Route::get('/board_create', [BoardController::class, 'create'])->name('board_create');
//post store (insert boards)
Route::post('/board_store', [BoardController::class, 'store'])->name('board_store');
//게시글 보여주기
Route::get('/board_view', [BoardController::class, 'view'])->name('board_view');
//게시글 수정하기
Route::get('/board_edit', [BoardController::class, 'edit'])->name('board_edit');
//post update
Route::post('/board_update', [BoardController::class, 'update'])->name('board_update');
//게시글 삭제하기
Route::get('/board_delete', [BoardController::class, 'delete'])->name('board_delete');
//게시글 보여주기
// Route::post('/ripple_show', [RippleController::class, 'select'])->name('ripple_select');
// //
// Route::post('/ripple_insert', [RippleController::class, 'insert'])->name('ripple_insert');
//controller 사용한 이메일 보내기
//Route::get('/mali',[SendMailController::class, 'index'])->name('send.mail.index');

//p.173 테스트
// Route::get('docs/{file?}', function ($file = null){
//     $text = (new App\Models\Documentation)->get($file);

//     return app(ParsedownExtra::class)->text($text);
// });
//p.178 컨트롤러로 변경
Route::get('docs/{file?}',[DocsController::class, 'show'])->name('docs.show');

//p.184 code 20-5
Route::get('docs/images/{image}', [DocsController::class, 'image'])->where('image','[\pL-\pN._-]+-img-[0-9]{2}.jpeg')->name('docs.image');




Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


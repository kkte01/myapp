<?php


use App\Http\Controllers\Api\v1\ArticlesController;
use App\Http\Controllers\Api\v1\AttachmentsController;
use App\Http\Controllers\Api\v1\CommentsController;
use App\Http\Controllers\Api\v1\WelcomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::domain(config('project.api_domain'))->name('Api')->as('api')->group(function (){
    //인증관련 라우트
    Route::prefix('v1')->name('v1')->as('v1')->group(function (){
        //리소스 관련 라우트
        /* 환영 메시지 */
        Route::get('/', [WelcomeController::class, 'index',])->name('index');

        /* 포럼 API */
        // 아티클
        Route::resource('articles', ArticlesController::class);

        // 태그별 아티클 (중첩 라우트)
        Route::get('tags/{slug}/articles', [ArticlesController::class, 'index'])->name('tags.articles.index');

        // 태그
        Route::get('tags', [ArticlesController::class, 'tags'])->name('tags.index');

        // 첨부 파일
        Route::resource('attachments', AttachmentsController::class)->only(['store', 'destroy']);

        // 아티클별 첨부 파일
        Route::resource('articles.attachments', AttachmentsController::class)->only(['index']);

        // 댓글
        Route::resource('comments', CommentsController::class)->only(['show', 'update', 'destroy']);

        // 아티클별 댓글
        Route::resource('articles.comments', CommentsController::class)->only(['index', 'store']);

        // 투표
        Route::post('comments/{comment}/votes', [CommentsController::class, 'vote',])->name('comments.vote');
    });
});

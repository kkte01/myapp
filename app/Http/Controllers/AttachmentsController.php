<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticlesRequest;
use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    //
    public function store(ArticlesRequest $request)
    {
        $attachments = [];
        //Illuminate\Http\Request $request 인스턴스는 로그인한 사용자 정보를 이미 가지고 있다.
        //게다가 auth미들웨어는 로그인하지 않은 사용자가 이 메서드에 들어오는것을 막아주므로 nullpoint 예외로부터도 안전하다.
        $article = $request->user()->articles()->create($request->all());

        if (!$article) {

            return back()->with('flash_message', '글이 저장되지 않았습니다.')->withInput();
        }
        $article->tags()->sync($request->input('tags'));


        //파일 저장하기
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                //test_image폴더로  $file을 올린다.
                $savePath = file_upload('test_image', $file);
                $payload = [
                    'filename' => $savePath,
                    'bytes' => $file->getSize(),
                    'mime' => $file->getClientMimeType()
                ];
                $attachments[] = ($id = $request->input('article_id'))
                    ? \App\Models\Article::findOrfail($id)->attachments()->create($payload)
                    : \App\Models\Attachment::create($payload);

            }
        }
        return response()->json($attachments);
    }
}

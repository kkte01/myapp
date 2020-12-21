<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//두번 이상 사용했으므로 use를 이용 File 파사드 import
use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Documentation extends Model
{
    use HasFactory;
    public function get($file = 'documentation.md')
    {
        $content = File::get($this->path($file));

        return $this->replaceLinks($content);
    }

    public function image($file)
    {
        return Image::make($this->path($file, 'docs/images'));
    }

    //경로 계산 함수
    protected function path($file, $dir = 'docs')
    {
        //$file 이 .md로 끝나는지 검사(삼항연산자이용)
        $file = Str::endsWith($file, ['.md', '.jpeg']) ? $file : $file . '.md';
        //$path의 절대 경로를 반환 하는 함수 DIRECTORY_SEPARATOR상수는 Window 계열에서는 역슬래시(\) 디렉터리 구분자로 치환
        $path = base_path($dir  . DIRECTORY_SEPARATOR . $file);
        //파일이 없을 시 예외처리
        if(! File::exists($path))
        {
            abort(404, '요청하신 파일이 없습니다.');
        }
        return $path;
    }
    protected function replaceLinks($content)
    {
        return str_replace('/docs/{{version}}', '/docs', $content);
    }

    /**
     * Calculate etag value.
     *
     * @param $file
     * @return string
     */
    public function etag($file)
    {
        $lastModified = File::lastModified($this->path($file, 'docs/images'));

        return md5($file . $lastModified);
    }
}

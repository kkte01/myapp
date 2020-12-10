<?php
use \Illuminate\Support\Facades\Storage;
//함수 존재여부 검사
if(!function_exists('markdown')){
    function markdown($text = null)
    {
        return app(ParsedownExtra::class)->text($text);
    }
}

function gravatar_url($email, $size = 48){
    return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
}

function gravatar_profile_url($email){
    return sprintf("//www.gravatar.com/%s", md5($email));
}

/********************************************
 * @name : file_upload
 * @description : 파일 업로드
 * @return : path
 *********************************************/
if(!function_exists('file_upload')){
    function file_upload($folder, $file){
        $savePath = Storage::disk('local')->put($folder, $file);

        return $savePath;
    }
}
/********************************************
 * @name : format_filesize
 * @description : 파일 사이즈 측정
 * @return : bytes
 *********************************************/
function format_filesize($bytes){
    if(!is_numeric($bytes)) return 'NaN';

    $decr = 1024;
    $step = 0;
    $suffix =['bytes', 'KB', 'MB'];
    while(($bytes / $decr) > 0.9){
        $bytes = $bytes /$decr;
        $step ++;
    }
    return round($bytes, 2) . $suffix[$step];
}
/********************************************
 * @name : link_for_sort
 * @description : HTML 링크를 반환
 * @return : link
 *********************************************/
function link_for_sort($column, $text, $params =[]){
    //입력값 조회하기 입려된 값중에 order라는 값이 있는지 확인
    $direction = request()->input('order');
    $reverse = ($direction == 'asc') ? 'desc' : 'asc';

    if(request()->input('sort') == $column){
        $text = sprintf("%s %s",
            $direction == 'asc'
                ? '<i class="fa fa-sort-alpha-asc"></i>'
                : '<i class="fa fa-sort-alpha-desc"></i>',
            $text
        );
    }

    $queryString = http_build_query(array_merge(
        request()->except(['sort', 'order']),
        ['sort' => $column, 'order'=>$reverse],
        $params
    ));
    return sprintf(
        '<a href="%s?%s">%s</a>',
        urldecode(request()->url()),
        $queryString,
        $text
    );
}

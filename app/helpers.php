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
if (! function_exists('link_for_sort')) {
    /**
     * Build HTML anchor tag for sorting
     *
     * @param string $column
     * @param string $text
     * @param array  $params
     * @return string
     */
    function link_for_sort($column, $text, $params = [])
    {
        $direction = request()->input('order');
        $reverse = ($direction == 'asc') ? 'desc' : 'asc';

        if (request()->input('sort') == $column) {
            // Update passed $text var, only if it is active sort
            $text = sprintf(
                "%s %s",
                $direction == 'asc'
                    ? '<i class="fa fa-sort-alpha-asc"></i>'
                    : '<i class="fa fa-sort-alpha-desc"></i>',
                $text
            );
        }

        $queryString = http_build_query(array_merge(
            request()->except(['sort', 'order']),
            ['sort' => $column, 'order' => $reverse],
            $params
        ));

        return sprintf(
            '<a href="%s?%s">%s</a>',
            urldecode(request()->url()),
            $queryString,
            $text
        );
    }
}

if (! function_exists('attachments_path')) {
    /**
     * Generate attachments path.
     *
     * @param string $path
     * @return string
     */
    function attachments_path($path = null)
    {
        return public_path('files'.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}

/********************************************
 * @name : cache_key
 * @description : 요청A와 요청B를 구분할는 캐시 키 생성
 * @return : $key
 *********************************************/
if(!function_exists('cache_key')){
    function cache_key($base){
        $key = ($uri = request()->getQueryString())
            ? $base.'.'.urlencode($uri)
            : $base;
        return md5($key);
    }
}
/********************************************
 * @name : taggable()
 * @description : 캐시 태그가 가능한지 판단하는 함수
 * @return : bool
 *********************************************/
if(!function_exists('taggable')){
    function taggable(){
        return in_array(config('cache.default'), ['memcached', 'redis'], true);
    }
}
/********************************************
 * @name : current_url()
 * @description : 쿼리 스트링을 다시 만드는 함수
 * @return : string
 *********************************************/
if(!function_exists('current_url')){
    function current_url(){
        if(!request()->has('return')){
            return request()->fullUrl();
        }

        return sprintf(
            '%s?$s',
            request()->url(),
            http_build_query(request()->except('return'))
        );
    }
    /********************************************
     * @name : array_transpose()
     * @description : 열과줄을 바꾸는 함수
     * @return : array
     *********************************************/
    if(!function_exists('array_transpose')){
        function array_transpose(array $data){
            $res = [];

            foreach ($data as $row => $columns){
                foreach ($columns as $row2 =>$columns2){
                    $res[$row2][$row] = $columns2;
                }
            }
            return $res;
        }
    }/********************************************
     * @name : is_api_domain()
     * @description : 현재 HTTP요청의 호스트 이름을 조회하고, apimyappdev로 시작하는지 확인하는 함수
     * @return : string
     *********************************************/
    if(!function_exists('is_api_domain')){
        function is_api_domain(): bool
        {
            return str_starts_with(request()->getHttpHost(), config('project.api_domain'));
        }
    }
    /********************************************
     * @name : optimus()
     * @description : 엘로퀀트 아이디를 난수화 하는 함수
     * @return : string
     *********************************************/
    if(!function_exists('optimus')){
        function optimus($id = null){
            $factory = app('optimus');

            if(func_num_args() === 0){
                return $factory;
            }
            return $factory->encode($id);
        }
    }
}

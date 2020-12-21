<?php

return [
    'name' => 'My Application',
//  프로젝트 기본정보-----------------------------------------------
    'url' => 'http://myappdev:8000',
    'api_domain'=> env('API_DOMAIN', 'apimyappdev'),
    'app_domain'=> env('APP_DOMAIN', 'myappdev'),
    //----------------------------------------------------------
//    'description' =>'',
//    'tags' =>[
//        'laravel' => '라라벨',
//        'lumen' => '루멘',
//        'general' => '자유의견',
//        'tip' => '팁'
//    ],
//    'sorting' =>[
//      'view_count'=>'조회수',
//      'created_at'=>'작성일',
//    ],
    'cache'=> true,
    'locales'=>[
        'ko' => '한국어',
        'en' => 'English'
    ],/*
    |--------------------------------------------------------------------------
    | Tag 목록
    |--------------------------------------------------------------------------
    */
    'tags' => [
        'ko' => [
            'laravel' => '라라벨',
            'lumen' => '루멘',
            'general' => '자유의견',
            'server' => '서버',
            'tip' => '팁',
        ],
        'en' => [
            'laravel' => 'Laravel',
            'lumen' => 'Lumen',
            'general' => 'General',
            'server' => 'Server',
            'tip' => 'Tip',
        ],
    ],
    'etag' => true

];

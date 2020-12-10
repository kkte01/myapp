@extends('layouts.app')

@section('content')
    @php $viewName = 'articles.index'; @endphp
    <div class="page-header">
        <h4>포럼 <small> / {{ $article->title }}</small></h4>
    </div>

    <!--ajax이용을 위해 아이디값선언  -->
    <article data-id="{{ $article->id }}">
        @include('articles.partial.article', compact('article'))
        <p>{!! markdown($article->content) !!}</p>
        @include('tags.partial.list', ['tags' => $article->tags])
        <div class="container__comment">
            @include('comments.index')
        </div>
    </article>
    <div class="text-center action__article">
        @can('update', $article)
        <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-info">
            <i class="fa fa-pencil"></i>글수정
        </a>
        @endcan
        @can('delete', $article)
        <button class="btn btn danger btn__delete">
            <i></i> 글 삭제
        </button>
        @endcan
        <a href="{{ route('articles.index') }}" class="btn btn-default">
            <i class="fa fa-list"></i> 글 목록
        </a>
    </div>
@stop
@section('script')
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.btn__delete').on('click', function(e){
            // var articleId = {{ $article->id }} 도 상관은 없다.
            var articleId = $('article').data('id');

            if(confirm('글을 삭제 하시겠습니까?')){
                $.ajax({
                    type:'DELETE',
                    url:'/articles/' + articleId
                }).then(function(){
                    window.location.href = '/articles';
                });
            }
        });
    </script>
@stop

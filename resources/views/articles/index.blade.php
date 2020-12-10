@extends('layouts.app')

@section('content')
  @php $viewName = 'articles.index'; @endphp

  <div class="page-header">
    <h4>
      <a href="{{ route('articles.index') }}">
        포럼
      </a>
      <small>
        / 글 목록
      </small>
    </h4>
  </div>
  <div class="text-right action__article">
    <a href="{{ route('articles.create') }}" class="btn btn-primary">
      <i class="fa fa-plus-circle"></i>
        새글쓰기
    </a>
        {{-- p.321 에서 추가된 구문 --}}
      <div class="btn-group sort__article">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-sort"></i>목록정렬 <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
              @if(!empty(config('project.sorting')))
              @foreach(config('project.sorting') as $column => $text)
                  <li {!! request()->input('sort') == $column ? 'class="active"' : '' !!}>
                      {!! link_for_sort($column, $text) !!}
                  </li>
              @endforeach
              @endif
          </ul>
      </div>
        {{-- 여기까지  --}}
    <div class="row">
      <div class="col-md-3">
        <aside>
          @include('tags.partial.index')
        </aside>
      </div>
    </div>
    <div class="col-md-9 list__article">
      <article>
        @forelse($articles as $article)
          @include('articles.partial.article', compact('article'))
        @empty
          <p class="text-center text-danger">
            글이 없습니다.
          </p>
        @endforelse
      </article>

      @if($articles->count())
        <div class="text-center paginator__article">
          {!! $articles->appends(request()->except('page'))->render() !!}
        </div>
      @endif
    </div>
  </div>
  {{-- p.321 에서 추가된 구문 --}}
    @include('articles.partial.search')
    @include('tags.partial.index')
  {{-- 여기까지 --}}
@stop

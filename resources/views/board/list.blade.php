@extends('layouts.master')
@section('content')
    <input type="button" value="Create board" onclick="location.href='{{ route('board_create') }}'">
    @if(isset($boards) && $boards != null)
        @foreach($boards as $board)
            <div>
                <span>{{ $number++ }}</span>
                <a href="{{ route('board_view', ['id' => $board->id]) }}">{{ $board-> title}}</a>
                <span>{{ $board-> writer}}</span>
                <span>{{ $board-> created_at}}</span>
                <input type="button" value="edit" onclick="location.href='{{ route('board_edit', ['id' => $board->id]) }}'">
                <input type="button" value="delete" onclick="location.href='{{ route('board_delete', ['id' => $board->id]) }}'">
            </div>
        @endforeach
    @else
        <h2>등록된 글이 없습니다.</h2>
    @endif
    @if($boards->count())
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
            {!! $boards->links() !!}
        </div>
    @endif
@endsection
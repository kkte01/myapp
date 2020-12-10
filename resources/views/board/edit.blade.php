@extends('layouts.master') 
@section('content') 
    <form method="POST" action="{{ route('board_update',['id' => $board->id, 'content' => $board->content, 'title' => $board->title ]) }}">
    @csrf
    <input type="text" name="title" id="title" value="{{ $board->title }}">
    <h3>{{ $board->writer }}</h3>
    <textarea name="content" rows="4">{{ $board->content}}</textarea> 
        @if($errors->any()) 
            @foreach($errors->all() as $error) 
                <p>{{$error}}</p> 
            @endforeach 
        @endif 
        <input type="submit" value="update"/> 
        <input type="button" value="back" onclick="location.href='{{ route('board_list') }}'"/> 
    </form> 
@endsection
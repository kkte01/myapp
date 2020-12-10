@extends('layouts.master')
@section('content')
    <input type="button" value="Create Memo" onclick="location.href='{{ route('create') }}'">
    @foreach($memos as $memo)
    <div>
        <span>{{ $memo->content}}</span>
        <input type="button" value="edit" onclick="location.href='{{ route('edit', ['id' => $memo->id]) }}'">
        <input type="button" value="delete" onclick="location.href='{{ route('delete', ['id' => $memo->id]) }}'">
    </div>
    @endforeach
@endsection
@extends('layouts.master')
@section('content')
    <form action="{{ route('board_store') }}" method="post">
    @csrf
    <label for="title">제목:&nbsp&nbsp</label>
    <input type="text" name="title" id="title">
    <br>
    <label for="writer">작성자:&nbsp&nbsp</label>
    <input type="text" name="writer" id="writer">
    <br>
    <textarea name="content" rows="4"></textarea> 
        @if($errors->any())
            @foreach($errors->all() as $error) 
            <p>{{ $error }}</p> 
            @endforeach 
        @endif 
        <input type="submit" value="create"> 
        <input type="button" value="back" onclick="location.href='{{ route( 'board_list' )}}'"> 
    </form>
@endsection
@extends('layouts.master')

@section('content')
    <div>
        <h3>제목 : {{ $board -> title}}</h3><h3>작성일자&nbsp:&nbsp{{ $board->created_at }}</h3>
        <h4>작성자 : {{ $board -> writer}}</h4>
        <h1>내용 : {{ $board -> content}}</h1>
        <input type="button" value="back" onclick="location.href='{{ route('board_list') }}'"/> 
    </div>
    <div>
        <p>ripples</p>
        <p>댓글 작성자</p>
        <input type="text" name="ripple_writer" id="ripple_writer">
        <br>
        <textarea name="ripple" id="ripple" cols="30" rows="10" ></textarea>
        <input type="button" value="regist" onclick=" ripple_insert({{ $board->id }})">
    </div>
@endsection
@section('script')
    <!-- ripple 보여주기, 등록 삭제 기능 ajax -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        function ripple_show($id){
            console.log($id);
            $.ajax({
                //아래 headers에 반드시 token을 추가해줘야 한다.!!!!! 
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                url: '{{ route('ripple_select') }}',
                dataType: 'json',
                data: { id :$id},
                success: function(data) {
                    console.log(data);
                },
                error: function(data) {
                        console.log("error" +data);
                }        
            });
        }
        ripple_show({{ $board->id }});
        function ripple_insert($id){
            $rpWriter = $('#ripple_writer').val();
            $rpContent = $('#ripple').val();
            console.log($rpWriter);
            console.log($rpContent);
            $.ajax({
                //아래 headers에 반드시 token을 추가해줘야 한다.!!!!! 
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                url: '{{ route('ripple_insert') }}',
                dataType: 'json',
                data: { rpWriter:$rpWriter, rpContent: $rpContent },
                success: function(data) {
                    console.log(data);
                },
                error: function(data) {
                        console.log("error" +data);
                }        
            });
        }
    </script>
@endsection
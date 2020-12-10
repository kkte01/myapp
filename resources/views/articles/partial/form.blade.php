<div class="form-group {{ $errors->has('title') ? 'has-error': '' }}">
    <label for="title">제목</label>
    <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" class="form-control">
    {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>
<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
    <label for="content">본문</label>
    <br>
    <textarea name="content" id="content" rows="10" class="form-control">{{ old('content', $article->content) }}</textarea>
    {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
</div>
<div class="form-group {{ $errors->has('tags') ? 'has-error' : '' }}">
  <label for="tags">태그</label>
  <!-- 태그는 다중선택이 가능하므로 서버에서 인식할 수 있게 []를 붙여준다. -->
  <select name="tags[]" id="tags" multiple="multiple" class="form-control" >
    @foreach($allTags as $tag)
      <option value="{{ $tag->id }}" {{ $article->tags->contains($tag->id) ? 'selected="selected"' : '' }}>
        {{ $tag->name }}
      </option>
    @endforeach
  </select>
{{--    <div class="form-group {{ $errors->has('files') ? 'has-error' : '' }}">--}}
{{--        <label for="files">파일</label>--}}
{{--        <br>--}}
{{--        <input type="file" name="files[]" id="files" class="form-control" multiple="multiple">--}}
{{--        {!! $errors->first('files.0', '<span class="form-error">:message</span>') !!}--}}
{{--    </div>--}}
</div>
<div class="form-group">
    <label for="my-awesome-dropzone">파일</label>
    <div id="my-awesome-dropzone" class="dropzone"></div>
</div>
@section('script')
    @parent
    <script>
        let myDropzone = new Dropzone('div#my-awesome-dropzone',{
            url : '/attachments',
            paramName:'files',
            maxFilesize:3,
            acceptedFiles:'.jpg,.png,.zip,.tar',
            uploadMultiple : true,
            params:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                article_id: '{{ $article->id }}'
            },
            dictDefaultMessage:'<div class="text-center text-muted>'+
                '<h2>첨부할 파일을 끌어다 놓으세여!</h2>'+
                '<p>(또는 클릭하셔도됩니다.)</p></div>',
            dictFileTooBig : "파일당 최대 크기는 3MB입니다.",
            dictInvalidFileType: 'jpg, png, zip, tar 파일만 가능합니다.'
        });

        var form = $('from').first();

        myDropzone.on('successmultiple', function (file,data){
            len=data.length;
            for(var i = 0; i< len; i++){
                $("<input>",{
                    type: "hidden",
                    name: "attachments[]",
                    value : data[i].id
                }).appendTo(form);
            }
        });
    </script>
@stop


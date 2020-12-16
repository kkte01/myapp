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
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="notification" value="{{ old('notufication', 1) }}" checked>
                댓글이 작성되면 이메일 알림 받기
            </label>
        </div>
    </div>
    <div class="form-group">
        <label for="my-dropzone">
            파일
        </label>
        <div id="my-dropzone" class="dropzone"></div>
    </div>
  <label for="tags">태그</label>
  <!-- 태그는 다중선택이 가능하므로 서버에서 인식할 수 있게 []를 붙여준다. -->
  <select name="tags[]" id="tags" multiple="multiple" class="form-control" >
    @foreach($allTags as $tag)
      <option value="{{ $tag->id }}" {{ $article->tags->contains($tag->id) ? 'selected="selected"' : '' }}>
        {{ $tag->name }}
      </option>
    @endforeach
  </select>
</div>
@section('script')
    @parent
    <script type="text/javascript">
        Dropzone.autoDiscover =false;
        // 드롭존 인스턴스 생성.
        let myDropzone = new Dropzone('div#my-dropzone', {
            url: '/attachments',
            paramName: 'files',
            maxFilesize: 3,
            acceptedFiles: '.jpg,.png,.zip,.tar',
            uploadMultiple: true,
            params: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                article_id: '{{ $article->id }}'
            },
            dictDefaultMessage: '<div class="text-center text-muted">' +
                "<h2>첨부할 파일을 끌어다 놓으세요!</h2>" +
                "<p>(또는 클릭하셔도 됩니다.)</p></div>",
            dictFileTooBig: "파일당 최대 크기는 3MB입니다.",
            dictInvalidFileType: 'jpg, png, zip, tar 파일만 가능합니다.',
            addRemoveLinks: true
        });
        // 파일 업로드 성공 이벤트 리스너.
        myDropzone.on('successmultiple', function(file, data) {
            for (var i= 0,len=data.length; i<len; i++) {
                // 책에 있는 'attachments[]' 숨은 필드 추가 로직을 별도 메서드로 추출했다.
                handleFormElement(data[i].id);
                // 책에 없는 내용
                // 성공한 파일 애트리뷰트를 파일 인스턴스에 추가
                file[i]._id = data[i].id;
                file[i]._name = data[i].filename;
                file[i]._url = data[i].url;
                // 책에 없는 내용
                // 이미 파일일 경우 handleContent() 호출.
                if (/^image/.test(data[i].mime)) {
                    handleContent('content', data[i].url);
                }
            }
        });
    </script>
@stop


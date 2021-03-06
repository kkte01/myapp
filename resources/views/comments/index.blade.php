<div class="page-header">
    <h4>
        댓글
    </h4>
</div>
<div class="form__new__comment">
    @if($currentUser)
        @include('comments.partial.create')
    @else
        @include('comments.partial.login')
    @endif
</div>
<div class="list__comment">
    @forelse($comments as $comment)
        @include('comments.partial.comment', [
          'parentId' => $comment->id,
          'isReply' => false,
          'hasChild'=> $comment->replies->count(),
          'isTrashed' => $comment->trashed(),
        ])
    @empty
    @endforelse
</div>

@section('script')
    @parent
    <script>
        // 댓글 삭제 요청을 처리한다.
        $('.btn__delete__comment').on('click', function(e) {
            var commentId = $(this).closest('.item__comment').data('id'),
                articleId = $('#item__article').data('id');
                console.log(commentId);
            if (confirm('댓글을 삭제 하시겠습니까?')) {
                $.ajax({
                    type:"POST",
                    url:"/comments/"+commentId,
                    data:{
                        _method:"DELETE"
                    }
                }).then(function() {
                    $('#'+commentId).fadeOut(1000, function (){ $(this).remove(); });
                });
            }
        });
        // 대댓글 폼을 토글한다.
        $('.btn__reply__comment').on('click', function(e) {
            var el__create = $(this).closest('.item__comment').find('.media__create__comment').first(),
                el__edit = $(this).closest('.item__comment').find('.media__edit__comment').first();
            el__edit.hide('fast');
            el__create.toggle('fast').end().find('textarea').focus();
        });
        // 댓글 수정 폼을 토글한다.
        $('.btn__edit__comment').on('click', function(e) {
            var el__create = $(this).closest('.item__comment').find('.media__create__comment').first(),
                el__edit = $(this).closest('.item__comment').find('.media__edit__comment').first();
            el__create.hide('fast');
            el__edit.toggle('fast').end().find('textarea').first().focus();
        });
        // 투표 저장 요청을 한다.
        $('.btn__vote__comment').on('click', function(e) {
            let self = $(this),
                commentId = self.closest('.item__comment').data('id');
            $.ajax({
                type: 'POST',
                url: '/comments/'+commentId+'/votes',
                data: {
                    vote: self.data('vote')
                }
            }).then(function (data) {
                self.find('span').html(data.value).fadeIn();
                self.attr('disabled', 'disabled');
                $('.btn__vote__comment').attr('disabled', 'disabled');
            });
        });
    </script>
@endsection

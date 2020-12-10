@if($tags->count())
 <ul class="tags--article">
    @foreach($tags as $tag)
    <li>
        <a href="{{ route('tags.articles.index', $tag->slug) }}">
            {{ $tag-> name}}
        </a>
    @endforeach
    </li>
 </ul>
 @endif
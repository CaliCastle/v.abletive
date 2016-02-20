@if(count($tags))<li class="divider" data-content="{{ trans('tags.title') }}"></li>@endif
@foreach($tags as $tag)
<li class="tag-item item">
    <a href="{{ $tag->link() }}">
        <h3>{{ $tag->name }}</h3>
    </a>
</li>
@endforeach
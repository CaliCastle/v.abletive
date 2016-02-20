@if(count($lessons))<li class="divider" data-content="{{ trans('lessons.skill_lessons') }}"></li>@endif
@foreach($lessons as $lesson)
<li class="lesson-item item">
    <a href="{{ $lesson->link() }}">
        <h3>{{ $lesson->title }}</h3>
        <p>{!! $lesson->description !!}</p>
    </a>
</li>
@endforeach
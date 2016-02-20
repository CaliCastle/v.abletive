<form action="{{ $url }}" method="POST" class="setting-form">
    {!! csrf_field() !!}
    <div class="form-group{{ $errors->has('title') ? " has-error" : "" }}">
        <label>{{ trans('manage/lessons.create.title') }}:</label>
        <input type="text" class="form-control" name="title" value="{{ old('title') ? old('title') : $lesson->title }}">
        @if($errors->has('title'))
            <strong class="has-error">{{ $errors->first('title') }}</strong>
        @endif
    </div>
    <div class="form-group{{ $errors->has('source') ? " has-error" : "" }}">
        <label>{{ trans('manage/lessons.create.source') }}:</label>
        <input type="text" class="form-control" name="source" value="{{ old('source') ? old('source') : $lesson->source }}">
        @if($errors->has('source'))
            <strong class="has-error">{{ $errors->first('source') }}</strong>
        @endif
    </div>
    <div class="form-group{{ $errors->has('duration') ? " has-error" : "" }}">
        <label>{{ trans('manage/lessons.create.duration') }}:</label>
        <input type="text" class="form-control" name="duration" value="{{ old('duration') ? old('duration') : $lesson->duration }}">
        @if($errors->has('duration'))
            <strong class="has-error">{{ $errors->first('duration') }}</strong>
        @endif
    </div>
    <div class="form-group{{ $errors->has('experience') ? " has-error" : "" }}">
        <label>{{ trans('manage/lessons.create.experience') }}:</label>
        <select class="form-control" name="experience" id="xp-select">
            @for ($i = 100; $i <= 1000; $i+=100)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        @if($errors->has('experience'))
            <strong class="has-error">{{ $errors->first('experience') }}</strong>
        @endif
    </div>
    <div class="form-group{{ $errors->has('description') ? " has-error" : "" }}">
        <label>{{ trans('manage/lessons.create.description') }}:</label>
        <textarea name="description" class="form-control">{!! old('description') ? old('description') : $lesson->description !!}</textarea>
        @if($errors->has('description'))
            <strong class="has-error">{{ $errors->first('description') }}</strong>
        @endif
    </div>
    <div class="form-group">
        <label>{{ trans('manage/lessons.create.tags') }}:</label>
        <select class="form-control" name="tags[]" id="tag-select" multiple>
            @foreach($lesson->tags as $tag)
                <option value="{{ $tag->name }}" selected>{{ $tag->name }}</option>
            @endforeach
            @foreach(\App\Tag::newest()->take(15)->get() as $tag)
                @unless(array_has($lesson->tags->lists('name', 'name')->toArray(), $tag->name))
                <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                @endunless
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>{{ trans('manage/lessons.table_header.series') }}:</label>
        <select class="form-control" name="series_id" id="series-select">
            @foreach(\App\Series::latest()->get() as $series)
                <option value="{{ $series->id }}"{{ $lesson->series_id == $series->id ? " selected" : "" }}>{{ $series->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <div class="checkbox">
            <input type="checkbox" name="need_subscription"{{ $lesson->needSubscription() ? " checked" : "" }}>{{ trans('manage/lessons.need_subscription') }}
        </div>
    </div>
    <div class="form-group">
        <input class="btn btn-block btn-primary" type="submit" value="{{ $button_text }}">
    </div>
    @if($type == "edit" && auth()->user()->isManager())
    <div class="form-group">
        <a class="btn btn-block btn-danger" href="javascript:;" id="delete-btn">{{ trans('manage/series.delete_button') }}</a>
    </div>
    @endif
</form>
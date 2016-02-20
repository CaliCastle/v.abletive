<form action="{{ $url }}" method="POST" class="setting-form">
    {!! csrf_field() !!}
    <div class="form-group{{ $errors->has('title') ? " has-error" : "" }}">
        <label>{{ trans('manage/series.create.title') }}:</label>
        <input type="text" class="form-control" name="title" value="{{ old('title') ? old('title') : $series->title }}">
        @if($errors->has('title'))
            <strong class="has-error">{{ $errors->first('title') }}</>
        @endif
    </div>
    <div class="form-group{{ $errors->has('slug') ? " has-error" : "" }}">
        <label>{{ trans('manage/series.create.slug') }}:</label>
        <input type="text" class="form-control" name="slug" value="{{ old('slug') ? old('slug') : $series->slug }}">
        @if($errors->has('slug'))
            <strong class="has-error">{{ $errors->first('slug') }}</strong>
        @endif
    </div>
    <div class="form-group{{ $errors->has('difficulty') ? " has-error" : "" }}">
        <label>{{ trans('manage/series.create.difficulty') }}:</label>
        <div class="radio">
            <input type="radio" name="difficulty" value="Beginner"{{ $series->difficulty == "Beginner" ? " checked" : '' }}>{{ trans('lessons.difficulty.beginner') }}
        </div>
        <div class="radio">
            <input type="radio" name="difficulty" value="Intermediate"{{ $series->difficulty == "Intermediate" ? " checked" : '' }}>{{ trans('lessons.difficulty.intermediate') }}
        </div>
        <div class="radio">
            <input type="radio" name="difficulty" value="Advanced"{{ $series->difficulty == "Advanced" ? " checked" : '' }}>{{ trans('lessons.difficulty.advanced') }}
        </div>
        @if($errors->has('difficulty'))
            <strong class="has-error">{{ $errors->first('difficulty') }}</strong>
        @endif
    </div>
    <div class="form-group{{ $errors->has('thumbnail') ? " has-error" : "" }}">
        <label>{{ trans('manage/series.create.thumbnail') }}:</label>
        <input type="text" class="form-control" name="thumbnail" value="{{ old('thumbnail') ? old('thumbnail') : $series->thumbnail }}">
        @if($errors->has('thumbnail'))
            <strong class="has-error">{{ $errors->first('thumbnail') }}</strong>
        @endif
    </div>
    <div class="form-group{{ $errors->has('description') ? " has-error" : "" }}">
        <label>{{ trans('manage/series.create.description') }}:</label>
        <textarea name="description" class="form-control">{{ old('description') ? old('description') : $series->description }}</textarea>
        @if($errors->has('description'))
            <strong class="has-error">{{ $errors->first('description') }}</strong>
        @endif
    </div>
    <div class="form-group{{ $errors->has('skills') ? " has-error" : "" }}">
        <label>{{ trans('manage/series.create.skill') }}:</label>
        <select name="skills[]" id="skill-select" class="form-control" multiple>
            @foreach(\App\Skill::all() as $skill)
                <option value="{{ $skill->id }}"{{ array_has($series->skills()->lists('id', 'id')->toArray(), $skill->id) ? " selected" : "" }}>{{ trans('skills.' . $skill->name) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <div class="checkbox">
            <input type="checkbox" name="published"{{ $series->published ? " checked" : "" }}>{{ trans('manage/series.publish_now') }}
        </div>
    </div>
    @if(isset($status))
    <div class="form-group">
        <div class="checkbox">
            <input type="checkbox" name="completed"{{ $series->completed ? " checked" : "" }}>{{ trans('manage/series.completed') }}
        </div>
    </div>
    @endif
    <div class="form-group">
        <input type="submit" class="btn btn-block btn-primary" value="{{ $button_text }}">
    </div>
    @if(isset($status) && auth()->user()->isManager())
    <div class="form-group">
        <a href="javascript:;" id="delete-btn" class="btn btn-block btn-danger">{{ trans('manage/series.delete_button') }}</a>
    </div>
    @endif
</form>
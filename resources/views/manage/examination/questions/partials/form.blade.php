<form action="{{ $url }}" method="POST" class="setting-form">
    {!! csrf_field() !!}
    <div class="form-group{{ $errors->has('title') ? " has-error" : '' }}">
        <label for="">标题</label>
        <textarea class="form-control" name="title">{{ old('title') ? old('title') : $question->title }}</textarea>
    </div>
    @for($i = 1; $i <= 4; $i++)
        <div class="form-group">
            <label for="">答案{{ $i }}</label>
            <input type="text" class="form-control" name="answers[]" value="{{ $question->getAnswer($i)->title }}">
            <div class="radio-inline">
                <input type="radio" name="correct" value="{{$i}}"{{ $question->getAnswer($i)->correct ? " checked" : "" }}> 正确答案
            </div>
        </div>
    @endfor
    <div class="form-group">
        <input class="btn btn-block btn-primary" type="submit" value="{{ $button_text }}">
    </div>
</form>
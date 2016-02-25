<form action="{{ $url }}" method="POST" class="setting-form">
    {!! csrf_field() !!}
    <div class="form-group">
        <label for="">标题</label>
        <input type="text" class="form-control" name="title" value="{{ old('title') ? old('title') : $examination->title }}" required>
    </div>
    <div class="form-group">
        <input class="btn btn-block btn-primary" type="submit" value="{{ $button_text }}">
    </div>
</form>
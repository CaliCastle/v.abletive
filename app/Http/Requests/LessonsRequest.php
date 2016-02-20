<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class LessonsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isTutor() || $this->user()->isManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" => "required|max:250",
            "source" => "required|max:255|url",
            "series_id" => "numeric|required",
            "duration" => "required",
            "experience" => "required|numeric|between:100,1000",
            "description" => "required|max:250"
        ];
    }

    /**
     * Custom attributes
     *
     * @return array
     */
    public function attributes()
    {
        return [
            "title" => trans('manage/lessons.create.title'),
            "source" => trans('manage/lessons.create.source'),
            "duration" => trans('manage/lessons.create.duration'),
            "experience" => trans('manage/lessons.create.experience'),
            "description" => trans('manage/lessons.create.description')
        ];
    }
}

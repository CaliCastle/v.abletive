<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SeriesRequest extends Request
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
            "slug" => "required|max:250|alpha_dash",
            "difficulty" => "required|in:Beginner,Intermediate,Advanced",
            "thumbnail" => "required|url"
        ];
    }

    public function attributes()
    {
        return [
            "title" => trans('manage/series.create.title'),
            "slug" => trans('manage/series.create.slug'),
            "difficulty" => trans('manage/series.create.difficulty'),
            "thumbnail" => trans('manage/series.create.thumbnail')
        ];
    }
}

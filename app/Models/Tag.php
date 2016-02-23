<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        "name"
    ];

    protected $perPage = 35;

    /**
     * Scope a query to its newest order
     *
     * @param $query
     * @return mixed
     */
    public static function scopeNewest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Lessons relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lessons()
    {
        return $this->belongsToMany('App\Video', 'tag_video');
    }

    /**
     * Paged lessons
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function pagedLessons()
    {
        return $this->lessons()->paginate();
    }

    /**
     * Detail link
     *
     * @return string
     */
    public function link()
    {
        return action('HomeController@showTag', ["tag" => $this->name]);
    }

    /**
     * Get tag by its name
     *
     * @param $name
     * @return mixed
     */
    public static function tagByName($name)
    {
        return static::where('name', 'like', $name)->first();
    }

    /**
     * Search tags
     *
     * @param $keyword
     * @return mixed
     */
    public static function search($keyword)
    {
        return static::where('name', 'like', "%{$keyword}%");
    }
}

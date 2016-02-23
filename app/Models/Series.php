<?php

namespace App;

use App\Skill;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    /**
     * Count per page in pagination
     * @var int
     */
    protected $perPage = 35;

    protected $fillable = [
        "title", "slug", "difficulty", "thumbnail", "completed", "published", "description", "skill_id"
    ];

    /**
     * @var array
     */
    protected $casts = [
        "completed", "published"
    ];

    /**
     * Relationship to lessons(videos)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany('App\Video');
    }

    /**
     * The subscriber of the series
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscriber()
    {
        return $this->belongsToMany(User::class, 'user_notifications');
    }

    /**
     * Action link
     *
     * @return string
     */
    public function link()
    {
        return action('SeriesController@show', ["name" => $this->slug]);
    }

    /**
     * Get series by slug
     *
     * @param $slug
     * @return mixed
     */
    public static function seriesBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Belonged skill
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    /**
     * Search by the given keyword
     *
     * @param $keyword
     * @return mixed
     */
    public static function search($keyword)
    {
        return static::where('title', 'like', "%{$keyword}%")->orWhere('description', 'like', "%{$keyword}%");
    }

    /**
     * Scope a query to its latest order
     *
     * @param $query
     * @return mixed
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to its newest order
     *
     * @param $query
     * @return mixed
     */
    public function scopeNewest($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * Mutator for completed attribute
     *
     * @param $value
     */
    public function setCompletedAttribute($value)
    {
        $this->attributes['completed'] = $value == "on";
    }

    /**
     * Mutator for published attribute
     *
     * @param $value
     */
    public function setPublishedAttribute($value)
    {
        $this->attributes['published'] = $value == "on";
    }

    /**
     * Is this series recently updated
     *
     * @return bool
     */
    public function recentlyUpdated()
    {
        return $this->updated_at <= Carbon::now() && $this->updated_at >= Carbon::now()->addDays(-2);
    }

    /**
     * Is this series recently published
     *
     * @return bool
     */
    public function recentlyPublished()
    {
        return $this->created_at <= Carbon::now() && $this->created_at >= Carbon::now()->addDays(-2);
    }

    /**
     * Scope a query to only its published
     *
     * @param $query
     * @return mixed
     */
    public static function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    /**
     * Featured series for index page
     *
     * @param $query
     * @return mixed
     */
    public static function scopeFeatured($query)
    {
        return $query->orderBy('updated_at', 'desc')->take(8)->get();
    }

    /**
     * Total experience
     *
     * @return string
     */
    public function totalExperience()
    {
        return number_format(array_sum($this->lessons->lists('experience')->toArray()));
    }

    /**
     * Total minutes
     *
     * @return int
     */
    public function totalMinutes()
    {
        $minutes = 0;
        foreach ($this->lessons as $lesson) {
            if (str_contains($lesson->duration, ':')) {
                $minute = intval(substr($lesson->duration, 0, stripos($lesson->duration, ':')));
                $second = intval(substr($lesson->duration, stripos($lesson->duration, ':') + 1));
            } else if (str_contains($lesson->duration, '：')) {
                $minute = intval(substr($lesson->duration, 0, stripos($lesson->duration, '：')));
                $second = intval(substr($lesson->duration, stripos($lesson->duration, '：') + strlen('：')));
            }
            $minutes = $minutes + (($minute * 60) + $second) / 60;
        }

        return intval($minutes);
    }

    /**
     * Get its one episode
     *
     * Index of array should be minus one
     *
     * @param $episode
     * @return mixed
     */
    public function getEpisode($episode)
    {
        return Video::findOrFail($this->lessons->toArray()[--$episode]['id']);
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function scopeBeginner($query)
    {
        return $query->where('difficulty', 'beginner');
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function scopeIntermediate($query)
    {
        return $query->where('difficulty', 'intermediate');
    }

    /**
     * @param $query
     * @return mixed
     */
    public static function scopeAdvanced($query)
    {
        return $query->where('difficulty', 'advanced');
    }
}

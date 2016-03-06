<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Video extends Model
{

    /**
     * @var int
     */
    protected $perPage = 35;

    protected $fillable = [
        "title", "duration", "experience", "description", "source", "series_id", "user_id", "need_subscription"
    ];

    protected $dates = [
        "published_at"
    ];

    protected $touches = [
        "series"
    ];

    /**
     * Whose video it is
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Who has watched the lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function watchedUsers()
    {
        return $this->belongsToMany('App\User', 'user_watched');
    }

    /**
     * Scope it show only the tutor's videos
     *
     * @param $query
     * @param $user_id
     * @return mixed
     */
    public static function scopeTutor($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * Relationship to user favorites
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userFavorites()
    {
        return $this->belongsToMany(User::class, 'user_favorites');
    }

    /**
     * From which series
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function series()
    {
        return $this->belongsTo('App\Series');
    }

    /**
     * Relationship to tags
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    /**
     * Relationship to its comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * Get its super comments
     *
     * @return mixed
     */
    public function superComments()
    {
        return $this->comments()->where('parent_id', 0);
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
     * Scope a query to its oldest order
     *
     * @param $query
     * @return mixed
     */
    public static function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Scope a query to its latest order
     *
     * @param $query
     * @return mixed
     */
    public static function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Hottest lessons
     *
     * @return \Illuminate\Support\Collection
     */
    public static function hottest()
    {
        $videos = static::all();
        $video_likes = [];
        foreach ($videos as $video) {
            array_push($video_likes, $video->userFavorites->count());
        }
        $sorted = collect($video_likes)->sort()->reverse();

        $collection = collect([]);
        foreach ($sorted as $key => $item) {
            $collection->push($videos[$key]);
        }

        return $collection;
    }

    /**
     * Mutator for duration attribute
     *
     * @param $value
     */
    public function setDurationAttribute($value)
    {
        if (str_contains($value, '：'))
            $value = str_replace('：', ':', $value);
        $this->attributes["duration"] = $value;
    }

    /**
     * Is this video recently published
     *
     * @return bool
     */
    public function recentlyPublished()
    {
        return $this->published_at <= Carbon::now() && $this->published_at >= Carbon::now()->addDays(-2);
    }

    /**
     * The episode number
     *
     * @return mixed
     */
    public function episode()
    {
        return array_search($this->id, $this->series->lessons->lists('id')->toArray()) + 1;
    }

    /**
     * Detail link
     *
     * @return string
     */
    public function link()
    {
        return action('SeriesController@showLesson', ["series" => $this->series->slug, "episode" => $this->episode()]);
    }

    /**
     * If it has a previous episode
     *
     * @return bool
     */
    public function hasPrevious()
    {
        return $this->episode() != 1;
    }

    /**
     * Previous episode
     *
     * @return mixed
     */
    public function previousEpisode()
    {
        return $this->series->getEpisode($this->episode() - 1);
    }

    /**
     * If it has a next episode
     *
     * @return bool
     */
    public function hasNext()
    {
        return $this->series->lessons->count() != $this->episode();
    }

    /**
     * Next episode
     *
     * @return mixed
     */
    public function nextEpisode()
    {
        return $this->series->getEpisode($this->episode() + 1);
    }

    /**
     * Download link
     *
     * @return string
     */
    public function downloadLink()
    {
        return $this->source;
    }

    /**
     * Mutator for need_subscription attribute
     *
     * @param $value
     */
    public function setNeedSubscriptionAttribute($value)
    {
        $this->attributes["need_subscription"] = $value == "on";
    }

    /**
     * Is the subscription needed to watch the video
     *
     * @return bool
     */
    public function needSubscription()
    {
        return $this->need_subscription;
    }

    /**
     * If the lesson has hot comments
     * 
     * @return bool
     */
    public function hasHotComments()
    {
        return $this->comments->count() >= 20;
    }

    /**
     * Hot comments
     *
     * @return \Illuminate\Support\Collection
     */
    public function hotComments()
    {
        $comments = $this->comments;
        $comment_likes = [];
        foreach ($comments as $comment) {
            array_push($comment_likes, $comment->likes->count());
        }
        $sorted = collect($comment_likes)->sort()->reverse()->take(5);

        $collection = collect([]);
        foreach ($sorted as $key => $item) {
            $collection->push($comments[$key]);
        }

        return $collection;
    }
}

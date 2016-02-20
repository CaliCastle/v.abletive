<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        "video_id", "user_id", "parent_id", "message", "user_agent"
    ];

    protected $perPage = 30;


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
     * Belongs to whom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Belongs to which lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo('App\Video', 'video_id');
    }

    /**
     * Parent comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Children comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * The likes of the comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany('App\User', 'comment_likes');
    }

    /**
     * Reply link to be prepended
     *
     * @return string
     */
    public function replyLink()
    {
        return "<a class=\"at\" href=\"" . $this->user->profileLink() . "\">@" . $this->user->display_name ."</a>";
    }

    /**
     * Search by the keyword
     *
     * @param $keyword
     * @return mixed
     */
    public static function search($keyword)
    {
        return static::where('message', 'like', "%{$keyword}%");
    }
}

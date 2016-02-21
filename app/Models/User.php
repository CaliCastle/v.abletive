<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'experience', 'user_id', 'display_name', 'role', 'avatar', 'expired_at', 'registered_at', 'description', 'slug'
    ];

    protected $perPage = 35;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'registered_at', 'expired_at',
    ];

    /**
     * Search users
     *
     * @param $keyword
     * @return mixed
     */
    public static function search($keyword)
    {
        return static::where('display_name', 'like', "%{$keyword}%")->orWhere('description', 'like', "%{$keyword}%");
    }

    /**
     * Profile href link
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function profileLink()
    {
        return $this->slug == "" || !isset($this->slug) ? url('@' . urlencode($this->display_name)) : url('@' . $this->slug);
    }

    /**
     * Get user by the profile url
     *
     * @param $url
     * @return mixed
     */
    public static function getUser($url)
    {
        $user = static::where('slug', urldecode($url))->first();
        if (!isset($user)) {
            $user = static::where('display_name', urldecode($url))->first();
        }
        return $user;
    }

    /**
     * Is the user a manager?
     *
     * @return bool
     */
    public function isManager()
    {
        return $this->role == "Admin";
    }

    /**
     * Is the user a tutor?
     *
     * @return bool
     */
    public function isTutor()
    {
        return $this->role == "Tutor";
    }

    /**
     * Format experience number
     *
     * @param $value
     * @return string
     */
    public function getExperienceAttribute($value)
    {
        return number_format($value);
    }

    /**
     * Scope a query to tutor first order
     *
     * @param $query
     * @return mixed
     */
    public static function scopeTutorsFirst($query)
    {
        return $query->orderBy('role', 'desc');
    }

    /**
     * User's comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * Super comments
     *
     * @return mixed
     */
    public function superComments()
    {
        return $this->comments()->where('parent_id', 0)->latest();
    }

    /**
     * Comments for profile use
     *
     * @return mixed
     */
    public function profileComments()
    {
        return $this->superComments()->take(10)->get();
    }

    /**
     * User's lessons
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany('App\Video');
    }

    /**
     * Lessons for profile use
     *
     * @return mixed
     */
    public function profileLessons()
    {
        return $this->lessons()->latest()->take(8)->get();
    }

    /**
     * User' subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscription()
    {
        return $this->hasOne('App\Subscription');
    }

    /**
     * Subscribe action
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function subscribe()
    {
        return $this->subscription()->create(["user_id" => $this->id]);
    }

    /**
     * Unsubscribe
     *
     * @return mixed
     */
    public function unsubscribe()
    {
        return $this->subscription->delete();
    }

    /**
     * If the user subscribed
     *
     * @return bool
     */
    public function subscribed()
    {
      return !is_null($this->subscription);
    }

    /**
     * User's level
     *
     * @return string
     */
    public function level()
    {
        return trans('app/levels.' . Level::levelByXp($this->attributes["experience"]));
    }

    /**
     * Scope a query to users registered today
     *
     * @param $query
     * @return mixed
     */
    public function scopeJustRegistered($query)
    {
        return $query->where('created_at', 'like', Carbon::today()->format("Y-m-d") . "%");
    }

    /**
     * One's watch laters
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function watchLaters()
    {
        return $this->belongsToMany('App\Video', 'user_watch_laters');
    }

    /**
     * Add a lesson to watch laters
     *
     * @param Video $lesson
     * @return string
     */
    public function watchLaterLesson(Video $lesson)
    {
        $added = $this->hasWatchLater($lesson);
        $added ? $this->watchLaters()->detach($lesson->id) : $this->watchLaters()->attach($lesson->id);

        return $added ? trans('messages.un_watch_later_success') : trans('messages.watch_later_success');
    }

    /**
     * Add a series to watch laters
     *
     * @param Series $series
     */
    public function watchLaterSeries(Series $series)
    {
        foreach ($series->lessons as $lesson) {
            if (!$this->hasWatchLater($lesson))
                $this->watchLaters()->attach($lesson->id);
        }
    }

    /**
     * Remove a series from watch laters
     *
     * @param Series $series
     */
    public function unwatchLaterSeries(Series $series)
    {
        foreach ($series->lessons as $lesson) {
            if ($this->hasWatchLater($lesson))
                $this->watchLaters()->detach($lesson->id);
        }
    }

    /**
     * If the user has the lesson in watch laters
     *
     * @param Video $lesson
     * @return bool
     */
    public function hasWatchLater(Video $lesson)
    {
        return in_array($lesson->id,$this->watchLaters()->lists('video_id')->toArray());
    }

    /**
     * If the user has the entire series in watch laters
     *
     * @param Series $series
     * @return bool
     */
    public function hasWatchLaterSeries(Series $series)
    {
        if ($series->lessons->count() == 0)
            return false;

        foreach ($series->lessons as $lesson) {
            if (!$this->hasWatchLater($lesson))
                return false;
        }
        return true;
    }

    /**
     * Completed lessons
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function watchedLessons()
    {
        return $this->belongsToMany('App\Video', 'user_watched');
    }

    /**
     * Watched a lesson
     *
     * @param Video $lesson
     * @return bool
     */
    public function watched(Video $lesson)
    {
        $level = $this->level();
        // Add XP
        $this->update(["experience" => intval($this->attributes["experience"]) + $lesson->experience]);

        if (!$this->hasWatched($lesson)) {
            $this->watchedLessons()->attach($lesson->id);
        }

        return $this->level() != $level;
    }

    /**
     * If the user has watched this lesson
     *
     * @param Video $lesson
     * @return bool
     */
    public function hasWatched(Video $lesson)
    {
        return in_array($lesson->id, $this->watchedLessons()->lists('video_id')->toArray());
    }

    /**
     * The completed percentage of current series
     *
     * @param Series $series
     * @return int
     */
    public function completedPercent(Series $series)
    {
        if ($series->lessons->count() == 0)
            return 0;

        $ids = $series->lessons()->lists('id')->toArray();
        $watched_lessons = Video::whereHas('watchedUsers', function ($query) use ($ids) {
            return $query->whereIn('video_id', $ids);
        })->get();

        return intval(($watched_lessons->count() / $series->lessons->count()) * 100);
    }

    /**
     * One's favorite lessons
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteLessons()
    {
        return $this->belongsToMany('App\Video', 'user_favorites');
    }

    /**
     * Add a lesson to favorites
     *
     * @param Video $lesson
     * @return string
     */
    public function favoriteLesson(Video $lesson)
    {
        $added = $this->hasFavorite($lesson);
        $added ? $this->favoriteLessons()->detach($lesson->id) : $this->favoriteLessons()->attach($lesson->id);

        return $added ? trans('messages.un_favorite_success') : trans('messages.favorite_success');
    }

    /**
     * Add a series to favorites
     *
     * @param Series $series
     */
    public function favoriteSeries(Series $series)
    {
        foreach ($series->lessons as $lesson) {
            if (!$this->hasFavorite($lesson))
                $this->favoriteLessons()->attach($lesson->id);
        }
    }

    /**
     * Remove a series from favorites
     *
     * @param Series $series
     */
    public function unfavoriteSeries(Series $series)
    {
        foreach ($series->lessons as $lesson) {
            if ($this->hasFavorite($lesson))
                $this->favoriteLessons()->detach($lesson->id);
        }
    }

    /**
     * If the user has the lesson in favorites
     *
     * @param Video $lesson
     * @return bool
     */
    public function hasFavorite(Video $lesson)
    {
        return in_array($lesson->id,$this->favoriteLessons()->lists('video_id')->toArray());
    }

    /**
     * If the user has the entire series in favorites
     *
     * @param Series $series
     * @return bool
     */
    public function hasFavoriteSeries(Series $series)
    {
        if ($series->lessons->count() == 0)
            return false;

        foreach ($series->lessons as $lesson) {
            if (!$this->hasFavorite($lesson))
                return false;
        }
        return true;
    }

    /**
     * User's series notifications
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function notifications()
    {
        return $this->belongsToMany('App\Series', 'user_notifications');
    }

    /**
     * Set notifications
     *
     * @param Series $series
     * @return string
     */
    public function notifySeries(Series $series)
    {
        $added = $this->hasNotified($series);
        $added ? $this->notifications()->detach($series->id) : $this->notifications()->attach($series->id);

        return $added ? trans('messages.un_notified') : trans('messages.notified');
    }

    /**
     * If the user has notified the series
     *
     * @param Series $series
     * @return bool
     */
    public function hasNotified(Series $series)
    {
        return in_array($series->id,$this->notifications()->lists('series_id')->toArray());
    }

    /**
     * User's liked comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likedComments()
    {
        return $this->belongsToMany(Comment::class, 'comment_likes');
    }

    /**
     * Has the user liked the comment
     *
     * @param Comment $comment
     * @return bool
     */
    public function likedComment(Comment $comment)
    {
        return in_array($comment->id, $this->likedComments()->lists('comment_id')->toArray());
    }

    /**
     * Refresh user's data
     *
     * @return bool
     */
    public function refresh()
    {
        return $this->getUserDataFromApi();
    }

    /**
     * Is the user a valid subscriber
     *
     * @return bool
     */
    public function validSubscription()
    {
        return $this->role != "Member";
    }

    /**
     * Get user's data from our api
     *
     * @return bool if the user is a valid member
     */
    protected function getUserDataFromApi()
    {
        $curl = curl_init("http://abletive.com/api/user/get_user_profile/?user_id=" . $this->user_id);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);

        $json = json_decode($data);

        curl_close($curl);

        if ($json->status != "ok") {
            return false;
        }

        $result = $this->checkMembershipExpired();

        if ($result) {
            $expired_at = $result;

            $this->update([
                "email" => $json->private_info->email,
                "expired_at" => $expired_at,
                "role" => $expired_at <= Carbon::now() ? "Member" : "Subscriber",
                "avatar" => $this->filterImageTag($json->public_info->avatar),
                "display_name" => $json->public_info->display_name,
                "description" => $json->public_info->description,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Update the profile
     *
     * @param $attributes
     * @return bool
     */
    public function updateProfile($attributes)
    {
        $url = "http://abletive.com/api/user/update_user_profile/?user_id=" . $this->user_id
            . "&email_address=" . $attributes["email"] . "&description=" . urlencode($attributes["description"])
            . "&display_name=" . urlencode($attributes["display_name"]);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);

        $json = json_decode($data);

        curl_close($curl);

        if ($json->status != "ok") {
            return false;
        }
        if (isset($attributes["password"]) && $attributes["password"] != "") {
            $curl = curl_init("http://abletive.com/api/user/update_user_password/?user_id=" . $this->user_id . "&pass1=" . urlencode($attributes["password"]) . "&pass2=" . urlencode($attributes["password"]));
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($curl);

            $json = json_decode($data);

            curl_close($curl);

            if ($json->status != "ok") {
                return false;
            }
            return true;
        }

        return true;
    }

    /**
     * Check to see if membership expired
     *
     * @return bool
     */
    public function expired()
    {
        return $this->expired_at <= Carbon::now();
    }

    /**
     * Check to see if the user membership has expired
     *
     * @return bool|Carbon
     */
    protected function checkMembershipExpired()
    {
        $ch = curl_init("http://abletive.com/api/user/membership/?user_id=" . $this->user_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($result);

        if ($json->status == "ok" && $json->member->endTime != "N/A") {
            $expired_at = Carbon::parse($json->member->endTime);
            if ($expired_at >= Carbon::now()) {
                return $expired_at;
            }
        }
        return Carbon::now();
    }

    /**
     * Get src from the image tag
     *
     * @param $html
     * @return mixed
     */
    protected function filterImageTag($html)
    {
        if (!str_contains($html, '<img src')) {
            return $html;
        }
        $matches = array();
        $pattern ='<img.*?src="(.*?)">';
        preg_match($pattern,$html,$matches);
        return $matches[1];
    }
}

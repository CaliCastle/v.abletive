<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        "name", "thumbnail", "description"
    ];

    /**
     * Skill's series
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function series()
    {
        return $this->belongsToMany(Series::class);
    }

    /**
     * Skill's lessons count
     *
     * @return int
     */
    public function lessonsCount()
    {
        $lessons_count = 0;
        foreach ($this->series as $series) {
            $lessons_count += $series->lessons->count();
        }
        return $lessons_count;
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function skillByName($name)
    {
        return static::where('name', $name)->first();
    }

    /**
     * The first lesson of the skill
     *
     * @return mixed
     */
    public function firstLesson()
    {
        if ($this->series()->count() == 0) {
            return false;
        }
        $series = $this->series()->first();

        if ($series->lessons()->count() == 0) {
            return false;
        }

        return $series->lessons()->first();
    }
}

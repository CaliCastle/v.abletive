<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{

    protected $fillable = [
        "title"
    ];

    /**
     * Relationship to its passed users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function passedUsers()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Relationship to its questions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}

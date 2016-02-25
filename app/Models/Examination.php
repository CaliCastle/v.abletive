<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{

    protected $fillable = [
        "title"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function passedUsers()
    {
        return $this->belongsToMany(User::class);
    }
}

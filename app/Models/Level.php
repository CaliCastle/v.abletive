<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    /**
     * Get level's name by experience
     *
     * @param $experience
     * @return string
     */
    public static function levelByXp($experience)
    {
        return last(static::where('experience',
            '<=',
            $experience)
            ->lists('name')
            ->toArray());
    }
}

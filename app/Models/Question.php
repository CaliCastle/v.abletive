<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = "examination_questions";

    protected $fillable = [
        "title"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    /**
     * Get the answer
     *
     * @param $i
     * @return null
     */
    public function getAnswer($i)
    {
        if ($this->answers()->count()) {
            return $this->answers[$i-1];
        }

        return new Answer;
    }
}

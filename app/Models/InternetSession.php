<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class InternetSession extends Model
{


    /**
     * The fields who are mass assignable
     *
     * @var string
     */
    protected $guarded = [];

    /**
     * messages
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function key()
    {
        return $this->belongsTo(Key::class);
    }
}

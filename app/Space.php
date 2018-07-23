<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    public function routine()
    {
        return $this->hasMany(Routine::class);
    }
    protected $table = 'm_space';
}

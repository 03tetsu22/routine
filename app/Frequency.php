<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
    public function routine()
    {
        return $this->hasMany(Routine::class);
    }
    protected $table = 'm_frequency';
    protected $fillable = ['id', 'frequency'];
}

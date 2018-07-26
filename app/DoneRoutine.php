<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoneRoutine extends Model
{
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'm_staff_id');
    }
    public function pt()
    {
        return $this->belongsTo(Point::class, 'point');
    }
    protected $table = 't_done_routine';
}

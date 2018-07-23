<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoneRoutine extends Model
{
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'm_staff_id');
    }
    protected $table = 't_done_routine';
}

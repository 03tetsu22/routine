<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    public function space()
    {
        return $this->belongsTo(Space::class, 'm_space_id');
    }
    public function frequency()
    {
        return $this->belongsTo(frequency::class, 'm_frequency_id');
    }
    public function pt()
    {
        return $this->belongsTo(point::class, 'point');
    }
    protected $table = 'm_routine';
    protected $fillable = ['routine_name','point','m_space_id','m_frequency_id'];
}

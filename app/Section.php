<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = [];

    public function blocks()
    {
        return $this->hasMany('App\Block', 'section_code', 'code');
    }
}

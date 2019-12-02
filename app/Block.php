<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $guarded = [];

    public function section()
    {
        return $this->hasOne('App\Section', 'code', 'section_code');
    }


}

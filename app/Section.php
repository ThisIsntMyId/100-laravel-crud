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

    public function page()
    {
        return $this->hasOne('App\Page', 'pageID', 'pageID');
    }
}

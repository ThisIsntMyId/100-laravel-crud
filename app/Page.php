<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'pageId';


    public function sections()
    {
        return $this->hasMany('App\Section', 'pageID', 'pageID');
    }
}

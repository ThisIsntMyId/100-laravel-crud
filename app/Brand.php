<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $guarded=[];

    public $searchable_fields = ['name', 'slug', 'description', 'h1_tag', 'h2_tag', 'meta_title', 'meta_desc', 'meta_kw'];
}

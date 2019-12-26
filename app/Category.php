<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public $searchable_fields = ['name', 'slug', 'description', 'h1_tag', 'h2_tag', 'meta_title', 'meta_desc', 'meta_kw'];

    public function children()
    {
        return $this->hasMany('App\Category', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Category', 'parent_id');
    }

    public function recursiveChildren()
    {
        return $this->children()->orderBy('name')->with('recursiveChildren');
    }
}

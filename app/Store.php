<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $guarded=[];

    public $searchable_fields = ['name', 'about_store', 'sp_terms_title', 'sp_terms', 'cb_terms', 'secrets', 'h1_tag', 'h2_tag', 'meta_title', 'meta_desc', 'meta_kw', 'homepage'];
}

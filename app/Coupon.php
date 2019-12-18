<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $guarded = [];

    public $searchable_fields = ['title', 'description', 'promo_text', 'code', 'link'];
}
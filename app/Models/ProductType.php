<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
    protected $table =  "product_types";
    use SoftDeletes;
   
}

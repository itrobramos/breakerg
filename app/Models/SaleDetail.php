<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{
    protected $table =  "sale_details";

    public function product(){
        return $this->belongsTo(Product::class,'productId','id');
    }
   
}

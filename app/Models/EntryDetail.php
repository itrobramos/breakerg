<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryDetail extends Model
{
    protected $table =  "entry_details";

    public function product(){
        return $this->belongsTo(Product::class,'productId','id');
    }
   
}

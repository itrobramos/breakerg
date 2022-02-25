<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use SoftDeletes;

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplierId','id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class,'userId','id')->withTrashed();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;
    public function client(){
        return $this->belongsTo(Client::class,'clientId','id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class,'userId','id')->withTrashed();
    }

}

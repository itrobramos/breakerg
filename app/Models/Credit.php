<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model 
{
    public $timestamps = false;
    public function client(){
        return $this->belongsTo(Client::class,'clientId','id')->withTrashed();
    }

    public function sale(){
        return $this->belongsTo(Sale::class,'saleId','id')->withTrashed();
    }
}

<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class chats extends Model
{
    protected $fillable=['sender_id','reciver_id','path'];
    
    public function user()
    {
        return $this->belongsTo('App\User','sender_id');
    }

    public function people()
    {
        return $this->belongsTo('App\User','reciver_id');
    }
}

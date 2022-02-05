<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
   protected $fillable=['user_id','post_id','comment'];
   public function user()
   {
       return $this->belongsTo('App\User', 'user_id');
   }
   public function posts()
   {
      return $this->belongsTo(posts::class,'post_id');
   }

}

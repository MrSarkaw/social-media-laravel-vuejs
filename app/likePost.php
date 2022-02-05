<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\posts;
class likePost extends Model
{
   protected $fillable=['post_id','user_id'];
   public function user()
   {
      return $this->belongsTo(User::class,'user_id');
   }

   public function posts()
   {
      return $this->belongsTo(posts::class,'post_id');
   }

}

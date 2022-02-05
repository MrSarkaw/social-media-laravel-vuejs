<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\likePost;
use App\comment;

class posts extends Model
{
    protected $fillable=['path','title','user_id'];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function UserLike(){
        return $this->belongsToMany(User::class,'like_posts','post_id','user_id')->withTimestamps();
    }
    public function likes(){
        return $this->hasMany(likePost::class,'post_id');
    }
    public function comments(){
        return $this->hasMany(comment::class,'post_id');
    }

    public function comment(){
        return $this->belongsToMany(User::class,'comments','post_id','user_id')->withTimeStamps();
    }
}

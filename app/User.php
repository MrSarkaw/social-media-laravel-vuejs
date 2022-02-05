<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\posts;
use App\follow;
class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','path','bio','private'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(){
        return $this->hasMany(posts::class);
    }

    public function postsLike()
    {
        return $this->belongsToMany(posts::class, 'like_posts', 'user_id', 'post_id')->withTimestamps();
    }

    public function comment(){
        return $this->belongsToMany(posts::class,'comments','user_id','post_id')->withTimeStamps();
    }
    public function follow(){
        return $this->hasMany(follow::class,'sender_id');
    }
    public function chat(){
        return $this->hasMany(follow::class,'sender_id');
    }
    
   
}

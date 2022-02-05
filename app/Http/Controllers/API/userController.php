<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\follow;
use App\posts;
use App\likePost;
use App\chats;
use App\comment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth:api');
    }
    
    public function index()
    {
        $userid=auth('api')->user()->id;
        return User::latest()->where('id','<>',$userid)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|string|max:30',
            'email'=>'required|email|max:30|unique:users',
            'password'=>'required|min:8'
        ]);
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    public function profile()
    {
         $user_id=auth('api')->user()->id;       
         $countFollower=count(follow::where('reciver_id',$user_id)->where('check',1)->get());
         $countFollowing=count(follow::where('sender_id',$user_id)->where('check',1)->get());
         $user=User::with(['posts','follow'])->where('id',$user_id)->get();
        return response()->json(array($user,$countFollower,$countFollowing)) ;
    }

  

    public function updateProfile(Request $request){
        $user=auth('api')->user();
        if($user->private != $request->private){
            follow::where('reciver_id',$user->id)->where('check','0')->update(['check'=>1]);
        }
        $this->validate($request,[
            'name'=>'required|string|max:30',
            'email'=>'required|email|max:30|unique:users,email,'.$user->id,
            'password'=>'sometimes|required|min:8',
            'bio'=>'string|nullable'
        ]);

            if(!empty($request->password)){
                $request->merge(['password'=>Hash::make($request->password)]);
            }
        User::find($user->id)->update($request->all());
    }  
    

    public function updateImage(Request $request){
    
        $user=auth('api')->user();
        $oldphoto=$user->path;
            $name=time().'.'.explode('/',explode(':',substr($request->path,0,strpos($request->path,';')))[1])[1];
            \Image::make($request->path)->save(public_path('wallpaper/').$name);

            $oldphoto=public_path('wallpaper/').$oldphoto;
            if(file_exists($oldphoto)){
                @unlink($oldphoto);
            }
            $request->merge(['path'=>$name]);
            User::find($user->id)->update($request->all());
          
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user= User::find($id);
        $this->validate($request,[
            'name'=>'required|string|unique|max:30',
            'email'=>'required|email|max:30|unique:users,email,'.$id,
            'password'=>'sometimes|required|min:8',
            'bio'=>'string|nullable'
        ]);
        $oldName=$user->path;
        if($request->path != $oldName){
            $name=time().'.'.explode('/',explode(':',substr($request->path,0,strpos($request->path,';')))[1])[1];
            \Image::make($request->path)->save(public_path('wallpaper/').$name);
            $request->merge(['path'=>$name]);
            $oldName=public_path('wallpaper/').$oldName;
            if(file_exists($oldName)){
                @unlink($oldName);
            }
        }
        if(!empty($request->password)){
            $request->merge(['password'=>Hash::make($request->password)]);
        }

        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        chats::where('sender_id',$id)->orWhere('reciver_id',$id)->delete();
        comment::where('user_id',$id)->delete();
        follow::where('sender_id')->orWhere('reciver_id',$id)->delete();
        likePost::where('user_id',$id)->delete();
        posts::where('user_id',$id)->delete();
        
        User::find($id)->delete();
    }


    public function currentUser(){
        return auth('api')->user()->id;
    }

      //peopleProfile
      public function peopleProfile($name)
      {
              $currentUser=auth('api')->user();
              $user=User::where('name',$name)->with(['posts','follow'])->first();  
              if($user){
                $countFollower=count(follow::where('reciver_id',$user->id)->where('check',1)->get());
                $countFollowing=count(follow::where('sender_id',$user->id)->where('check',1)->get());
                 return response()->json(array($user,$countFollower,$currentUser,$countFollowing)) ;
              }
              return [];

              
      }

    //people For Follow
      public function followPublic()
      {
        $ids=[];
        $currentUser=auth('api')->user();
        $whoIfollowed=follow::where('sender_id',$currentUser->id)->get();
        foreach($whoIfollowed as $row){
            array_push($ids,$row->reciver_id);
        }
        array_push($ids,$currentUser->id);
       return  $newUser=User::latest()->whereNotIn('id',$ids)->paginate(12);
      }

      public function postsPublic()
      {
        $ids=[];
        $userPrivate=User::where('private',1)->get();
            foreach($userPrivate as $row){
                array_push($ids,$row->id);
            }
        $post=posts::latest()->with(['user','likes','comments'])
                        ->whereNotIn('user_id',$ids)->paginate(20);
        $userId=auth('api')->id();
        return response()->json(array($post,$userId));
      }

      public function postsFriends()
      {
        $ids=[];
        $currentUser=auth('api')->id();
        array_push($ids,$currentUser);
        $following=follow::where('sender_id',$currentUser)->where('check',1)->get();
            foreach($following as $row){
                array_push($ids,$row->reciver_id);
            }
        $post=posts::latest()->with(['user','likes','comments'])
                        ->whereIn('user_id',$ids)->paginate(10);
        $userId=auth('api')->id();
        return response()->json(array($post,$currentUser));
      }

      public function search($name){
          $id=auth('api')->id();
        $user=User::where('name','like','%'.$name.'%')->where('id','<>',$id)->paginate(10);
        return $user;
      }
}

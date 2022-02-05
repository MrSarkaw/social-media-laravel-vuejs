<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\posts;
use App\likePost;
use App\User;
use App\follow;
class postsController extends Controller
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
        $posts= posts::orderByDesc('created_at')->with(['User','comment'])->paginate(10);
        return response()->json($posts);
    }   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->path){
               $name=time().'.'.explode('/',explode(':',substr($request->path,0,strpos($request->path,';')))[1])[1];
               \Image::make($request->path)->save(public_path('posts/').$name);
               
        auth('api')->user()->posts()->create([
            'path'=>$name,
            'title'=>$request->title,
        ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      
        $post=posts::where('id',$id)->with('likes')->first();
        return response()->json($post);
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
        posts::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        likePost::where('post_id',$id)->delete();

        $post=posts::find($id);
        $oldphoto=public_path('posts/').$post->path;
        if(file_exists($oldphoto)){
            @unlink($oldphoto);
        }
        @unlink($wallpaper);
        posts::find($id)->delete();
    }




    public function postForCurrentUser(){
        $user=auth('api')->user();
        $post= posts::latest()->where('user_id',$user->id)->with(['likes','comment'])->paginate(8);
        return response()->json($post);
    }

    public function peoplePost($name){
        $userID=User::where('name',$name)->first();
        if($userID->private==0){
            $post= posts::latest()->where('user_id',$userID->id)
                            ->with(['likes','comment'])->paginate(8);
            return response()->json($post);    
        }else{
          $thisUser=auth('api')->id();
        $checkForPost=follow::where('sender_id',$thisUser)
                                ->where('reciver_id',$userID->id)->first();
        if($checkForPost){
           if($checkForPost->check==1){
            $post= posts::latest()->where('user_id',$userID->id)
                            ->with(['likes','comment'])->paginate(8);
            return response()->json($post);    
            }  
        }  
        }
        
       

        
    }

    
}

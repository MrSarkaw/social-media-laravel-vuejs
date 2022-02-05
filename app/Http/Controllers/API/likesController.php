<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\likePost;
use App\User;
use Response;

class likesController extends Controller
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
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        $idUser=auth('api')->id();
        $check=false;
        $post=likePost::where('post_id',$request->id)->where('user_id',$idUser)->get();
        if(count($post)>0){
           return  likePost::where(['post_id'=>$request->id , 'user_id'=>$idUser])->delete();
        }
        return User::find($idUser)->postsLike()->attach($request->id);
         

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $idUser=auth('api')->id();
        $check='';
        $postCheck=likePost::where('post_id',$id)->where('user_id',$idUser)->get();
        if(count($postCheck)>0){
            $check='true';
        }
         $post=likePost::where('post_id',$id)->get();
         $count=count($post);
         return Response::json(array(
            $post,
            $check,
            $count,
        ));
    }


     public function show2($id)
    {
        return $user=likePost::where('post_id',$id)->with('user')->paginate(25);
    }

    public function checkLike($id)
    {
        $check=0;
       $user=auth('api')->id();
       $like=likePost::where('post_id',$id)->where('user_id',$user)->get();
       if(count($like)==1){
        $check=1;
       }
       return $check;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}

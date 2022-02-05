<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\follow;
use App\User;
use App\likePost;
use App\posts;
use App\comment;
use Illuminate\Http\Request;

class followController extends Controller
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
    public function getFollowers(){
        $userId=auth('api')->id();
        return follow::with('user')->latest()->where('reciver_id',$userId)->where('check',1)->paginate(20); 
    }

    public function getPeopleFollowers($id){
        $currentUser=User::findOrFail($id);
        if($currentUser->private==0){
            return  follow::with('user')->latest()
                           ->where('reciver_id',$id)->where('check',1)->paginate(20); 
        }
        else{
            $userId=auth('api')->id();
            $checkForFollow= follow::where('reciver_id',$id)
                                        ->where('sender_id',$userId)->first();
            if($checkForFollow){
                if($checkForFollow->check==1){
                                 return  follow::with('user')->latest()
                                ->where('reciver_id',$id)->where('check',1)->paginate(20); 
                 }
            }
            
        }
    }

    public function getFollowing(){
        $userId=auth('api')->id();
        return follow::with('people')->latest()->
        where('sender_id',$userId)->where('check',1)->paginate(20);  
    }   
    public function getPeopleFollowing($id){
        $currentUser=User::findOrFail($id);
        if($currentUser->private==0){
               return follow::with('people')->latest()
                               ->where('sender_id',$id)
                               ->where('check',1)->paginate(20); 
        }
        else{
      $userId=auth('api')->id();
        $checkForFollow= follow::where('reciver_id',$id)
                                  ->where('sender_id',$userId)->first();
        if($checkForFollow){
            if($checkForFollow->check==1){
                return follow::with('people')->latest()
                                 ->where('sender_id',$id)
                                 ->where('check',1)->paginate(20); 
            }
        }    
      }
       
    }

    public function deleteMaFollower($id)
    {
      follow::find($id)->delete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
       $userId=auth('api')->id();
       $checkIfFollow=follow::where('sender_id',$userId)->where('reciver_id',$request->id)->first();
       if($checkIfFollow){
        follow::where('sender_id',$userId)->where('reciver_id',$request->id)->delete();
       }else{
           $check=0;
           $user=User::find($request->id);
           if($user->private!=1){
            $check=1;
           }
           follow::create(['sender_id'=>$userId,'reciver_id'=>$request->id,'check'=>$check]);
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function show(follow $follow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        follow::find($id)->update(['check'=>1]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\follow  $follow
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        follow::find($id)->delete();
    }


    public function checkFollow($id){
       $isFollow=0;
       $check=User::find($id)->private;
       $user=auth('api')->id();
        if($id==$user){
            $isFollow=3;
        }else{
        $follow=follow::where('reciver_id',$id)->where('sender_id',$user)->first();
        if($follow){
            if($follow->check==1){
                $isFollow=2;
                $check=0;
            }else{
                $isFollow=1;
            }
        }   
      }
       

      
      return response()->json(array($check,$isFollow));
    }

    //notofication
    public function notification(){
        $arrayId=[];
        $arrayComment=[];
        $user=auth('api')->id();    

        $posts=posts::where('user_id',$user)->get();
        foreach($posts as $rows){
            array_push($arrayId,$rows->id);
        }
        
        $allNotifi=[];
        $follow= follow::with('user')->latest()->where('reciver_id',$user)->where('check',1)->paginate(10);
        $like=likePost::with(['user','posts'])->latest()
                        ->whereIn('post_id',$arrayId)->where('user_id','<>',$user)->paginate(10);
        $comment=comment::with(['user','posts'])->latest()
                            ->whereIn('post_id',$arrayId)->where('user_id','<>',$user)->paginate(10);
        return response()->json(array($follow,$like,$comment)); 
    }
    public function notifiRequest()
    {
        $allArray=[];
        $user=auth('api')->id();    
        $requestFollow= follow::with('user')->latest()
                 ->where('reciver_id',$user)->where('check',0)->paginate(10);
        $requestFollow2= follow::with('user')->latest()
                 ->where('reciver_id',$user)->where('check',0)->get();
          $countRequest=count($requestFollow2);
        return array($requestFollow,$countRequest);

    }
}

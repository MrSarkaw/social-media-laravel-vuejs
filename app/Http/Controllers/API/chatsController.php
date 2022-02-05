<?php

namespace App\Http\Controllers\API;
use App\Events\chatEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\chats;
use App\User;
class chatsController extends Controller
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
        $this->validate($request,[
            'path'=>'required|string'
        ]);
     $userId =auth('api')->user()->id;
     $message=chats::create([
         'sender_id'=>$userId,
         'reciver_id'=>$request->reciver_id,
         'path'=>$request->path
     ]);

     event(new chatEvent($message));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $id=User::where('name',$name)->first();
        $userId=auth('api')->user()->id;
        if($id){
        $chat= chats::latest()->where('reciver_id',$id->id)->where('sender_id',$userId)->
         orWhere('sender_id',$id->id)->where('reciver_id',$userId)->paginate(10);
         return response()->json(array($chat,$id,$userId));
        }
        
        return [];
       
    }

    public function oldMessage(){
        $id=auth('api')->id();
        $checkSender=0;
        $idSender=[];
        $checkReciver=0;
        $idReciver=[];
        $chatIrecived=chats::latest()->where('reciver_id',$id)->get();
        $chatIsent=chats::latest()->where('sender_id',$id)->get();
        foreach($chatIrecived as $row ){
            foreach($idSender as $row2){
                if($row->sender_id==$row2){
                    $checkSender=1;
                    break;
                }
            }
            if($checkSender==0){
             array_push($idSender,$row->sender_id);  
            }   
            $checkSender=0;
        }
        foreach( $chatIsent as $row){
            foreach($idReciver as $row2){
                if($row->reciver_id==$row2){
                    $checkReciver=1;
                    break;
                }
            }
            if($checkReciver==0){
             array_push($idReciver,$row->reciver_id);  
            }
            $checkReciver=0;
        }
       
        $query=User::whereIn('id',$idSender)
                     ->orWhereIn('id',$idReciver)->paginate(10);
         return response()->json($query);
        
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
        //
    }
}

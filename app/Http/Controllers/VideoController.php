<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use App\Video;
use App\Comment;

class VideoController extends Controller
{
    public function createVideo(){
      return view('video.createVideo');
    }

    public function saveVideo(Request $request){
      //validar formulario
      $validateData = $this->validate($request,[
        'title' => 'required|min:5',
        'description' =>'required',
        'video' => 'mimes:mp4'
        ] );
        $video = new Video();
        $user = \Auth::user();
        $video->user_id = $user->id;
        $video->title = $request->input('title');
        $video->description = $request->input('description');

        //Subida de la Miniatura
        $image = $request->file('image');
        if($image){
          $image_path= time().$image->getClientOriginalName();
          \Storage::disk('images')->put($image_path, \File::get($image));
          $video ->image = $image_path;
        }
        //Subida de videos
        $video_file = $request->file('video');
        if($video_file){
          $video_path = time().$video_file->getClientOriginalName();
          \Storage::disk('videos')->put($video_path, \File::get($video_file));

          $video->video_path = $video_path;

        }

        $video->save();

        return redirect()->route('home')->with(array(
          'message'=> 'El video se ha subido correctamente'
        ));
    }

    public function getImage($filename){
      $file = Storage::disk('images')->get($filename);
      return new Response($file, 200);
    }

    public function getVideoPage($video_id)
    {
      $video = Video::find($video_id);
      return view('video.detail', array(
        'video' => $video
      ));
    }

    public function getVideo($filename){
      $file = Storage::disk('videos')->get($filename);
      return new Response($file, 200);

    }

    public function delete($video_id) {
      $user = \Auth::user();
      $video = Video::find($video_id);
      $comments= Comment::where('video_id', $video_id)->get();

      if ($user && $video->user_id == $user->id) {
        if($comments && count($comments)>=1){
          foreach ($comments as $comment) {
            $comment->delete();
          }

        }

        Storage::disk('images')->delete($video->image);
        Storage::disk('videos')->delete($video->video_path);
        $video-> delete();
        $message = array('message'=>'Video eliminado correctamente!!');

      }else{
        $message = array('message'=>'Video no ha podido eliminarse !!');
      }
      return redirect()->route('home')->with($message);
    }

    public function edit($video_id){
      $user = \Auth::user();
      $video = Video::findOrFail($video_id);
      if ($user && $video->user_id == $user->id) {

        return view('video.edit', array('video'=> $video));
      }else{
        return redirect()->route('home')->with($message);
      }
    }

    public function update($video_id, Request $request){
      $validateData = $this->validate($request,[
        'title' => 'required|min:5',
        'description' =>'required',
        'video' => 'mimes:mp4'
        ] );
        $user = \Auth::user();
        $video= Video::findOrFail($video_id);
        $video->user_id = $user->id;
        $video->title = $request->input('title');
        $video->description = $request->input('description');

        $image = $request->file('image');
        if($image){
          $image_path= time().$image->getClientOriginalName();
          \Storage::disk('images')->put($image_path, \File::get($image));
          $video ->image = $image_path;
        }
        //Subida de videos
        $video_file = $request->file('video');
        if($video_file){
          $video_path = time().$video_file->getClientOriginalName();
          \Storage::disk('videos')->put($video_path, \File::get($video_file));

          $video->video_path = $video_path;
        }
        $video ->update();
        return redirect()->route('home')->with(array('message'=> 'El video se ha actualizado'));
    }



    public function search($search = null, $filter = null){
      if(is_null($search)){
        $search = \Request::get('search');
        if(is_null($search)){

        return redirect()->route('home');
      }}

      if(is_null($filter) && \Request::get('filter') && !is_null($search)){
        $filter = \Request::get('filter');
        return redirect()->route('videoSearch', array('search'=>$search, 'filter' =>$filter));
      }
      $column = 'id';
      $order = 'desc';
      if(!is_null($filter)){
        if ($filter == 'new') {
          $column ='id';
          $order = 'desc';
              }
        if ($filter == 'old') {
          $column ='id';
          $order = 'asc';
            }
        if ($filter == 'alfa') {
          $column ='title';
          $order = 'asc';
        }
      }
      $video = Video::where('title', 'LIKE', '%'.$search.'%')->orderBy($column, $order)->paginate(5);

      return view('video.search', array(
        'videos' => $video,
        'search' => $search
      ));
    }
  }

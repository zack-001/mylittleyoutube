@extends('layouts.app')

@section('content')

<div class="col-md-10 col-md-offset-1">
  <h2>{{$video->title}}</h2><hr>
  <div class="col-md-12">
    <!-- video -->
    <video controls id="video-player">
     <source src="{{route('fileVideo', ['filename'=> $video->video_path])}}"></source>
       Tu navegador no funciona con HTML5
    </video>
  </div>

  <div class="panel panel-default video-data">
    <div class="panel-heading">
      <div class="panel-title">
        Subido por <strong><a href="{{route('channel', ['user_id'=>$video->user->id])}}">{{$video->user->name.' '.$video->user->surname}}</a></strong> {{\FormatTime::LongTimeFilter($video->created_at)}}
      </div>
    </div>
    <div class="panel-body">
      {{$video->description}}
    </div>


    @include('video.comments')
  </div>
</div>
@endsection

@extends('layouts.app');

@section('content')

<div class="container">
    <div class="row">
        <div class="container">

            <h2>Canal de {{$user->name.' '.$user->username}}</h2>
            <br>
          </div>
                    <div class="clearfix">
            @include('video.videosList')

        </div>
    </div>
</div>

@endsection

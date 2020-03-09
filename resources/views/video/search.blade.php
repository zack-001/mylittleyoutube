@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="container">
          <div class="col-md-4">
            <h2>BUSQUEDA: {{$search}}</h2>
            <br>
          </div>
          <div class="col-md-8">
            <form class="col-md-3 pull-right" action="{{url('/buscar/'.$search)}}" method="get">
              <label for="filter">Ordenar</label>
              <select name="filter" class="form-control">
                <option value="new">Más nuevo primero</option>
                <option value="old">Más antiguos primero</option>
                <option value="alfa">De la A a la Z</option>
              </select>
              <input type="submit"  value="Ordenar" class="btn-filter btn btn-sm btn-primary">
            </form>
          </div>
          <div class="clearfix">
            @include('video.videosList')
          </div>
        </div>
    </div>
</div>
@endsection

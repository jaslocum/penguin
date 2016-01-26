@extends('layout')

@section('content')

    <h1>Show Images</h1>
    <h2>{{$category_rec->category}} - {{$category_rec->category_rec_id}}</h2>
    <hr>
    <div class="row">
        <form method="post" action="/{{$category_rec->category}}/{{$category_rec->category_rec_id}}/images" class="dropzone">
            {{csrf_field()}}
            @include('errors.show')
        </form>
    </div>
    <hr>

@stop
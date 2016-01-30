@extends('layout')

@section('content')

    <h1>Add Image</h1>
    <hr>
    <div class="row">
        <form method="post" action="/images" enctype="multipart/form-data" class="col-md-6">
            @include('images.form')
            @include('errors.submission')
        </form>
    </div>
    <hr>

@stop
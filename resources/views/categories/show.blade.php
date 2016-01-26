@extends('layout')

@section('content')

    <h1>Add Images</h1>
    <hr>
    <div class="row">
        <form method="post" action="/categories" enctype="multipart/form-data" class="col-md-6">
            @include('categories.form')
            @include('errors.submission')
        </form>
    </div>
    <hr>

@stop
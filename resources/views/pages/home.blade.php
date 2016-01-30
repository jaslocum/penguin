@extends('layout')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1>Image Server</h1>
            <p>version 0.1</p>
            <br>
            <h2>Usage:</h2>
            <br>
            <p>Web interface to maintain images for a {category} and {category_rec_id} key pair:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{category_rec_id}</p>
            <br>
            <p>Return first image for a {category} and {category_rec_id} key pair:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{category_rec_id}/get</p>
            <p></p>
            <br>
            <p>Return image for a {category}, {category_rec_id} and {filename} key set:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{category_rec_id}/{filename}</p>
            <p></p>
            <br>
            <p>Web interface to define categories for images:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/category_definitions</p>
            <p></p>
        </div>
    </div>
@stop
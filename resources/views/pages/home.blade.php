@extends('layout')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1>Image Server</h1>
            <p>version 0.1</p>
            <br>
            <br>
            <p>Usage:</p>
            <br>
            <p>Web interface to maintain images in a category:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{category_rec_id}</p>
            <br>
            <p>Return first the image for a category:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{category_rec_id}/image</p>
            <p></p>
            <br>
            <p>Web interface to create categories for images:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/categories/create </p>
            <p></p>
        </div>
    </div>
@stop
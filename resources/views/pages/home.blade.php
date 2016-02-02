@extends('layout')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h2>Image Server</h2>
            <p>version 0.1</p>
            <img src="http://penguin.dev/penguin/penguinWithAttitude" alt="penguin with attitude" height="80" width="56">
            <h3>Usage:</h3>
            <br>
            <p>Web interface to maintain images for a {category} and {category_rec_id} key pair:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{category_rec_id}/update</p>
            <br>
            <p>Return first image for a {category} and {category_rec_id} key pair:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{category_rec_id}</p>
            <br>
            <p>Return image for a {category}, {category_rec_id} and {filename} key set:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{category_rec_id}/{filename}</p>
            <br>
            <p>Web interface to define categories for images:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/category_definitions</p>
            <p></p>
        </div>
    </div>
@stop
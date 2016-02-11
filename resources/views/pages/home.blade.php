@extends('layout')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h2>Image Server</h2>
            <p>version 0.1</p>
            <img src="{{Request::root()}}/penguin/penguinWithAttitude.jpg" alt="penguin with attitude" height="80" width="56">
            <h3>Usage:</h3>
            <br>
            <p>Web interface to maintain images for a {category} and {key} pair bucket:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{key}/update</p>
            <br>
            <p>Return first image in bucket for a {category} and {key} pair:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{key}</p>
            <br>
            <p>Return an image for a {filename} in a bucket with {category} and {key}:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;penguin/{category}/{category_rec_id}/{filename}</p>
            <br>
            <p>Web interface to define categories for images:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="category/">
                    penguin/category
                </a>
            </p>
            <p></p>
        </div>
    </div>
@stop
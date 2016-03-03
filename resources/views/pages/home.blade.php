@extends('layout')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h2>Image Server</h2>
            <p>version 0.1</p>
            <img src="{{Request::root()}}/penguin/penguin/penguinWithAttitude.jpg" alt="penguin with attitude" height="80" width="56">
            <h3>Usage:</h3>
            <br>
            <p>Web interface to maintain images for a {category} and {key} pair bucket:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{key}/update</p>
            <br>
            <p>Return first image in bucket for a {category} and {key} pair:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{key}</p>
            <br>
            <p>Return an image for a {filename} in a bucket with {category} and {key}:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{category_rec_id}/{filename}</p>
            <br>
            <p>Return an image by {image_id}:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/image/{image_id}</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{image_id}</p>
            <br>
            <p>Web interface to define categories for images:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="category/">
                    {{Request::root()}}/category
                </a>
            </p>
            <p></p>
        </div>
    </div>
@stop
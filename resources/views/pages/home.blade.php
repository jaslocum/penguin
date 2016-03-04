@extends('layout')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h2>Image Server</h2>
            <p>version 0.1</p>
            <img src="{{Request::root()}}/1" alt="penguin with attitude" height="80" width="56">
            <h3>Usage:</h3>
            <br>
            <p>Web interface to edit/create a {category}/{key} bucket or an {image} bucket:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{key}/edit[?description={description}]</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{image}/edit[?description={description}]</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/image/edit[?description={description}]</p>
            <br>
            <p>Return first image in a {category}/{key} bucket:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{key}</p>
            <br>
            <p>Return an image for a {filename} in a {category}/{key} bucket:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{key}/{filename}</p>
            <br>
            <p>Return an {image}:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{image}</p>
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
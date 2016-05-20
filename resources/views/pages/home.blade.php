@extends('layout')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h2>Image Server</h2>
            <p>version 0.1</p>
            <img src="{{Request::root()}}/1" alt="penguin with attitude" height="80" width="56">
            <h3>Usage:</h3>
            <br>
            <p>Web interface to edit|create a {category}/{key} bucket or an {image_id} bucket:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{key}/edit[?description={description}]</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{image_id}/edit[?description={description}]</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/create[?description={description}]</p>
            <br>
            <p>Return the first image or an image by filename from a {category}/{key} bucket
               or return an image from an {image_id} bucket:</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{key}[/{filename}]</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{image_id}</p>
            <br>
            <p>Delete an image from a  {category}/{key} bucket or an {image_id} bucket:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{key}/[{filename}/]destroy|delete</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{image_id}/destroy|delete</p>
            <br>
            <p>Restore an image from a  {category}/{key} bucket or an {image_id} bucket:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{category}/{key}/[{filename}/]restore|undelete</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{Request::root()}}/{image_id}/restore|undelete</p>
            <br>
            <p>Web interface to define a {category} for images:<p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="category/">
                    {{Request::root()}}/category
                </a>
            </p>
            <p></p>
        </div>
    </div>
@stop
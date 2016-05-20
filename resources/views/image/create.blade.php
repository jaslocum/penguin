@extends('layout')

@section('content')
    <h3>
        <br>
        <p>{image}: {{$id}}</p>
        <p>{category}: {{$category_rec->category}}</p>
        <p>{key}:{{$key}}</p>
        @if(strlen($description)>0)<p>{description}: {{$description}}</p>
        @endif
        <br>
    </h3>
    <hr>
    <div class="row">
        <form id="imageForm" method="post" action="/{{$id}}" class="dropzone" category="{{$category_rec->category}}" key="{{$key}}">
            {{csrf_field()}}
            @include('errors.show')
        </form>
    </div>
    <hr>
@stop

@section('scripts.footer')

    <script>
            Dropzone.options.imageForm = {
                init: function () {
                    this.on("addedfile",
                        function() {
                            //
                        }
                    );
                    this.on("complete",
                        function(){
                            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                                //alert("finished uploading...");
                            }
                            window.open(
                                    '{{Request::root()}}/{{$id}}/edit',
                                    '_self' // <- This is what makes it open in a new window.
                            )
                        }
                    );
                    this.on("removedfile",
                        function (file){
                            var category = document.getElementById("imageForm").getAttribute('category');
                            var key = document.getElementById("imageForm").getAttribute('key');
                            var filename = file.name;
                            var xhttp = new XMLHttpRequest();
                            xhttp.open("GET", '{{Request::root()}}'+'/{{$id}}/destroy', true);
                            xhttp.send();
                        }
                    );
                },
                maxFilesize: {{$category_rec->max_size_MB}},
                acceptedFiles: '{{$category_rec->mime}}',
                headers: {description: "{{$description}}"},
                addRemoveLinks: true,
                dictDefaultMessage: '',
            };


    </script>

@endsection

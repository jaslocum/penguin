@extends('layout')

@section('content')
    <h1>{{$bucket->category}} - {{$bucket->key}}</h1>
    <hr>key
    <div class="row">
        <form id="imageForm" method="post" action="/{{$bucket->category}}/{{$bucket->key}}" class="dropzone">
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
                    this.on("complete",
                        function(){
                            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                            //alert("finished uploading...");
                        }
                        }
                    );
                },
                maxFilesize: 24,
                acceptedFiles: '.jpg, .jpeg, .png, .pdf',
                addRemoveLinks: true,
            };
            var mockFile = {name:"KIMG0109.jpg", size:12345};
            imageForm.emit("addedfile",mockFile);
            imageForm.createThumbnailFromUrl("KIMG0109.jpg","http://penguin.dev/woimg/xyz/KIMG0109.jpg");
            // Make sure that there is no progress bar, etc...
            myDropzone.emit("complete", mockFile);

    </script>

@endsection

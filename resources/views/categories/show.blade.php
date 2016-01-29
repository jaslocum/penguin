@extends('layout')

@section('content')
    <h1>{{$category_rec->category}} - {{$category_rec->category_rec_id}}</h1>
    <hr>
    <div class="row">
        <form id="imageForm" method="post" action="/{{$category_rec->category}}/{{$category_rec->category_rec_id}}" class="dropzone">
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
                            alert("finished uploading...");
                        }
                        }
                    );
                },
                maxFilesize: 24,
                acceptedFiles: '.jpg, .jpeg, .png, .pdf',
                addRemoveLinks: true,
                maxFiles: 100
            };
    </script>

@endsection

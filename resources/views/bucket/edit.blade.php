@inject('image','App\Http\Utilities\Images')

@extends('layout')

@section('content')
    <h3>
        {{$category}}: {{$key}}@if(strlen($description)>0), {{$description}}@endif
    </h3>
    <hr>
    <div class="row">
        <form id="imageForm" method="post" action="/{{$category}}/{{$key}}" class="dropzone" category="{{$category}}" key="{{$key}}">
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
                        }
                    );
                    this.on("removedfile",
                        function (file){
                            var category = document.getElementById("imageForm").getAttribute('category');
                            var key = document.getElementById("imageForm").getAttribute('key');
                            var filename = file.name;
                            var xhttp = new XMLHttpRequest();
                            xhttp.open("GET", '{{Request::root()}}'+'/'+category+'/'+key+'/'+filename+'/destroy', true);
                            xhttp.send();
                        }
                    );
                    @foreach($image::images($bucket_id) as $image)
                            var file = {name:'{{$image->filename}}', size:'{{$image->size}}'};
                            this.options.addedfile.call(this, file);
                            file.previewElement.onclick=function()
                            {
                                window.open(
                                        '{{Request::root()}}/{{$image->id}}',
                                        '_blank' // <- This is what makes it open in a new window.
                                );
                            };
                            this.createThumbnailFromUrl(file,'{{Request::root()}}/{{$image->id}}');
                            // Make sure that there is no progress bar, etc...
                            this.options.complete.call(this, file);
                    @endforeach
                },
                maxFilesize: {{$category_rec->max_size_MB}},
                acceptedFiles: '{{$category_rec->mime}}',
                headers: {description: "{{$description}}"},
                addRemoveLinks: true,
                dictDefaultMessage: '',
            };


    </script>

@endsection

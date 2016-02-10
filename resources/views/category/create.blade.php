@extends('layout')

@section('content')

    {!! Form::open(
                        [
                            'route' => ['category.store'],
                            'enctype' => 'multipart/form-data',
                            'class' => 'col-xs-6',
                            'method' => 'POST',
                            'id' => '_category_definition_form'
                        ]
                    )
    !!}

        <div class="form-group">
            {!! Form::label ('category', 'category:') !!}
            {!! Form::text  (
                                'category',
                                '',
                                [
                                    'class'=>'form-control',
                                    'required'=>'required'
                                ]
                            )
            !!}
        </div>
        <div class="form-group">
            {!! Form::label ('description', 'description:') !!}
            {!! Form::text  (
                                'description',
                                '',
                                [
                                    'class'=>'form-control'
                                ]
                            )
            !!}
        </div>
        <div class="form-group">
            {!! Form::label ('mime', 'mime:') !!}
            {!! Form::text  (
                                'mime',
                                '',
                                [
                                    'class'=>'form-control'
                                ]
                            )
            !!}
        </div>
        <div class="form-group">
            {!! Form::label ('max_size_MB', 'max size in MB:') !!}
            {!! Form::text  (
                                'max_size_MB',
                                '',
                                [
                                    'class'=>'form-control'
                                ]
                            )
            !!}
        </div>
        <div class="form-group">
            {!! Form::submit   (
                                    'Submit',
                                    [
                                        'id' => '_submit',
                                        'class' => 'btn btn-success btn-lg col-s-4',
                                    ]
                                )
            !!}
            {!! Form::button    (
                                    'Cancel',
                                    [
                                        'id' => '_cancel',
                                        'class' => 'btn btn-info btn-lg col-s-4',
                                    ]
                                )
            !!}
        </div>

    {!! Form::close() !!}

@endsection

@section('scripts.footer')

    <script type="text/javascript">

        document.getElementById("_submit").addEventListener("click", function(event){
            set_method('POST');
        });
        document.getElementById("_cancel").addEventListener("click", function(event){
            set_method('GET');
            window.history.back();
        });
        function set_method(method)
        {
            document.getElementsByName('_method')[0].value = method;
        }

    </script>

@endsection


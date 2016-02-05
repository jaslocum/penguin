@extends('layout')

@section('content')

    {!! Form::model(
                        $category_definition,
                        [
                            'route' => ['category_definitions.update', $category_definition['id']],
                            'enctype' => 'multipart/form-data',
                            'class' => 'col-md-5',
                            'method' => 'put',
                            'id' => '_category_definition_form'
                        ]
                    )
    !!}

        <div class="form-group">
            {!! Form::label ('category', 'category:') !!}
            {!! Form::text  (
                                'category',
                                $category_definition['category'],
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
                                $category_definition['description'],
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
                                $category_definition['mime'],
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
                                $category_definition['max_size_MB'],
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
                                        'class' => 'btn btn-success btn-lg col-md-5',
                                    ]
                                )
            !!}
            {!! Form::button    (
                                    'Cancel',
                                    [
                                        'id' => '_cancel',
                                        'class' => 'btn btn-info btn-lg col-md-5',
                                    ]
                                )
            !!}
            {!! Form::button    (
                                    'Delete',
                                    [
                                        'id' => '_delete',
                                        'class' => 'btn btn-danger btn-lg col-md-5',
                                    ]
                                )
            !!}
        </div>

    {!! Form::close() !!}

@endsection

@section('scripts.footer')

    <script type="text/javascript">

        document.getElementById("_submit").addEventListener("click", function(event){
            set_method('PUT');
        });
        document.getElementById("_cancel").addEventListener("click", function(event){
            set_method('GET');
            window.history.back();
        });
        document.getElementById("_delete").addEventListener("click", function(event){
            if (confirm("Delete?")) {
                set_method('DELETE');
                document.getElementById("_category_definition_form").submit();
            }
        });
        function set_method(method)
        {
            document.getElementsByName('_method')[0].value = method;
        }

    </script>

@endsection


@extends('layout')

@section('content')

{{ csrf_field() }}

    {!! Form::model(
                        $category_definition,
                        [
                            'route' => ['category_definitions.update', $category_definition['id']],
                            'enctype' => 'multipart/form-data',
                            'class' => 'col-md-4',
                            'method' => 'PUT'
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
                                    'required'=>'required',

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
                                        'class' => 'btn btn-success btn-lg col-md-6',
                                        'onClick' => "set_method('PUT')"
                                    ]
                                )
            !!}
            {!! Form::submit    (
                                    'Cancel',
                                    [
                                        'class' => 'btn btn-info btn-lg col-md-6',
                                        'onClick' => "window.location = 'category_definitions/'"
                                    ]
                                )
            !!}
            {!! Form::submit    (
                                    'Delete',
                                    [
                                        'class' => 'btn btn-danger btn-lg col-md-6',
                                        'onClick' => "set_method('DELETE')"
                                    ]
                                )
            !!}
        </div>
    {!! Form::close() !!}

@endsection

@section('scripts.footer')

    <script type="text/javascript">
        function set_method(method)
        {
            document.getElementsByName('_method')[0].value = method;
        }
    </script>

@endsection


@extends('layout')

@section('content')

    {!! Form::model(
                        $category,
                        [
                            'route' => ['category.update',$category->id],
                            'enctype' => 'multipart/form-data',
                            'class' => 'col-xs-6',
                            'method' => 'PUT',
                            'id' => '_category_form'
                        ]
                    )
    !!}

        <div class="form-group">
            {!! Form::label ('category', 'category:') !!}
            {!! Form::text  (
                                'category',
                                $category['category'],
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
                                $category['description'],
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
                                $category['mime'],
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
                                $category['max_size_MB'],
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
            {!! Form::button    (
                                    'Delete',
                                    [
                                        'id' => '_delete',
                                        'class' => 'btn btn-danger btn-lg col-s-4',
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
                document.getElementById("_category_form").submit();
            }
        });
        function set_method(method)
        {
            document.getElementsByName('_method')[0].value = method;
        }

    </script>

@endsection


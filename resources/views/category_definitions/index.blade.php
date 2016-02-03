@inject('category_definitions','App\Http\Utilities\Category_definitions')

@extends('layout')

@section('content')


    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th>category</th>
                <th>description</th>
                <th>mime</th>
                <th>max_size_MB</th>
            </tr>
            </thead>
            <tbody>
                @foreach($category_definitions::all() as $category_definition)
                    <tr>
                        <td><a href="/category_definitions/{{$category_definition['id']}}/edit">
                                {{$category_definition['category']}}
                            </a></td>
                        <td>{{$category_definition['description']}}</td>
                        <td>{{$category_definition['mime']}}</td>
                        <td>{{$category_definition['max_size_MB']}}MB</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <form action="/category_definitions/create" method="get">
            <button type="submit" class="btn btn-primary btn-lg">Add new category definition&raquo;</button>
        </form>
    </div>

@endsection



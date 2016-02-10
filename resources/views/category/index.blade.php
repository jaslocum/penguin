@inject('categorys','App\Http\Utilities\Categories')

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
                @foreach($categories::all() as $category)
                    <tr>
                        <td>
                            <a href="/category/{{$category['id']}}/edit">
                                {{$category['category']}}
                            </a>
                        </td>
                        <td>{{$category['description']}}</td>
                        <td>{{$category['mime']}}</td>
                        <td>{{$category['max_size_MB']}}MB</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <form action="/category/create" method="get">
            <button type="submit" class="btn btn-primary btn-lg">Add Category &raquo;</button>
        </form>
    </div>

@endsection



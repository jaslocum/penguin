@inject('category_definitions','App\Http\Utilities\Category_definitions')

{{ csrf_field() }}

<div class="form-group">
    <label for="category">category:</label>
    <select name="category" id="category" class="form-control">
        @foreach($category_definitions::all() as $category_definition)
            <option value="{{$category_definition['category']}}">{{$category_definition['description']}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label>categoryId:</label>
    <input type="text" name="category_rec_id" id="category_rec_id" class="form-control" value="" required>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary btn-lg">Add or Find Category &raquo;</button>
</div>

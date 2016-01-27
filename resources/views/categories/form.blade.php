@inject('categories','App\Http\Utilities\Category')

{{ csrf_field() }}

<div class="form-group">
    <label for="category">category:</label>
    <select name="category" id="category" class="form-control">
        @foreach($categories::all() as $category => $code)
            <option value="{{$code}}">{{$category}}</option>
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

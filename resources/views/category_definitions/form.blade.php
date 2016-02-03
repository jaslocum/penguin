@inject('category_definitions','App\Http\Utilities\Category_definitions')

{{ csrf_field() }}

<div class="form-group">
    <label>category:</label>
    <input type="text" name="category" id="category" class="form-control" value="" required>
</div>
<div class="form-group">
    <label>description:</label>
    <input type="text" name="description" id="description" class="form-control" value="" required>
</div>
<div class="form-group">
    <label>mime:</label>
    <input type="text" name="mime" id="mime" class="form-control" value="" required>
</div>
<div class="form-group">
    <label>max size in MB:</label>
    <input type="text" name="max_size_MB" id="max_size_MB" class="form-control" value="" required>
</div>
<div class="form-group">
    <button method = "post" type="submit" bs-action="/{category_definitions_id}" class="btn btn-default btn-lg">Add or Commit Category Definition &raquo;</button>
</div>
<div class="form-group">
    <button method="delete" type="submit" bs-action="/{category_definitions_id}/delete" class="btn btn-danger btn-lg">Delete Category Definition &raquo;</button>
</div>


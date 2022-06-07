<form action="{{ url('/canteen/update/menu-category/'. $menuCategory->id) }}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Edit Menu Category
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <input type="text" name="categoryName" value="{{ $menuCategory->category_name }}" class="form-control" required>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-info pull-right">Update</button>
    </div>
</form>

<script>
    
</script>

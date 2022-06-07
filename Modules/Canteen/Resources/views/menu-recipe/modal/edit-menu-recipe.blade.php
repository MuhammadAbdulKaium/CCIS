<form action="{{ url('/canteen/update/menu-recipe/'. $recipe->id) }}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Edit Menu Recipe
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <select name="menuId" id="" class="form-control" required>
                    <option value="">--Menu--</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}" {{ ($recipe->menu->id == $menu->id)?'selected':'' }}>{{ $menu->menu_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <input type="text" name="recipeName" class="form-control" value="{{ $recipe->recipe_name }}" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-info pull-right">Update</button>
    </div>
</form>

<script>
    
</script>

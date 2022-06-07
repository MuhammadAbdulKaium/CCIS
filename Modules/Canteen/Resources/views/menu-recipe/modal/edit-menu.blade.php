<form action="{{ url('/canteen/update/menu/'. $menu->id) }}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Edit Menu
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-4">
                <select name="categoryId" id="" class="form-control" required>
                    <option value="">--Category--</option>
                    @foreach ($menuCategories as $menuCategory)
                        <option value="{{ $menuCategory->id }}" {{ ($menu->category_id == $menuCategory->id)?'selected':'' }}>{{ $menuCategory->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-5">
                <input type="text" name="menuName" value="{{ $menu->menu_name }}" class="form-control">
            </div>
            <div class="col-sm-3">
                <select name="uomId" id="" class="form-control" required>
                    <option value="">--UoM--</option>
                    @foreach ($uoms as $uom)
                        <option value="{{ $uom->id }}" {{ ($menu->uom_id == $uom->id)?'selected':'' }}>{{ $uom->formal_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 15px">
            <div class="col-sm-4">
                <input type="number" name="costing" value="{{ $menu->cost }}" class="form-control" required>
            </div>
            <div class="col-sm-4">
                <input type="text" id="effectiveDate" class="form-control hasDatepicker"
                    name="effectiveDate" maxlength="10" placeholder="Effective Date" aria-required="true"
                    size="10" required>
            </div>
            <div class="col-sm-4">
                <input type="number" name="sellPrice" value="{{ $menu->sell_price }}" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-info pull-right">Update</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#effectiveDate').datepicker();
    });
</script>

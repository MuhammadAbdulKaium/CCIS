<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Add New Unit of Measurement</h4>
</div>
<div class="modal-body">
    <form action="/inventory/store/unit-of-measurement" method="POST">
        @csrf
        
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Symbol:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Symbol" name="symbol_name">
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Formal Name:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Name" name="formal_name">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success pull-right">Add</button>
            </div>
        </div>
    </form>
</div>


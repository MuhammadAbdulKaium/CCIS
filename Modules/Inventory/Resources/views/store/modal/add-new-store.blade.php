<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Add New Store</h4>
</div>
<div class="modal-body">
    <form action="/inventory/store-new-store" method="POST">
        @csrf
        
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Name: <span class="text-danger">*</span></label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="store_name" required>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Address 1: <span class="text-danger">*</span></label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="store_address_1" required>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Address 2: <span class="text-danger">*</span></label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="store_address_2" required>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Phone: <span class="text-danger">*</span></label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="store_phone" required>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Fax: <span class="text-danger">*</span></label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="store_fax" required>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">City: <span class="text-danger">*</span></label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="store_city" required> 
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Category: <span class="text-danger">*</span></label>
            </div>
            <div class="col-sm-8">
                <select name="category_id" id="category_id" class="form-control" required>
                    @foreach($storeCategory as $category)
                        <option value="{{$category->id}}">{{$category->store_category_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success pull-right">Add</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('#category_id').select2();
    });
</script>


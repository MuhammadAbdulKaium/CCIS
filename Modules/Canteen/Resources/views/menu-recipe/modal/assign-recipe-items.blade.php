<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Assign Recipe Items
    </h4>
</div>
<!--modal-header-->
<div class="modal-body">
    <div class="row">
        <div class="col-sm-5">
            <select name="" class="form-control item-id">
                <option value="">--Choose Item--</option>
                @foreach ($stockItems as $stockItem)
                    <option value="{{ $stockItem->id }}">{{ $stockItem->product_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <input type="number" class="form-control item-qty" placeholder="Qty">
        </div>
        <div class="col-sm-2">
            <button class="btn btn-success assign-item">Assign</button>
            <button class="btn btn-success update-item" style="display: none" data-item-id="">Update</button>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-info reset-edit-btn" style="display: none"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Stock Item</th>
                        <th>Qty</th>
                        <th>UoM</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="items-holder">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
       var recipe = {!! json_encode($recipe) !!};
       var stockItems = {!! json_encode($stockItems) !!};
       var uoms = {!! json_encode($uoms) !!}

       function generateTable(itemsFromRecipe) {
            var txt = '';

            if (itemsFromRecipe) {
                itemsFromRecipe.forEach(element => {
                    stockItems.forEach(item => {
                        if (item.id == element.id) {
                            txt += '<tr><td>'+item.product_name+'</td><td>'+element.qty+'</td><td>'+uoms[item.unit].symbol_name+
                                '</td><td><button class="btn btn-xs btn-primary edit-item" style="margin-right: 5px" data-item-id="'+item.id+
                                    '" data-item-qty="'+element.qty+'"><i class="fa fa-pencil"></i></button><button class="btn btn-xs btn-danger remove-item" data-item-id="'+item.id+
                                    '"><i class="fa fa-trash"></i></button></td></tr>';
                        }
                    });
                });
                $('.items-holder').html(txt);
            }else{
                $('.items-holder').html('<tr><td colspan="5" style="text-align: center">No Items assigned!</td></tr>');
            }
       }

       function refreshTable() {
           // Ajax Request Start
           $_token = "{{ csrf_token() }}";
           $.ajax({
               headers: {
                   'X-CSRF-Token': $('meta[name=_token]').attr('content')
               },
               url: "{{ url('/canteen/get/recipe/stock-items') }}",
               type: 'GET',
               cache: false,
               data: {
                   '_token': $_token,
                   'recipeId': recipe.id,
               }, //see the _token
               datatype: 'application/json',
           
               beforeSend: function () {},
           
               success: function (data) {
                    generateTable(data);
               }
           });
           // Ajax Request End
       }

       refreshTable();

       $('.assign-item').click(function () {
           var itemId = $('.item-id').val();
           var itemQty = $('.item-qty').val();
           var stockItem = null;

           if (itemId && itemQty) {
               stockItem = {
                   id: itemId,
                   qty: itemQty
               }

               // Ajax Request Start
               $_token = "{{ csrf_token() }}";
               $.ajax({
                   headers: {
                       'X-CSRF-Token': $('meta[name=_token]').attr('content')
                   },
                   url: "{{ url('/canteen/assign/item/to/recipe') }}",
                   type: 'GET',
                   cache: false,
                   data: {
                       '_token': $_token,
                       'recipeId': recipe.id,
                       'stockItem': stockItem
                   }, //see the _token
                   datatype: 'application/json',
               
                   beforeSend: function () {},
               
                   success: function (data) {
                       if (data == 1) {
                            refreshTable();
                            $('.item-id').val('');
                            $('.item-qty').val('');
                       } else if (data == 2) {
                           swal('Error!', 'This Item is already assigned, can not assign again.', 'error');
                       }
                   }
               });
               // Ajax Request End
           }else{
               swal('Error!', 'Fill up all the fields first.', 'error');
           }
       });

       $(document).on('click', '.edit-item', function () {
           var itemId = $(this).data('item-id');
           var itemQty = $(this).data('item-qty');

           $('.assign-item').css('display', 'none');
           $('.update-item').css('display', 'inline-block');
           $('.update-item').data('item-id', itemId);
           $('.reset-edit-btn').css('display', 'inline-block');

           $('.item-id').val(itemId);
           $('.item-qty').val(itemQty);
       });

       function resetEditFields() {
            $('.assign-item').css('display', 'inline-block');
            $('.update-item').css('display', 'none');
            $('.reset-edit-btn').css('display', 'none');

            $('.item-id').val('');
            $('.item-qty').val('');
       }

       $('.update-item').click(function () {
            var itemId = $(this).data('item-id');
            var newItemId = $('.item-id').val();
            var itemQty = $('.item-qty').val();

            if (newItemId && itemQty) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/canteen/update/recipe/item') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'recipeId': recipe.id,
                        'itemId': itemId,
                        'newItemId': newItemId,
                        'itemQty': itemQty,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        if (data == 1) {
                            refreshTable();
                            resetEditFields();
                       } else if (data == 2) {
                           swal('Error!', 'This Item is already assigned, can not assign again.', 'error');
                       }
                    }
                });
                // Ajax Request End
            }else{
               swal('Error!', 'Fill up all the fields first.', 'error');
            }
       });

       $('.reset-edit-btn').click(function () {
        resetEditFields();
       });

       $(document).on('click', '.remove-item', function () {
            var itemId = $(this).data('item-id');

            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/canteen/remove/recipe/item') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'recipeId': recipe.id,
                    'itemId': itemId,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    if (data == 1) {
                        refreshTable();
                    }
                }
            });
            // Ajax Request End
       });
    });
</script>

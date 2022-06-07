@extends('layouts.master')

@section('styles')
<style>
    li {
        list-style: none;
    }

    ul {
        padding-left: 0;
    }

    .food-btn {
        display: block;
        width: 100%;
        margin: 10px 0;
    }

    .pm-btn {
        cursor: pointer;
        font-size: 19px;
        margin-left: 5px;
        font-weight: 600;
    }

    .food-item-wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .food-items {
        border: 1px solid;
        padding: 2px 8px 15px 8px;
        display: inline-block;
        min-width: 150px;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Canteen |<small>Transactions</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="">Canteen</a></li>
            <li>SOP Setup</li>
            <li class="active">Transactions</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif

        <div class="row">
            <div class="col-sm-3">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-history"></i> Transactions </h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ '/canteen/store/transaction' }}" method="POST">
                            @csrf

                            <input type="hidden" name="purchaseDetails" class="purchase-details-field">
                            <input type="hidden" name="total" class="total-field">
                            <input type="hidden" name="previousDues" class="previous-dues-field">

                            <span class="badge badge-info">Date: {{ Carbon\Carbon::now()->format('d/m/Y - D') }}</span>
                            <span class="badge badge-info">Time: {{ Carbon\Carbon::now()->format('h:i A') }}</span>

                            <div class="row user-info-holder" style="padding: 20px 10px">
                                
                            </div>

                            <div class="row" style="padding: 20px 5px">
                                <div class="col-sm-12">
                                    <label for="">Today's Selection:</label>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">SL</th>
                                                <th scope="col">Item</th>
                                                <th scope="col">Qty</th>
                                                <th scope="col">Rate</th>
                                                <th scope="col">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="selected-items-table">
                                            {{-- <tr>
                                                <th scope="row">1</th>
                                                <td>18' Chicken Pizza</td>
                                                <td>1</td>
                                                <td>850</td>
                                                <td>850</td>
                                            </tr> --}}
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <th colspan="4">
                                                    <div style="float: right"><b>Total:</b></div>
                                                </th>
                                                <td class="total-trans">0</td>
                                            </tr>
                                            <tr>
                                                <th class="text-danger" colspan="4">
                                                    <div style="float: right"><b>Previous Dues:</b></div>
                                                </th>
                                                <td><b class="text-danger previous-due-trans">0</b></td>
                                            </tr>
                                            <tr>
                                                <th class="text-danger" colspan="4">
                                                    <div style="float: right"><b>Total Dues:</b></div>
                                                </th>
                                                <td><b class="text-danger total-due-trans">0</b></td>
                                            </tr>
                                            <tr>
                                                <th colspan="4">
                                                    <div style="float: right"><b>Amount Given:</b></div>
                                                </th>
                                                <td><input type="number" name="amountGiven" class="form-control amount-given" required></td>
                                            </tr>
                                            <tr>
                                                <th colspan="4">
                                                    <div style="float: right"><b>Payment For:</b></div>
                                                </th>
                                                <td><input type="number" name="paymentFor" class="form-control payment-for" required></td>
                                            </tr>
                                            <tr>
                                                <th class="text-danger" colspan="4">
                                                    <div style="float: right"><b>Change To Customer:</b></div>
                                                </th>
                                                <td><b class="text-danger change-to-customer">0</b></td>
                                            </tr>
                                            <tr>
                                                <th class="text-danger" colspan="4">
                                                    <div style="float: right"><b>Dues Carry Forwarded:</b></div>
                                                </th>
                                                <td><b class="text-danger dues-carry-forwarded">0</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div style="float: right">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#canteenCustomerModal" id="modal-launch-btn" data-modal-size="modal-md">Select Customer</button>
                                        <button type="button" class="btn btn-success proceed-transaction-btn">Proceed</button>
                                        <button style="display: none" class="transaction-submit-btn"></button>
                                    </div>
                                </div>
                            </div>

                            {{-- Table seat person management modal --}}
                            <div class="modal fade" id="canteenCustomerModal" tabindex="-1" role="dialog" aria-labelledby="canteenCustomerModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title" id="canteenCustomerModalLabel" style="float: left">Select Customer</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <select name="customerType" class="form-control person-type-field">
                                                    <option value="">--Customer Type--</option>
                                                    <option value="1">Cadet</option>
                                                    <option value="2">HR/FM</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-7">
                                                <select name="customerId" class="form-control person-field">
                                                    <option value="">--Select Customer--</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary customer-select-btn">Select</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Food Table </h3>
                    </div>
                    <div class="box-body">
                        <div class="row" id="easy-filter-wrap">
                            <div class="col-sm-2">
                                <button type="button " data-easyfilter="*" class="btn btn-info food-btn"> All </button>
                                @foreach ($menuCategories as $menuCategory)
                                    <button type="button" data-easyfilter="item-{{ $menuCategory->id }}" class="btn btn-primary food-btn"> {{ $menuCategory->category_name }}</button>
                                @endforeach
                            </div>
                            <div class="col-sm-10">
                                <div class="food-item-wrapper">
                                    @foreach ($menus as $menu)
                                        <div data-easyitem="item-{{ $menu->category_id }}" class="food-items" data-sl="{{ $loop->index }}" style="{{ ($menu->available_qty < 1)?'background: lightgrey':'' }}">
                                            <div style="text-align: right; {{ ($menu->available_qty < 1)?'visibility: hidden':'' }}">
                                                <span class="pm-btn text-success food-plus">+</span>
                                                <span class="pm-btn text-danger food-minus" style="display: none">-</span>
                                            </div>
                                            <div class="text-primary" style="text-align: center">{{ $menu->menu_name }}</div>
                                            <div class="text-primary" style="text-align: center">Price: {{ $menu->sell_price }}</div>
                                            <div class="text-primary" style="text-align: center">Available: <b class="item-available-qty" data-max-qty="{{ $menu->available_qty }}">{{ $menu->available_qty }}</b></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="loader">
                            <div class="es-spinner">
                                <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script src="/js/filter/jquery.easyFilter.js"></script>
<script>
    $(document).ready(function () {
        // $('#categoryTable').DataTable();

        $('#fromDate').datepicker();
        $('#toDate').datepicker();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        // For Filtering Foods
        $('#easy-filter-wrap').easyFilter();

        var foods = {!! json_encode($menus) !!};


        // Add or remove foods
        var selectedItems = [];
        var total = 0;
        var previousDues = 0;
        var amountGiven = '';
        var paymentFor = '';
        var customerType = null;
        var customerId = null;

        function createTrans() {
            total = 0;
            $('.selected-items-table').empty();

            selectedItems.forEach((item, index) => {
                var txt = "<tr><th>" + (index + 1) + "</th><td>" + item.name + "</td><td>" + item.qty +
                    "</td><td>" + item.price + "</td><td>" + (item.qty * item.price) + "</td></tr>";

                $('.selected-items-table').append(txt);

                total += item.price * item.qty;
            });

            $('.total-trans').text(total);
            $('.previous-due-trans').text(previousDues);
            $('.total-due-trans').text(total+previousDues);
            $('.amount-given').val(amountGiven);
            $('.payment-for').val(paymentFor);
            $('.change-to-customer').text(amountGiven - paymentFor);
            $('.dues-carry-forwarded').text((total+previousDues) - paymentFor);
        }

        function availableQtyCheck(parent, qty, maxQty) {
            parent.find('.item-available-qty').text(qty);
            parent.find('.food-plus').css('display', 'inline-block');
            parent.find('.food-minus').css('display', 'inline-block');

            if (qty >= maxQty) {
                parent.find('.food-minus').css('display', 'none');
            }else if(qty > 0){
                parent.css('background', 'white');
            }else{
                parent.css('background', 'lightgrey');
                parent.find('.food-plus').css('display', 'none');
            }
        }

        $('.food-plus').click(function () {
            var parent = $(this).parent().parent();
            var foodSl = parent.attr('data-sl');
            var availableQty = parseInt(parent.find('.item-available-qty').text());
            var maxQty = parseInt(parent.find('.item-available-qty').data('max-qty'));
            var selectedFood = foods[foodSl];

            var flag = false;
            var selectedItemsIndex = -1;

            availableQtyCheck(parent, availableQty-1, maxQty);

            selectedItems.forEach((item, index) => {
                if (item.id == selectedFood.id) {
                    selectedItemsIndex = index;
                    flag = true;
                }
            });

            if (flag) {
                selectedItems[selectedItemsIndex].qty += 1;
            } else {
                selectedItems.push({
                    id: selectedFood.id,
                    categoryId: selectedFood.category_id,
                    name: selectedFood.menu_name,
                    qty: 1,
                    price: selectedFood.sell_price
                });
            }

            createTrans();
        });

        $('.food-minus').click(function () {
            var parent = $(this).parent().parent();
            var foodSl = parent.attr('data-sl');
            var availableQty = parseInt(parent.find('.item-available-qty').text());
            var maxQty = parseInt(parent.find('.item-available-qty').data('max-qty'));
            var selectedFood = foods[foodSl];

            var flag = false;
            var selectedItemsIndex = -1;

            availableQtyCheck(parent, availableQty+1, maxQty);

            selectedItems.forEach((item, index) => {
                if (item.id == selectedFood.id) {
                    selectedItemsIndex = index;
                    flag = true;
                }
            });

            if (flag) {
                if (selectedItems[selectedItemsIndex].qty < 2) {
                    selectedItems.splice(selectedItemsIndex, 1);
                } else {
                    selectedItems[selectedItemsIndex].qty -= 1;
                }
            }

            createTrans();
        });

        $(document).on('input', '.amount-given', function () {
           amountGiven = $(this).val();
           createTrans();
        });

        $(document).on('input', '.payment-for', function () {
            paymentFor = $(this).val();
           createTrans();
        });

        $('.person-type-field').click(function () {
            var type=$(this).val();
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/mess/get/all/persons/from/personType') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'type': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Select Customer--</option>';

                    data.forEach(element => {

                        if(type===1){
                            console.log(element)
                            if(element.status===1){
                                var id=element.std_id;
                                txt += '<option value="'+id+'">'+element.first_name+' '+element.last_name+'</option>';
                            }

                        }else{
                            var id=element.id;
                            txt += '<option value="'+id+'">'+element.first_name+' '+element.last_name+'</option>';

                        }
                    });

                    $('.person-field').html(txt);
                }
            });
            // Ajax Request End
        });

        $('.customer-select-btn').click(function () {
            customerType = $('.person-type-field').val();
            customerId = $('.person-field').val();

            if (customerType && customerId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/canteen/transaction/get/customer-info') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'type': customerType,
                        'id': customerId,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        console.log(data)
                        $('.user-info-holder').html(data.html);
                        if (data.lastTrans) {
                            previousDues = data.lastTrans.carry_forwarded_due;
                        }else{
                            previousDues = 0;
                        }
                        $('#canteenCustomerModal').modal('hide');
                        createTrans();
                    }
                });
                // Ajax Request End
            } else{
                swal('Error!', 'Fill up all the fields first', 'error');
            }
        });

        $('.proceed-transaction-btn').click(function () {
           if (total < 1) {
                swal('Error!', 'Choose a menu first.', 'error');
           } else if (!customerId) {
                swal('Error!', 'Select your customer first.', 'error');
           } else {
                $('.purchase-details-field').val(JSON.stringify(selectedItems));
                $('.total-field').val(total);
                $('.previous-dues-field').val(previousDues);

                $('.transaction-submit-btn').click();
           }
        });
    });
</script>
@stop
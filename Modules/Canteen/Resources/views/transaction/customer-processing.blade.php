@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Canteen |<small>Customer Processing</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="">Canteen</a></li>
            <li>SOP Setup</li>
            <li class="active">Customer Processing</li>
        </ul>
    </section>
    <section class="content">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Search Transactions </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <input type="text" id="fromDate" class="form-control hasDatepicker"
                                name="fromDate" maxlength="10" placeholder="From Date" aria-required="true"
                                size="10" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <input type="text" id="toDate" class="form-control hasDatepicker" name="toDate"
                                maxlength="10" placeholder="To Date" aria-required="true" size="10" required>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <select name="customerType" class="form-control person-type-field">
                            <option value="">--Customer Type--</option>
                            <option value="1">Cadet</option>
                            <option value="2">HR/FM</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="customerId" class="form-control person-field">
                            <option value="">--All Customer--</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="categoryId" id="" class="form-control category-field" required>
                            <option value="">--All Category--</option>
                            @foreach ($menuCategories as $menuCategory)
                                <option value="{{ $menuCategory->id }}">{{ $menuCategory->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="menuId" id="" class="form-control menu-field" required>
                            <option value="">--All Item--</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <button class="btn btn-success search-btn" style="float: right"><i class="fa fa-search"></i></button>
                        {{-- <button class="btn btn-primary print-btn" style="float: right; margin-right:7px"><i class="fa fa-print"></i></button> --}}
                    </div>
                </div>
                <div class="row" style="margin-top: 20px">
                    <div class="col-sm-12 tables-holder">
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#fromDate').datepicker();
        $('#toDate').datepicker();


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
                    var txt = '<option value="">--All Customer--</option>';

                    data.forEach(element => {
                        if(type===1){
                            console.log(element)

                                var id=element.std_id;
                                txt += '<option value="'+id+'">'+element.first_name+' '+element.last_name+'</option>';


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

        $('.category-field').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/canteen/get/menus/from/category') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'categoryId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--All Item--</option>';

                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.menu_name+'</option>';
                    });

                    $('.menu-field').html(txt);
                }
            });
            // Ajax Request End 
        });

        $('.search-btn').click(function () {
            var datas = {
                '_token': "{{ csrf_token() }}",
                fromDate: $('#fromDate').val(),
                toDate: $('#toDate').val(),
                type: $('.person-type-field').val(),
                personId: $('.person-field').val(),
                categoryId: $('.category-field').val(),
                menuId: $('.menu-field').val(),
            }

            // Ajax Request Start
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/canteen/search/transactions') }}",
                type: 'GET',
                cache: false,
                data: datas,
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    $('.tables-holder').html(data);
                }
            });
            // Ajax Request End
        });
    });


    
</script>
@stop
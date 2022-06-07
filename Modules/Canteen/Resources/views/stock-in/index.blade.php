@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Canteen |<small>Make & Stock-In</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="">Canteen</a></li>
            <li>SOP Setup</li>
            <li class="active">Make & Stock-In</li>
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
            @if(in_array('canteen/stock-in', $pageAccessData))
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> {{ ($stockIn)?'Edit':'' }} Stock In </h3>
                @if ($stockIn)    
                    <div class="box-tools">
                        <a class="btn btn-success btn-sm" href="{{url('canteen/stock-in')}}">Create</a>
                    </div>
                @endif
            </div>
            <div class="box-body">
                <form action="{{ ($stockIn)?url('/canteen/update/stock-in/'.$stockIn->id):url('/canteen/stock-in') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-sm-2">
                            <input type="text" id="date" class="form-control hasDatepicker" name="date" maxlength="10" placeholder="Select Date" aria-required="true" size="10" value="{{ ($stockIn)?Carbon\Carbon::parse($stockIn->date)->format('m/d/Y'):'' }}" required>
                        </div>
                        <div class="col-sm-2">
                            <select name="categoryId" id="" class="form-control category-field" required>
                                <option value="">--Select Category--</option>
                                @foreach ($menuCategories as $menuCategory)
                                    <option value="{{ $menuCategory->id }}" {{ ($stockIn)?($stockIn->category_id == $menuCategory->id)?'selected':'':'' }}>{{ $menuCategory->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="menuId" id="" class="form-control menu-field" required>
                                <option value="">--Select Item--</option>
                                @if($stockIn)
                                    @foreach($stockIn->category->menus as $menu)
                                        <option value="{{ $menu->id }}" {{ ($stockIn->menu_id == $menu->id)?'selected':'' }}>{{ $menu->menu_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="number" name="qty" placeholder="Unit Made" class="form-control" value="{{ ($stockIn)?$stockIn->qty:'' }}" required>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" placeholder="UoM" class="form-control uom-holder" disabled>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-sm btn-success">{{ ($stockIn)?'Update':'Save' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            @endif  
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Stock In List</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered" id="stockInTable">
                    <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Date</th>
                            <th scope="col">Category</th>
                            <th scope="col">Item</th>
                            <th scope="col">Qty. Made</th>
                            <th scope="col">To-Date-Cost</th>
                            <th scope="col">Amount Value</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockIns as $stockIn)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $stockIn->date }}</td>
                                <td>{{ $stockIn->category->category_name }}</td>
                                <td>{{ $stockIn->menu->menu_name }}</td>
                                <td>{{ $stockIn->qty }} {{ $stockIn->menu->uom->symbol_name }}</td>
                                <td>{{ $stockIn->cost }}</td>
                                <td>{{ $stockIn->qty * $stockIn->cost }}</td>
                                <td>
                                    @if(in_array('canteen/stock-in.edit', $pageAccessData))
                                    <a href="{{ url('/canteen/stock-in/'.$stockIn->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>

                                    @endif
                                    @if(in_array('canteen/stock-in.delete', $pageAccessData))
                                        <a href="{{ url('/canteen/delete/stock-in/'.$stockIn->id) }}" class="btn btn-danger btn-xs"
                                            onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                            data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#stockInTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('#date').datepicker();


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
                   var txt = '<option value="">--Select Item--</option>';

                   data.forEach(element => {
                       txt += '<option value="'+element.id+'">'+element.menu_name+'</option>';
                   });

                   $('.menu-field').html(txt);
               }
           });
           // Ajax Request End 
        });

        $('.menu-field').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/canteen/get/uom/from/menus') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'menuId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    $('.uom-holder').val(data.symbol_name);
                }
            });
            // Ajax Request End
        });
    });
</script>
@stop
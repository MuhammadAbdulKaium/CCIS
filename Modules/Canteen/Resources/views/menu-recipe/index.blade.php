@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Canteen |<small>Menu & Recipe</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li>Canteen</li>
            <li>SOP Setup</li>
            <li class="active">Menu & Recipe</li>
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
            @if(in_array('26350', $pageAccessData))
                <div class="col-sm-3">
                @if(in_array('canteen/store/menu-category', $pageAccessData))
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Category </h3>
                    </div>

                    <div class="box-body">
                        <form method="POST" action="{{ url('/canteen/store/menu-category') }}">
                            @csrf

                            <input type="text" name="categoryName" placeholder="Category Name" class="form-control"
                                style="margin: 20px 0 40px 0" required>

                            <button class="btn btn-success">Create</button>
                            <button type="button" class="btn btn-secondary reset-fields-btn">Reset</button>
                        </form>
                    </div>
                </div>
                @endif
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Category List </h3>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="categoryTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">SL</th>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menuCategories as $menuCategory)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $menuCategory->category_name }}</td>
                                        <td>
                                            @if(in_array('canteen/menu-category.edit', $pageAccessData))
                                                <a class="btn btn-xs btn-primary" href="{{ url('/canteen/edit/menu-category/'.$menuCategory->id) }}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(in_array('canteen/menu-category.delete', $pageAccessData))
                                                <a href="{{ url('/canteen/delete/menu-category/'.$menuCategory->id) }}" class="btn btn-danger btn-xs"
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

            </div>
            @endif
            @if(in_array('26550', $pageAccessData))
                <div class="col-sm-5">
                    @if(in_array('canteen/store/menu', $pageAccessData))
                    <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Menu Name
                        </h3>
                    </div>

                    <div class="box-body">
                        <form method="POST" action="{{ url('/canteen/store/menu') }}">
                            @csrf

                            <div class="row" style="margin: 20px 0 15px 0">
                                <div class="col-sm-4">
                                    <select name="categoryId" id="" class="form-control" required>
                                        <option value="">--Category--</option>
                                        @foreach ($menuCategories as $menuCategory)
                                            <option value="{{ $menuCategory->id }}">{{ $menuCategory->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" name="menuName" placeholder="Menu Name" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <select name="uomId" id="" class="form-control" required>
                                        <option value="">--UoM--</option>
                                        @foreach ($uoms as $uom)
                                            <option value="{{ $uom->id }}">{{ $uom->formal_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin: 15px 0 40px 0">
                                <div class="col-sm-4">
                                    <input type="number" name="costing" placeholder="Costing" class="form-control" required>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" name="sellPrice" placeholder="Sell Price" class="form-control" required>
                                </div>
                            </div>

                            <button class="btn btn-success">Create</button>
                            <button type="button" class="btn btn-secondary reset-fields-btn">Reset</button>
                        </form>
                    </div>
                </div>
                    @endif
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Menu List </h3>
                    </div>

                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="menuTable">
                            <thead>
                                <tr>
                                    <th scope="col">SL</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Menu</th>
                                    <th scope="col">UoM</th>
                                    <th scope="col">Costing</th>
                                    <th scope="col">Sell Price</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menus as $menu)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $menu->category->category_name }}</td>
                                    <td>{{ $menu->menu_name }}</td>
                                    <td>{{ $menu->uom->formal_name }}</td>
                                    <td>{{ $menu->cost }}</td>
                                    <td>{{ $menu->sell_price }}</td>
                                    <td>
                                        @if(in_array('canteen/menu.history', $pageAccessData))
                                            <a class="btn btn-xs btn-info" href="{{ url('/canteen/menu/history/'.$menu->id) }}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">H</a>
                                        @endif
                                        @if(in_array('canteen/menu.edit', $pageAccessData))
                                            <a class="btn btn-xs btn-primary" href="{{ url('/canteen/edit/menu/'.$menu->id) }}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(in_array('canteen/menu.delete', $pageAccessData))
                                                <a href="{{ url('/canteen/delete/menu/'.$menu->id) }}" class="btn btn-danger btn-xs"
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
            </div>
            @endif
                @if(in_array('26800', $pageAccessData))
                <div class="col-sm-4">
                @if(in_array('canteen/store/menu-recipe', $pageAccessData))
                    <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Menu Recipe
                        </h3>
                    </div>

                    <div class="box-body">
                        <form action="{{ url('/canteen/store/menu-recipe') }}" method="POST">
                            @csrf

                            <div class="row" style="margin: 20px 0 40px 0">
                                <div class="col-sm-6">
                                    <select name="menuId" id="" class="form-control" required>
                                        <option value="">--Menu--</option>
                                        @foreach ($menus as $menu)
                                            <option value="{{ $menu->id }}">{{ $menu->menu_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" name="recipeName" class="form-control" placeholder="Recipe Name" required>
                                </div>
                            </div>

                            <button class="btn btn-success">Create</button>
                            <button type="button" class="btn btn-secondary reset-fields-btn">Reset</button>
                        </form>
                    </div>
                </div>
                @endif
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Recipe List </h3>
                    </div>

                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="recipeTable">
                            <thead>
                                <tr>
                                    <th scope="col">SL</th>
                                    <th scope="col">Menu</th>
                                    <th scope="col">Recipe</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recipes as $recipe)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $recipe->menu->menu_name }}</td>
                                    <td>{{ $recipe->recipe_name }}</td>
                                    <td>
                                        @if(in_array('canteen/menu-recipe.assign', $pageAccessData))
                                        <a class="btn btn-xs btn-success" href="{{ url('/canteen/assign/recipe/items/'.$recipe->id) }}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">A</a>
                                        @endif
                                        @if(in_array('canteen/menu-recipe.edit', $pageAccessData))
                                        <a class="btn btn-xs btn-primary" href="{{ url('/canteen/edit/menu-recipe/'.$recipe->id) }}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if(in_array('canteen/menu-recipe.delete', $pageAccessData))
                                        <a href="{{ url('/canteen/delete/recipe/items/'.$recipe->id) }}" class="btn btn-danger btn-xs"
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
            </div>
                    @endif
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
<script>
    $(document).ready(function () {
        // $('#categoryTable').DataTable();
        $('#menuTable').DataTable();
        $('#recipeTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('.reset-fields-btn').click(function () {
            var parent = $(this).parent().parent();
            var allFields = parent.find(':input');

            allFields.each((index, value) => {
                $(value).val('');
            }); 
        });
    });
</script>
@stop
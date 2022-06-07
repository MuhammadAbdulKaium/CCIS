@extends('layouts.master')

@section('styles')
    <style>
        .select2-selection{
            min-height: 33px !important;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Appoints |<small>House</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">House</a></li>
            <li>SOP Setup</li>
            <li class="active">House Appoints</li>
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
            {{-- @if( in_array('17700',$pageAccessData) )
            @endif --}}
            <div class="col-sm-6">
                @if(in_array('house/appoints.create',$pageAccessData))
                <div class="box box-solid">
                    <form method="POST" action="{{ url('/house/store/house-appoint') }}">
                        @csrf

                        <div class="box-header with-border">
                            <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Create House Appoints </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Category</label>
                                    <select name="category" class="form-control" required>
                                        <option value="hr">HR</option>
                                        <option value="fm">FM</option>
                                        <option value="cadet">Cadet</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="">Appointment Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Symbol</label>
                                    <input type="text" name="symbol" class="form-control" placeholder="Fontawesome Class">
                                </div>
                                <div class="col-md-2">
                                    <label for="">Color</label>
                                    <input type="color" name="color" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success" style="float: right">Create</button>
                        </div>
                    </form>
                </div>
                @endif
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> House Appoints List </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="appoints_table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Category</th>
                                    <th>Appointment Name</th>
                                    <th>Symbol</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($houseAppoints as $appoint)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ $appoint->category }}</td>
                                        <td style="color: {{ $appoint->color }}">{{ $appoint->name }}</td>
                                        <td style="color: {{ $appoint->color }}">
                                            @if ($appoint->symbol)
                                                <i class="{{ $appoint->symbol }}"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('/house/house-appoints/'.$appoint->id) }}" class="btn btn-xs btn-success"><i class="fa fa-user"></i></a>
                                            @if(in_array('house/appoints.edit',$pageAccessData))
                                            <a class="btn btn-primary btn-xs"
                                                href="{{ url('/house/edit/house-appoint/'.$appoint->id) }}" data-target="#globalModal" 
                                                data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(in_array('house/appoints.delete',$pageAccessData))
                                            <a href="{{ url('/house/delete/house-appoint/'.$appoint->id) }}" class="btn btn-danger btn-xs"
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
            @if ($selectedAppoint)
                @php
                    $userType = 'User';
                    if($selectedAppoint->category == 'hr'){
                        $userType = 'Hr';
                    }elseif($selectedAppoint->category == 'fm'){
                        $userType = 'Fm';
                    }elseif($selectedAppoint->category == 'cadet'){
                        $userType = 'Cadet';
                    }
                @endphp
                <div class="col-md-6">
                    @if(in_array('house/assign/user/to/appoint',$pageAccessData))
                    <div class="box box-solid">
                        <form method="POST" action="{{ url('/house/assign/user/to/appoint') }}">
                            @csrf

                            <input type="hidden" name="appointId" value="{{ $selectedAppoint->id }}">

                            <div class="box-header with-border">
                                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Assign {{ $userType }}s to {{ $selectedAppoint->name }} </h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <label for="">{{ $userType }}s</label>
                                        <select name="userIds[]" id="select-users" class="form-control" multiple required>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->user_id }}">
                                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->singleUser->username }})
                                                    @if ($selectedAppoint->category == 'cadet')
                                                         - {{ $user->roomStudent->house->name }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($selectedAppoint->category != 'cadet')
                                        <div class="col-md-5">
                                            <label for="">House</label>
                                            <select name="houseId" id="" class="form-control" required>
                                                <option value="">--Select House--</option>
                                                @foreach ($houses as $house)
                                                    <option value="{{ $house->id }}">{{ $house->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="box-footer">
                                <button class="btn btn-success" style="float: right">Assign</button>
                            </div>
                        </form>
                    </div>
                    @endif
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> {{ $selectedAppoint->name }} {{ $userType }} List </h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered" id="appoints_table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>{{ $userType }} Name</th>
                                        <th>{{ $userType }} ID</th>
                                        <th>House Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($houseAppointUsers as $appointUser)
                                        <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ $appointUser->user->name }}</td>
                                            <td>{{ $appointUser->user->username }}</td>
                                            <td>
                                                @if ($selectedAppoint->category != 'cadet')
                                                    {{ $appointUser->house->name }}
                                                @else
                                                    @if ($appointUser->stuProfile->roomStudent->house)
                                                        {{ $appointUser->stuProfile->roomStudent->house->name }}
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if(in_array('house/appoints.user.delete',$pageAccessData))
                                                <a href="{{ '/house/remove/user/from/appoint/'.$appointUser->id }}" 
                                                    class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" 
                                                    data-placement="top" data-content="delete"> <i class="fa fa-trash-o"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="50" class="text-center">No users are assigned yet!</td>
                                        </tr>
                                    @endforelse
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
        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('#appoints_table').DataTable();

        $('#select-users').select2({
            placeholder: "Select Users"
        });
    });
</script>
@stop
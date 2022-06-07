@extends('layouts.master')

@section('styles')
    <style>
        .bed-container{
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .bed-container > div{
            padding: 10px;
            margin: 10px;
            border: 1px solid;
            text-align: center;
            width: 120px;
        }

        .bed-std-info{
            margin: 10px 0;
        }

        .assign-form{
            display: none;
        }

        .select2-container--default{
            width: 100% !important;
        }
        .select2-selection--single{
            height: 33px !important;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>House</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">House</a></li>
            <li>SOP Setup</li>
            <li class="active">Mange House</li>
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
            @if( in_array('17700',$pageAccessData) )
            <div class="col-sm-7">
                @if(in_array('house/create-house',$pageAccessData))
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Create House </h3>
                        </div>
                        <div class="box-body">
                            <form action="{{url('/house/create-house')}}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="name" placeholder="House Name" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="bengali_name" placeholder="House Bengali Name">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="alias" placeholder="House Alias" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <select name="employeeId" id="house-master-field" class="form-control" required>
                                            <option value="">--House Master--</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{$employee->id}}">{{$employee->first_name}} {{$employee->last_name}} ({{$employee->singleUser->username}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" min="1" max="99" name="floors" placeholder="Floors" required>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="symbol_name" placeholder="House Symbol">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" name="symbol" placeholder="Symbol icon">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="text" class="form-control" name="color_name" placeholder="House Color">
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="color" class="form-control" name="color">
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="motto" placeholder="House Motto">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="bengali_motto" placeholder="House Bengali Motto">
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> House List </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="houseTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>House Name</th>
                                    <th>House Bengali Name</th>
                                    <th>House Alias</th>
                                    <th>No Of Floors</th>
                                    <th>House Master</th>
                                    <th>Motto</th>
                                    <th>Bengali Motto</th>
                                    <th>Symbol</th>
                                    <th>Symbol Icon</th>
                                    <th>Color</th>
                                    <th>House Prefect</th>
                                    <th>No Of Rooms</th>
                                    <th>Blank Beds</th>
                                    @if( in_array('house/edit',$pageAccessData) || in_array('house/delete',$pageAccessData))
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($houses as $house)
                                    @php
                                        $totalBedsInHouse = 0;
                                        foreach ($house->rooms as $room) {
                                            $totalBedsInHouse += $room->no_of_beds;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td style="color: {{$house->color}}">{{$house->name}}</td>
                                        <td style="color: {{$house->color}}">{{$house->bengali_name}}</td>
                                        <td>{{$house->alias}}</td>
                                        <td>{{$house->no_of_floors}}</td>
                                        <td>{{$house->houseMaster->first_name}} {{$house->houseMaster->last_name}} ({{ $house->houseMaster->singleUser->username }})</td>
                                        <td>{{$house->motto}}</td>
                                        <td>{{$house->bengali_motto}}</td>
                                        <td>{{$house->symbol_name}}</td>
                                        <td><i class="{{$house->symbol}}"></i></td>
                                        <td>{{$house->color_name}}</td>
                                        <td>
                                            @if ($house->housePrefect)
                                                {{$house->housePrefect->first_name}} {{$house->housePrefect->last_name}} ({{ $house->housePrefect->singleUser->username }})
                                            @endif    
                                        </td>
                                        <td>{{sizeof($house->rooms)}}</td>
                                        <td>{{ $totalBedsInHouse - sizeof($house->roomStudents) }}</td>
                                        @if( in_array('house/edit',$pageAccessData) || in_array('house/delete',$pageAccessData))
                                            <td>
                                                @if(in_array('house/edit',$pageAccessData))
                                                    <a class="btn btn-primary btn-xs"
                                                        href="{{url('/house/edit-house/'.$house->id)}}"
                                                        data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i
                                                            class="fa fa-edit"></i></a>
                                                @endif
                                                @if(in_array('house/delete',$pageAccessData))
                                                    <a href="{{url('house/delete-house/'.$house->id)}}"
                                                    class="btn btn-danger btn-xs"
                                                    onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                                    data-content="delete"><i class="fa fa-trash-o"></i></a>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="50">
                                            <div class="text-danger" style="text-align: center">No House Found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            @if( in_array('17900',$pageAccessData) )
            <div class="col-sm-5">
                @if( in_array('house/create-room',$pageAccessData))
                    <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Create Room </h3>
                    </div>
                    <div class="box-body">
                        <form action="{{url('/house/create-room')}}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-sm-8">
                                    <select name="houseId" id="" class="form-control select-house" required>
                                        <option value="">--House--</option>
                                        @foreach ($houses as $house)
                                            <option value="{{$house->id}}" data-floors="{{$house->no_of_floors}}">{{$house->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <select name="floor" id="" class="form-control select-floor" required>
                                        <option value="">--Floor--</option>

                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px">
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" placeholder="Room Name" required>
                                </div>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" min="1" max="999" name="beds" placeholder="Beds" required>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px">
                                <div class="col-sm-12">
                                    <button class="btn btn-success">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Room List </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered" id="{{ (sizeof($rooms)>0)?'roomTable':'' }}">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Room Name</th>
                                    <th>House</th>
                                    <th>Floor</th>
                                    <th>No. of Beds</th>
                                    <th>Empty Beds</th>
                                    @if( in_array('house/room.assign-beds',$pageAccessData) || in_array('house/room.delete',$pageAccessData) || in_array('house/room.edit',$pageAccessData) )
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rooms as $room)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$room->name}}</td>
                                        <td>{{$room->house->name}}</td>
                                        <td>{{$room->floor_no}}</td>
                                        <td>{{$room->no_of_beds}}</td>
                                        <td>{{$room->no_of_beds-sizeof($room->roomStudents)}}</td>
                                        @if( in_array('house/room.assign-beds',$pageAccessData) || in_array('house/room.delete',$pageAccessData) || in_array('house/room.edit',$pageAccessData)  )
                                            <td>
                                            @if( in_array('house/room.assign-beds',$pageAccessData))
                                                <a class="btn btn-success btn-xs"
                                                        href="{{url('/house/assign-beds/'.$room->id)}}"
                                                        data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                                        class="fa fa-bed"></i></a>
                                            @endif
                                            @if( in_array('house/room.edit',$pageAccessData))
                                                <a class="btn btn-primary btn-xs"
                                                        href="{{url('/house/edit-room/'.$room->id)}}"
                                                        data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i
                                                            class="fa fa-edit"></i></a>
                                            @endif
                                            @if( in_array('house/room.delete',$pageAccessData))
                                                <a href="{{url('/house/delete-room/'.$room->id)}}"
                                                    class="btn btn-danger btn-xs"
                                                    onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                                    data-content="delete"><i class="fa fa-trash-o"></i></a>
                                            @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="50">
                                            <div class="text-danger" style="text-align: center">No Room Found</div>
                                        </td>
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
        $('#houseTable').DataTable();
        $('#roomTable').DataTable();

        $('#house-master-field').select2();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('.select-house').change(function () {
            console.log("Dhukse");
            var floors = $(this).find(':selected').data('floors');

            $('.select-floor').empty();
            $('.select-floor').append('<option value="">--Floor--</option>');

            for (let i = 1; i <= floors; i++) {
                $('.select-floor').append('<option value="'+i+'">'+i+'</option>');
            }
        });
    });
</script>
@stop
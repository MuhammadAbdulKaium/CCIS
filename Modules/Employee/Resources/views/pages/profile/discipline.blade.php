
@extends('employee::layouts.profile-layout')

@section('profile-content')
    @if (in_array('employee/discipline.create', $pageAccessData))
        <p class="text-right">
            <a class="btn btn-primary btn-sm" href="{{url('/employee/profile/create/discipline/'.$employeeInfo->id)}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Add</a>
        </p>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <th>SL</th>
            <th>Cadet College</th>
            <th>Occurrence Date</th>
            <th>Place/Location</th>
            <th>Description</th>
            <th>Punishment Category</th>
            <th>Punishment By</th>
            <th>Remarks</th>
            <th>Attachment</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach($allDisciplines as $discipline)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{ $discipline->singleInstitute?$discipline->singleInstitute->institute_alias:" " }}</td>
                    <td>
                       {{ date('d/m/Y', strtotime($discipline->occurrence_date)) }}
                    </td>
                    <td>{{$discipline->place}}</td>
                    <td>{{$discipline->description}}</td>
                    <td>{{$discipline->punishment_category}}</td>
                    <td>
                        @if ($discipline->punishment_by_select)
                            {{ $discipline->singlePunishmentBy->title ." ".$discipline->singlePunishmentBy->first_name." ".$discipline->singlePunishmentBy->middle_name." ".$discipline->singlePunishmentBy->last_name }} 
                            <br>
                        @endif
                        @if ($discipline->punishment_by_write)
                            {{ $discipline->punishment_by_write }}
                            <br>
                        @endif
                        Date: {{ date('d/m/Y', strtotime($discipline->punishment_date)) }}
                    </td>
                    <td>{{$discipline->remarks}}</td>
                    <td>
                        @if (str_contains($discipline->attachment, 'jpeg') ||str_contains($discipline->attachment, 'jpg') || str_contains($discipline->attachment, 'png'))
                           
                            <a href="{{ asset('assets/Employee/Discipline/' . $discipline->attachment) }}"
                                target="_blank">
                                <img src="{{ asset('assets/Employee/Discipline/' . $discipline->attachment) }}"
                                    id="attach_img" height="40" width="40" alt="">
                            </a>
                            @elseif (str_contains($discipline->attachment, 'pdf'))
                            <a href="{{ asset('assets/Employee/Discipline/' . $discipline->attachment) }}"
                                target="_blank">
                                <i style="font-size: 30px;" class="fa fa-file-pdf-o" title="{{ $discipline->attachment }}" aria-hidden="true"></i> 
                            </a>
                        @endif
                    </td>
                    
                    <td>
                        @if (in_array('employee/discipline.edit', $pageAccessData))
                        <a class="btn btn-primary btn-xs" href="{{ url('employee/profile/edit/discipline/'.$discipline->id) }}"
                           data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                    class="fa fa-edit"></i></a>
                        @endif
                        @if (in_array('employee/discipline.delete', $pageAccessData))
                        <a href="{{ url('employee/profile/delete/discipline/'.$discipline->id) }}" class="btn btn-danger btn-xs"
                           onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                           data-content="delete"><i class="fa fa-trash-o"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

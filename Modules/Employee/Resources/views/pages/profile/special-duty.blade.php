
@extends('employee::layouts.profile-layout')

@section('profile-content')
    @if (in_array('employee/special-duty.create', $pageAccessData))
        <p class="text-right">
            <a class="btn btn-primary btn-sm" href="{{url('/employee/profile/create/special-duty/'.$employeeInfo->id)}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Add</a>
        </p>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="border-bottom: 0;">SL</th>
                    <th>Cadet College</th>
                    <th>Description</th>
                    <th colspan="2">
                        Duration
                    </th>
                    <th>Remarks</th>
                    <th>Attachment</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <th colspan="2"></th>
                    <th>From</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To &nbsp;&nbsp;</th>
                    <th colspan="3"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($specialDuties as $duty)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{ $duty->singleInstitute?$duty->singleInstitute->institute_alias:" " }}</td>
                    <td>
                       {{ $duty->description }}
                    </td>
                    <td>{{($duty->start_date)?date('d/m/Y', strtotime($duty->start_date)):" "}}</td>
                    <td>{{($duty->end_date)?date('d/m/Y', strtotime($duty->end_date)):"To This Day"}}</td>
                    <td>{{$duty->remarks}}</td>
                    <td>
                        @if (str_contains($duty->attachment, 'jpeg') ||str_contains($duty->attachment, 'jpg') || str_contains($duty->attachment, 'png'))
                            <a href="{{ asset('assets/Employee/SpecialDuty/' . $duty->attachment) }}"
                                target="_blank">
                                <img src="{{ asset('assets/Employee/SpecialDuty/' . $duty->attachment) }}"
                                    id="attach_img" height="50" width="60" alt="">
                            </a>
                        @elseif (str_contains($duty->attachment, 'pdf'))
                            <a href="{{ asset('assets/Employee/SpecialDuty/' . $duty->attachment) }}"
                                target="_blank">
                                <i style="font-size: 30px;" class="fa fa-file-pdf-o" title="{{ $duty->attachment }}" aria-hidden="true"></i> 
                            </a>
                        @endif

                       
                    </td>
                   
                    
                    <td>
                        @if (in_array('employee/special-duty.edit', $pageAccessData))
                        <a class="btn btn-primary btn-xs" href="{{ url('employee/profile/edit/special-duty/'.$duty->id) }}"
                           data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                    class="fa fa-edit"></i></a>
                        @endif
                        @if (in_array('employee/special-duty.delete', $pageAccessData))
                        <a href="{{ url('employee/profile/delete/special-duty/'.$duty->id) }}" class="btn btn-danger btn-xs"
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

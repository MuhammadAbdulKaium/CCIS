
@extends('employee::layouts.profile-layout')

@section('styles')
    <style>
        .select2-selection {
            min-height: 35px !important;
        }
    </style>
@endsection

@section('profile-content')
    @if (in_array('employee/award.create', $pageAccessData))
        <p class="text-right">
            <a class="btn btn-primary btn-sm" href="{{url('/employee/profile/create/award/'.$employeeInfo->id)}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" 
            data-modal-size="modal-md">Add</a>
        </p>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Institute</th>
                    <th>Award Name</th>
                    <th>Award Description</th>
                    <th>Awarded On</th>
                    <th>Awarded By</th>
                    <th>Attachment</th>
                    <th>Remarks</th>
                    <th>Created By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($awards as $award)
                    @php
                        $date = Carbon\Carbon::parse($award->awarded_on);
                    @endphp
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $award->institute->institute_alias }}</td>
                        <td>{{ $award->name }}</td>
                        <td>{{ $award->description }}</td>
                        <td>@if ($award->awarded_on) {{ $date->format('d M Y') }} @endif</td>
                        <td>
                            @if ($award->awarded_by_employee) 
                                {{ $award->awardedBy->first_name }} {{ $award->awardedBy->last_name }} 
                            @else
                                {{ $award->awarded_by_name }} 
                            @endif
                        </td>
                        <td>
                            @if ($award->attachment)
                                <a href="{{ asset('/assets/Employee/awards/'.$award->attachment) }}" target="_blank">{{ $award->attachment }}</a>
                            @endif
                        </td>
                        <td>{{ $award->remarks }}</td>
                        <td>{{ $award->createdBy->name }}</td>
                        <td>
                            @if (in_array('employee/award.edit', $pageAccessData))
                                <a class="btn btn-primary btn-xs"
                                href="{{ url('employee/profile/edit/award/' . $award->id) }}"
                                data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i
                                class="fa fa-edit"></i></a>
                            @endif
                            @if (in_array('employee/award.delete', $pageAccessData))
                                <a href="{{ url('employee/profile/delete/award/' . $award->id) }}"
                                class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')"
                                data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                            @endif    
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

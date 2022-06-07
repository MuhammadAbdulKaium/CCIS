@extends('employee::layouts.profile-layout')

@section('profile-content')
    @if (in_array('employee/acr.create', $pageAccessData))
    <p class="text-right">
        <a class="btn btn-primary btn-sm" href="{{ url('/employee/profile/create/acr/' . $employeeInfo->id) }}"
            oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                class="fa fa-plus-square" aria-hidden="true"></i> Add</a>
    </p>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th rowspan="2">SL</th>
                    <th rowspan="2">Cadet College</th>
                    <th rowspan="2"><span>Years</span></th>
                    <th colspan="2">
                        Grading
                    </th>
                    <th rowspan="2">Average Number</th>
                    <th colspan="2">Recommendation</th>
                    <th colspan="2">Name</th>
                    <th rowspan="2">Remarks</th>
                    <th rowspan="2">Attachment</th>
                    <th rowspan="2">Action</th>
                </tr>
                <tr>
                    <th>Initiative Officer</th>
                    <th>Higher Officer</th>
                    <th>IO</th>
                    <th>HO</th>
                    <th>IO</th>
                    <th>HO</th>
                </tr>

            </thead>
            <tbody>
               
                @foreach ($employee_acrs as $acr_data)
                @php
                    $average_number =0;
                    $average_number += ($acr_data->initiative_officer + $acr_data->higher_officer)/2;
                @endphp
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $acr_data->singleInstitute?$acr_data->singleInstitute->institute_alias:" " }}</td>
                        <td>
                            {{ $acr_data->year }}
                        </td>
                        <td>
                            {{ $acr_data->initiative_officer }} %
                        </td>
                        <td>
                            {{ $acr_data->higher_officer }} %
                        </td>
                        <td>{{  $average_number }}</td>
                        <td>
                            @switch($acr_data->io)
                                @case(1)
                                    <span class="text-success">Recommended</span>
                                @break

                                @case(2)
                                    <span class="text-danger"> Not Recommended</span>
                                @break
                            @endswitch
                        </td>
                        <td>
                            @switch($acr_data->ho)
                                @case(1)
                                    <span class="text-success">Recommended</span>
                                @break

                                @case(2)
                                    <span class="text-danger"> Not Recommended</span>
                                @break
                            @endswitch
                        </td>
                        <td>
                            {{ $acr_data->employeeIoName?($acr_data->employeeIoName->title . ' ' . $acr_data->employeeIoName->first_name . ' ' . $acr_data->employeeIoName->last_name):" " }}
                        </td>
                        <td>
                            {{ $acr_data->employeeHoName?($acr_data->employeeHoName->title . ' ' . $acr_data->employeeHoName->first_name . ' ' . $acr_data->employeeHoName->last_name):" " }}
                        </td>
                       

                        <td>
                            {{ $acr_data->remarks }}
                        </td>
                        <td>
                            @if (str_contains($acr_data->attachment, 'jpeg') || str_contains($acr_data->attachment, 'jpg') || str_contains($acr_data->attachment, 'png'))
                                <a href="{{ asset('assets/Employee/ACR/' . $acr_data->attachment) }}"
                                    target="_blank">
                                    <img src="{{ asset('assets/Employee/ACR/' . $acr_data->attachment) }}"
                                        id="attach_img" width="30" alt="">
                                </a>
                            @elseif (str_contains($acr_data->attachment, 'pdf'))
                                <a href="{{ asset('assets/Employee/ACR/' . $acr_data->attachment) }}"
                                    target="_blank">
                                    <i style="font-size: 20px;" class="fa fa-file-pdf-o"
                                        title="{{ $acr_data->attachment }}" aria-hidden="true"></i>
                                </a>
                           
                            @endif
                        </td>
                        <td>
                            @if (in_array('employee/acr.edit', $pageAccessData))
                            <a class="btn btn-primary btn-xs"
                                href="{{ url('employee/profile/edit/acr/' . $acr_data->id) }}"
                                data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                    class="fa fa-edit"></i></a>
                            @endif
                            @if (in_array('employee/acr.delete', $pageAccessData))
                            <a href="{{ url('employee/profile/delete/acr/' . $acr_data->id) }}"
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

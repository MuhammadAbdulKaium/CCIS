
@extends('employee::layouts.profile-layout')

@section('profile-content')
    @if (in_array('employee/qualification.create', $pageAccessData))
        <p class="text-right">
            <a class="btn btn-primary btn-sm" href="{{url('/employee/profile/create/qualification/'.$employeeInfo->id)}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Add</a>
        </p>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <th>SL</th>
            <th>Cadet College</th>
            <th>Type</th>
            <th>Year</th>
            <th>Group/Division</th>
            <th>Name</th>
            <th>Board/University</th>
            <th>Institute Address</th>
            <th>Marks</th>
            <th>Attachment</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach($qualifications as $qualification)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{ $qualification->institute->institute_alias }}</td>
                    <td>
                        @if($qualification->qualification_type == 1)
                            General Qualification
                        @elseif($qualification->qualification_type == 2)
                            Special Qualification
                        @elseif($qualification->qualification_type == 3)
                            Last Academic Qualification
                        @endif
                    </td>
                    <td>{{$qualification->qualification_year}}</td>
                    <td>{{$qualification->qualification_group}}</td>
                    <td>{{$qualification->qualification_name}}</td>
                    <td>{{$qualification->qualification_institute}}</td>
                    <td>{{$qualification->qualification_institute_address}}</td>
                    <td>{{$qualification->qualification_marks}}</td>
                    <td>
                        @if (str_contains($qualification->qualification_attachment, 'jpeg') ||str_contains($qualification->qualification_attachment, 'jpg') || str_contains($qualification->qualification_attachment, 'png'))
                           
                            <a href="{{ asset('/employee-attachment/' . $qualification->qualification_attachment) }}"
                                target="_blank">
                                <img src="{{ asset('/employee-attachment/' . $qualification->qualification_attachment) }}"
                                    id="attach_img" height="40" width="40" alt="">
                            </a>
                            @elseif (str_contains($qualification->qualification_attachment, 'pdf'))
                            <a href="{{ asset('/employee-attachment/' . $qualification->qualification_attachment) }}"
                                target="_blank">
                                <i style="font-size: 30px;" class="fa fa-file-pdf-o" title="{{ $qualification->qualification_attachment }}" aria-hidden="true"></i> 
                            </a>
                        @endif
                    </td>
                    <td>
                        @if (in_array('employee/qualification.edit', $pageAccessData))
                        <a class="btn btn-primary btn-xs" href="{{ url('employee/profile/edit/qualification/'.$qualification->id) }}"
                           data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                    class="fa fa-edit"></i></a>
                        @endif
                        @if (in_array('employee/qualification.delete', $pageAccessData))
                        <a href="{{ url('employee/profile/delete/qualification/'.$qualification->id) }}" class="btn btn-danger btn-xs"
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

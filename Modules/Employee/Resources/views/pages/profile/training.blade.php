
@extends('employee::layouts.profile-layout')

@section('profile-content')
    @if (in_array('employee/training.create', $pageAccessData))
        <p class="text-right">
            <a class="btn btn-primary btn-sm" href="{{url('/employee/profile/create/training/'.$employeeInfo->id)}}" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square" aria-hidden="true"></i> Add</a>
        </p>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <th>SL</th>
            <th>Cadet College</th>
            <th>Training Name</th>
            <th>Training Institute</th>
            <th  style="padding: 0;">Training Period
                <table class="table table-bordered" style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                </table>
            </th>
            <th>Grading</th>
            <th>Remarks</th>
            <th>Attachment</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach($allTraining as $training)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{ $training->singleInstitute?$training->singleInstitute->institute_alias:" " }}</td>
                    <td>
                       {{ $training->training_name }}
                    </td>
                    <td>{{$training->training_institute}}</td>
                    <td style="padding: 0;">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tbody>
                                <tr>
                                    <td>{{date('d/m/Y', strtotime($training->training_from))}}</td>
                                    <td>{{date('d/m/Y', strtotime($training->training_to))}}</td>
                                    <td>{{$training->training_duration}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>{{$training->training_grading}}</td>
                    <td>{{$training->remarks}}</td>
                    <td>
                        @if (str_contains($training->attachment, 'jpeg') ||str_contains($training->attachment, 'jpg') || str_contains($training->attachment, 'png'))
                           
                            <a href="{{ asset('assets/Employee/Training/' . $training->attachment) }}"
                                target="_blank">
                                <img src="{{ asset('assets/Employee/Training/' . $training->attachment) }}"
                                    id="attach_img" height="20" width="40" alt="">
                            </a>
                            @elseif (str_contains($training->attachment, 'pdf'))
                            <a href="{{ asset('assets/Employee/Training/' . $training->attachment) }}"
                                target="_blank">
                                <i style="font-size: 20px;" class="fa fa-file-pdf-o" title="{{ $training->attachment }}" aria-hidden="true"></i> 
                            </a>
                        @endif

                       
                    </td>
                   
                    
                    <td>
                        @if (in_array('employee/training.edit', $pageAccessData))
                            <a class="btn btn-primary btn-xs" href="{{ url('employee/profile/edit/training/'.$training->id) }}"
                            data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                        class="fa fa-edit"></i></a>
                        @endif
                        @if (in_array('employee/training.delete', $pageAccessData))
                            <a href="{{ url('employee/profile/delete/training/'.$training->id) }}" class="btn btn-danger btn-xs"
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

@extends('employee::layouts.profile-layout')

@section('profile-content')
 @if (in_array('employee/contribution-board-result.create', $pageAccessData))
    <p class="text-right">
        <a class="btn btn-primary btn-sm"
            href="{{ url('/employee/profile/create/contribution-board-result/' . $employeeInfo->id) }}"
            oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                class="fa fa-plus-square" aria-hidden="true"></i> Add</a>
    </p>
 @endif
    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th><span>Years</span></th>
                    <th><span>Cadet College</span></th>
                    <th>Exam Name</th>
                    <th>Total Cadet</th>
                    <th>GPA-5</th>
                    <th>Not GPA-5</th>
                    <th>Remarks</th>
                    <th>Attachment</th>
                    <th>Action</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($exam_years_groups as $key => $groups)
                    @php
                        $flag = true;
                    @endphp
                    @foreach ($groups as $group)
                        <tr>
                            @if ($flag)
                                @php
                                    $flag = false;
                                @endphp
                                <th rowspan="{{ count($groups) }}">{{ $key }}</th>
                            @endif
                            <td>{{ $group->singleInstitute?$group->singleInstitute->institute_alias:" " }}</td>
                            <td>{{ $group->exam_name }}</td>
                            <td>{{ $group->total_cadet }}</td>
                            <td>
                                @php
                                    $total_gpa = json_decode($group->total_gpa);
                                @endphp
                                @if ($group->gpa_type == 0)
                                    @foreach ($total_gpa as $gpa)
                                        {{ $gpa->gpa }}
                                    @endforeach
                                @endif
                                @if ($group->gpa_type == 1)
                                    @foreach ($total_gpa as $gpa)
                                        <strong>{{ $group->getSubject($gpa->subject) }}: </strong>{{ $gpa->gpa }} <br>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @php
                                    $not_total_gpa = json_decode($group->not_total_gpa);
                                @endphp
                                @if ($group->gpa_type == 0)
                                    @foreach ($not_total_gpa as $gpa)
                                        {{ $gpa->gpa }}
                                    @endforeach
                                @endif
                                @if ($group->gpa_type == 1)
                                    @foreach ($not_total_gpa as $gpa)
                                        <strong>{{ $group->getSubject($gpa->subject) }}: </strong>{{ $gpa->gpa }}
                                        <br>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                {{ $group->remarks }}
                            </td>
                            <td>
                                @if (str_contains($group->attachment, 'jpeg') || str_contains($group->attachment, 'jpg') || str_contains($group->attachment, 'png'))
                                    <a href="{{ asset('assets/Employee/CBR/' . $group->attachment) }}"
                                        target="_blank">
                                        <img src="{{ asset('assets/Employee/CBR/' . $group->attachment) }}"
                                            id="attach_img" width="30" alt="">
                                    </a>
                                @elseif (str_contains($group->attachment, 'pdf'))
                                    <a href="{{ asset('assets/Employee/CBR/' . $group->attachment) }}"
                                        target="_blank">
                                        <i style="font-size: 20px;" class="fa fa-file-pdf-o"
                                            title="{{ $group->attachment }}" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if (in_array('employee/contribution-board-result.edit', $pageAccessData))
                                <a class="btn btn-primary btn-xs"
                                    href="{{ url('employee/profile/edit/contribution-board-result/' . $group->id) }}"
                                    data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                        class="fa fa-edit"></i></a>
                                    @endif
                                    @if (in_array('employee/contribution-board-result.delete', $pageAccessData))
                                    <a href="{{ url('employee/profile/delete/contribution-board-result/' . $group->id) }}"
                                    class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')"
                                    data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

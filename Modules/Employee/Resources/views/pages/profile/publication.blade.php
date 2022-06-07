@extends('employee::layouts.profile-layout')

@section('profile-content')
    @if (in_array('employee/publication.create', $pageAccessData))
        <p class="text-right">
            <a class="btn btn-primary btn-sm" href="{{ url('/employee/profile/create/publication/' . $employeeInfo->id) }}"
                oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                    class="fa fa-plus-square" aria-hidden="true"></i> Add</a>
        </p>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Cadet College</th>
                    <th>Publication Title</th>
                    <th>
                        Publication Description
                    </th>
                    <th>Publication Time</th>
                    <th>Attachment</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($publications as $pub_data)
                    @php
                        $flag = true;
                    @endphp
                    @foreach ($pub_data->publicationEditions as $edition)
                        <tr>
                            @if ($flag)
                                <td rowspan="{{ count($pub_data->publicationEditions) }}">{{ $loop->index + 1 }}</td>
                                <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                    {{ $pub_data->singleInstitute ? $pub_data->singleInstitute->institute_alias : ' ' }}</td>

                                <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                    {{ $pub_data->publication_title }}
                                </td>
                                <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                    {{ $pub_data->publication_description }}
                                </td>
                            @endif
                            <td>
                                <strong>{{ $edition->editions }}: </strong> <span>{{ date('d/m/Y',strtotime($edition->date))}}</span> <br>

                            </td>
                            <td>
                                @if (str_contains($edition->attachment, 'jpeg') || str_contains($edition->attachment, 'jpg') || str_contains($edition->attachment, 'png'))
                                    <a href="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                        target="_blank">
                                        <img src="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                            id="attach_img" width="30" alt="">
                                    </a>
                                @elseif (str_contains($edition->attachment, 'pdf'))
                                    <a href="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                        target="_blank">
                                        <i style="font-size: 20px;" class="fa fa-file-pdf-o"
                                            title="{{ $edition->attachment }}" aria-hidden="true"></i>
                                    </a>
                                @elseif (str_contains($edition->attachment, 'doc') || str_contains($edition->attachment, 'docx'))
                                    <a href="{{ asset('assets/Employee/Publication/' . $edition->attachment) }}"
                                        target="_blank">
                                        <i style="font-size: 20px;" class="fa fa-file-text"
                                            title="{{ $edition->attachment }}" aria-hidden="true"></i>
                                    </a>
                                @endif

                            </td>

                            <td>
                                {{ $edition->remarks }} <br>
                            </td>
                            @if ($flag)
                                @php
                                    $flag = false;
                                @endphp
                                <td rowspan="{{ count($pub_data->publicationEditions) }}">
                                    @if (in_array('employee/publication.edit', $pageAccessData))
                                        <a class="btn btn-primary btn-xs"
                                            href="{{ url('employee/profile/edit/publication/' . $pub_data->id) }}"
                                            data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i
                                                class="fa fa-edit"></i></a>
                                    @endif
                                    @if (in_array('employee/publication.delete', $pageAccessData))
                                        <a href="{{ url('employee/profile/delete/publication/' . $pub_data->id) }}"
                                            class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')"
                                            data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    @endif

                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

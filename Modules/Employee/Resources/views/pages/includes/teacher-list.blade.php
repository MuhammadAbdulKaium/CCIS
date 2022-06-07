<div class="box box-solid">
    <div class="et">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> Employee Register
                <span id="employee_count">({{$allEmployee->count()}})</span></h3>
            <div class="box-tools">
                <form id="w0" action="{{url("/employee/manage/download/excel")}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="department"
                           @if(!empty($allSearchInputs['department'])) value="{{$allSearchInputs['department']}} @endif">
                    <input type="hidden" name="designation"
                           @if(!empty($allSearchInputs['designation']))  value="{{$allSearchInputs['designation']}}" @endif>
                    <input type="hidden" name="category"
                           @if(!empty($allSearchInputs['category']))  value="{{$allSearchInputs['category']}}" @endif>
                    <input type="hidden" name="email"
                           @if(!empty($allSearchInputs['email']))  value="{{$allSearchInputs['email']}}" @endif>
                    <input type="hidden" name="gr_no"
                           @if(!empty($allSearchInputs['gr_no']))  value="{{$allSearchInputs['gr_no']}}" @endif>
                    <input type="hidden" name="emp_id"
                           @if(!empty($allSearchInputs['id']))  value="{{$allSearchInputs['id']}}" @endif>
                    {{--<input type="hidden" name="section" value="{{$allSearchInputs['section']}}">--}}
                    {{--<input type="hidden" name="stu_detail_search" value="{{$allSearchInputs['academic_level']}}">--}}
                    <input type="hidden" name="student_name" value="{{csrf_token()}}">
                    {{-- <button type="submit" class="btn btn-primary">
                        <i class="icon-user icon-white"></i> Excel
                    </button> --}}
                </form>
            </div>
        </div>
    </div>
    <div class="box-body table-responsive">
        @if(!empty($allEmployee) AND $allEmployee->count()>0)
            <table id="example2" class="table table-striped table-bordered text-center">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Photo</th>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Married</th>
                    <th>DoB-DoJ-DoR</th>
                    <th>Contact</th>
                    <th>Emergency</th>
                    <th>Blood</th>
                    <th>Category</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Position</th>
                    <th>Central Position</th>
                    <th>Medical</th>
                    <th>Appointment</th>
                    <th>Class Entitlement</th>
                    <th>Status</th>
                    <th>Child</th>
                    <th>Activity</th>
                    <th>Schedule</th>
                    <th>Payroll</th>
                    <th>Leave</th>
                    <th>Ration</th>
                    <th>History</th>
                </tr>
                </thead>
                <tbody id="table">
                @php $count=0; @endphp
                @foreach($empkeySort as $index => $val)
                    @php

                        $employee=$allEmployee[$val];
                       $total_child=$employee->childCount();

                    @endphp

                    @if($child_count!=null  )

                        @if( $total_child==$child_count)
                            @php $count++;
                            @endphp
                            <tr>
                                <td>{{$count}}</td>
                                <td>
                                    @if($employee->singelAttachment("PROFILE_PHOTO"))
                                        <img class="center-block img-thumbnail img-responsive"
                                             src="{{URL::asset('assets/users/images/'.$employee->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"
                                             alt="No Image" style="width:60px;height:auto">
                                    @elseif($employee->category == 1)
                                        <img class="center-block img-thumbnail img-responsive"
                                             src="{{URL::asset('assets/users/images/user-teaching.png')}}"
                                             alt="No Image"
                                             style="width:60px;height:auto">
                                    @elseif($employee->category == 2)
                                        <img class="center-block img-thumbnail img-responsive"
                                             src="{{URL::asset('assets/users/images/user-non-teaching.png')}}"
                                             alt="No Image"
                                             style="width:60px;height:auto">
                                    @endif
                                </td>
                                <td>{{$employee->user()->username}}</td>
                                <td>
                                    @if (in_array('employee/profile', $pageAccessData))
                                        <a href="{{url('/employee/profile/personal/'.$employee->id)}}">{{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}} @if($employee->alias)
                                                ({{$employee->alias}}) @endif</a>
                                    @else
                                        {{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}} @if($employee->alias)
                                            ({{$employee->alias}}) @endif
                                    @endif
                                </td>
                                <td>@if($employee->marital_status == 'MARRIED') Yes @else No @endif</td>
                                <td>
                                    <b>DoB</b>:{{date('d/m/Y', strtotime($employee->dob))}} <br>
                                    <b>DoJ</b>:{{date('d/m/Y', strtotime($employee->doj))}} <br>
                                    <b>DoR</b>:{{date('d/m/Y', strtotime($employee->dor))}}
                                </td>
                                <td>
                                    @if($employee->phone){{$employee->phone}} <br>@endif
                                    @if($employee->alt_mobile){{$employee->alt_mobile}} <br>@endif
                                    @if($employee->email){{$employee->email}}@endif
                                </td>
                                <td></td>
                                <td>@if($employee->blood_group) {{$employee->blood_group}} @endif</td>
                                <td>
                                    @if($employee->category == 1)
                                        Teaching
                                    @elseif($employee->category == 2)
                                        Non Teaching
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($employee->department()))
                                        {{$employee->department()->name}}
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($employee->designation()))
                                        {{$employee->designation()->name}}
                                    @endif
                                </td>
                                <td>{{$employee->position_serial}}</td>
                                <td></td>
                                <td></td>
                                @php




                                        @endphp
                                <td>
                                    {{--Checking Status--}}

                                    <a href="{{url('/employee/change-status/'.$employee->id)}}" title="Employe status"
                                       data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">

                                        @if($status=$employee->currentStatus())
                                            <span class=" @if($status->status->category==1)text-success @elseif($status->status->category==2)
                                                    text-warning  @elseif($status->status->category==3)
                                                    text-danger
@endif">


                                        @if($status->status->category==1) Active @elseif($status->status->category==2)
                                                    Inactive  @elseif($status->status->category==3)
                                                    Closed
                                                @endif
                                        -{{$status->status->status}}
</span>
                                        @else

                                            No Status assigned
                                        @endif
                                    </a>


                                </td>

                                <td>
                                    <a href="{{url('/employee/show/childs/'.$employee->id)}}"
                                       data-target="#globalModal" data-toggle="modal"
                                       data-modal-size="modal-sm">{{$total_child}}</a>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    @else
                        @php $count++;
                        @endphp
                        <tr>
                            <td>{{($index+1)}}</td>
                            <td>
                                @if($employee->singelAttachment("PROFILE_PHOTO"))
                                    <img class="center-block img-thumbnail img-responsive"
                                         src="{{URL::asset('assets/users/images/'.$employee->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"
                                         alt="No Image" style="width:60px;height:auto">
                                @elseif($employee->category == 1)
                                    <img class="center-block img-thumbnail img-responsive"
                                         src="{{URL::asset('assets/users/images/user-teaching.png')}}" alt="No Image"
                                         style="width:60px;height:auto">
                                @elseif($employee->category == 2)
                                    <img class="center-block img-thumbnail img-responsive"
                                         src="{{URL::asset('assets/users/images/user-non-teaching.png')}}"
                                         alt="No Image"
                                         style="width:60px;height:auto">
                                @endif
                            </td>
                            <td>{{$employee->user()->username}}</td>
                            <td>
                                @if (in_array('employee/profile', $pageAccessData))
                                    <a target="_blank"
                                       href="{{url('/employee/profile/personal/'.$employee->id)}}">{{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}} @if($employee->alias)
                                            ({{$employee->alias}}) @endif</a>
                                @else
                                    {{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}} @if($employee->alias)
                                        ({{$employee->alias}}) @endif
                                @endif
                            </td>
                            <td>@if($employee->marital_status == 'MARRIED') Yes @else No @endif</td>
                            <td>
                                <b>DoB</b>:{{date('d/m/Y', strtotime($employee->dob))}} <br>
                                <b>DoJ</b>:{{date('d/m/Y', strtotime($employee->doj))}} <br>
                                <b>DoR</b>:{{date('d/m/Y', strtotime($employee->dor))}}
                            </td>
                            <td>
                                @if($employee->phone){{$employee->phone}} <br>@endif
                                @if($employee->alt_mobile){{$employee->alt_mobile}} <br>@endif
                                @if($employee->email){{$employee->email}}@endif
                            </td>
                            <td></td>
                            <td>@if($employee->blood_group) {{$employee->blood_group}} @endif</td>
                            <td>
                                @if($employee->category == 1)
                                    Teaching
                                @elseif($employee->category == 2)
                                    Non Teaching
                                @endif
                            </td>
                            <td>
                                @if(!empty($employee->department()))
                                    {{$employee->department()->name}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($employee->designation()))
                                    {{$employee->designation()->name}}
                                @endif
                            </td>
                            <td>{{$employee->position_serial}}</td>
                            <td>{{$employee->central_position_serial}}</td>
                            <td>{{$employee->medical_category}}</td>
                            <td></td>
                            <td></td>
                            @php




                                    @endphp
                            <td>
                                {{--Checking Status--}}

                                <a href="{{url('/employee/change-status/'.$employee->id)}}" title="Employe status"
                                   data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">

                                    @if($status=$employee->currentStatus())
                                        <span class=" @if($status->status->category==1)text-success @elseif($status->status->category==2)
                                                text-warning  @elseif($status->status->category==3)
                                                text-danger @endif">
                                        @if($status->status->category==1) Active @elseif($status->status->category==2)
                                                Inactive  @elseif($status->status->category==3)
                                                Closed
                                            @endif
                                        -{{$status->status->status}}
</span>
                                    @else

                                        No Status assigned
                                    @endif
                                </a>


                            </td>

                            <td>
                                <a href="{{url('/employee/show/childs/'.$employee->id)}}"
                                   data-target="#globalModal" data-toggle="modal"
                                   data-modal-size="modal-sm">{{$total_child}}</a>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        @else
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-warning"></i> No result found. </h5>
            </div>
        @endif
    </div>
</div>
<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>

<script>
    function openStatus() {
        alert("hellow")

    }
    @php

        if(!isset($count)){
        $count=0;
    }
    @endphp
    var empCount = document.getElementById('employee_count');
    empCount.innerHTML = '(' + {{$count}} + ')';


    jQuery(document).ready(function () {
        // emp_sort_order click action
        $("#emp_sort_order").click(function () {
            // checking
            if ($(this).is(':checked')) {
                // attendance looping
                $("#table input").each(function () {
                    // remove class
                    $(this).removeAttr('readonly');
                });
                // emp_web_sort
                //emp_web_sort();
            } else {
                // attendance looping
                $("#table input").each(function () {
                    // remove class
                    $(this).attr('readonly', 'readonly');
                });
                // emp_web_sort
                //emp_web_sort();
            }
        });

        $("#table input").keyup(function () {

            var emp_id = $(this).attr('id');
            var web_position = $(this).val();
            var _token = '{{csrf_token()}}';
            // change background color
            $(this).css("background-color", "pink");

            // checking
            if (web_position && emp_id && $.isNumeric(web_position)) {
                // ajax request
                $.ajax({
                    url: '/employee/update/web-position',
                    type: 'POST',
                    cache: false,
                    data: {'emp_id': emp_id, 'sort_order': web_position, '_token': _token}, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function () {
                        // show waiting dialog
                        // waitingDialog.show('Loading...');
                    },

                    success: function (data) {
                        // hide waiting dialog
                        //waitingDialog.hide();
                        // background
                    },

                    error: function () {
                        // hide waiting dialog
                        waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            } else {
                swal("Warning", 'Invalid input', "warning");
                $(this).val('');
            }

        });

        {{--function emp_web_sort() {--}}
        {{--$("#table input").keyup(function(){--}}

        {{--var emp_id = $(this).attr('id');--}}
        {{--var web_position = $(this).val();--}}
        {{--var _token = '{{csrf_token()}}';--}}

        {{--// checking--}}
        {{--if(web_position && emp_id){--}}
        {{--// ajax request--}}
        {{--$.ajax({--}}
        {{--url: '/employee/update/web-position',--}}
        {{--type: 'POST',--}}
        {{--cache: false,--}}
        {{--data: {'emp_id': emp_id,'sort_order': web_position,'_token': _token }, //see the $_token--}}
        {{--datatype: 'application/json',--}}

        {{--beforeSend: function() {--}}
        {{--// show waiting dialog--}}
        {{--waitingDialog.show('Loading...');--}}
        {{--},--}}

        {{--success:function(data){--}}
        {{--// hide waiting dialog--}}
        {{--waitingDialog.hide();--}}
        {{--// background--}}
        {{--},--}}
        {{--error:function(){--}}
        {{--// hide waiting dialog--}}
        {{--waitingDialog.hide();--}}
        {{--// sweet alert--}}
        {{--swal("Error", 'Unable to load data form server', "error");--}}
        {{--}--}}
        {{--});--}}
        {{--}--}}

        {{--});--}}
        {{--}--}}




    });
</script>
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"> <i class="fa fa-info-circle"></i> Leave Application</h4>
    </div>
    <form id="leave_approval_form">
        @csrf
    <div class="modal-body">
        <div class="box-body">
            <div class="col-md-4">
                <table class="table table-bordered">
                   <tbody>
                   <tr>
                       <input type="hidden" name="emp_id" value="{{$leaveApplication->employee_id}}">
                       <input type="hidden" name="leave_application_id" value="{{$leaveApplication->id}}">
                       <th>Employee ID</th>
                       <td>{{$employeeInformation->singleUser->username}}</td>
                   </tr>
                   <tr>
                       <th>Name</th>
                       <td>{{$employeeInformation->title}} {{$employeeInformation->first_name}} {{$employeeInformation->last_name}}</td>
                   </tr>
                   <tr>
                        <th>Department</th>
                        <td>{{$employeeInformation->singleDepartment->name}}</td>
                   </tr>
                   <tr>
                       <th>Designation</th>
                       <td> {{$employeeInformation->singleDesignation->name}}</td>
                   </tr>
                   </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <table class="table">
                    <thead>
                        <th>Leave Structure Name</th>
                        <th>Remains</th>
                    </thead>
                    <tbody>
                    @foreach($employeeLeaveAssign as $leavesAssign)
                        <tr>
                            <td>{{$leavesAssign->leaveStructureDetail->leave_name}}</td>
                            <td>{{$leavesAssign->leave_remain}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <h3>Leave Details</h3>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            @php
                                $leave=$employeeLeaveAssign->where('leave_structure_id',$leaveApplication->leave_structure_id)->first();
                                    @endphp
                            <input type="hidden" value="{{$leave->leave_remain}}" name="remain_leave" id="remain_leave">
                            <th>start from</th>
                            <td>{{$leaveApplication->req_start_date}}</td>
                            <th>End Leave</th>
                            <td>{{$leaveApplication->req_end_date}}</td>
                            <th>Leave for</th>
                            <td>{{$leaveApplication->req_for_date}}</td>
                        </tr>
                    <tr>
                        <th>Leave Reason</th>
                        <td>{{$leaveApplication->leave_reason}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-4">
                <h4>Approve leave Date <small class="text-success">@isset($weekend) @if(count($weekend)>0) (Include Holidays) @endif @endisset</small></h4>
                @foreach($dates as $leaveDates)
                    <input type="checkbox" class="@isset($leaveManagement[$leaveDates]) allLeaveDays @else leaveDays @endisset" name="leaveDays[]" @isset($leaveManagement[$leaveDates]) checked @endisset @isset($weekend) @foreach($weekend as $day) @if($day==$leaveDates) disabled @endif @endforeach @endisset value="{{$leaveDates}}"> {{$leaveDates}}
                @endforeach
            </div>
            <div class="col-md-2">
                <input type="checkbox" name="leave_status" @if($leaveApplication->status==3) checked @endif id="leave_status" value="3">
                <level  class="text-danger"><b>Reject</b></level>
            </div>
            <div class="col-md-6">
                <level>Remarks</level>
                <textarea id="" cols="100" rows="10" name="remarks" class="form-control">{{$leaveApplication->remarks}}</textarea>
            </div>
            <input type="hidden" name="approval_status" id="approval_status">
            <input type="hidden" name="applied_leave" id="applied_leave">
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-left">Submit</button>
        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>

    </div>
    </form>

    <script>
        $("form#leave_approval_form").on('submit',function (e){
            e.preventDefault();
            var totalLeaveDays=[];
                $.each($("input[name='leaveDays[]']"), function(){
                    totalLeaveDays.push($(this).val());
                })

            var holidayLeaveDays=[];
            $.each($("input[name='leaveDays[]']:disabled"), function(){
                holidayLeaveDays.push($(this).val());
            })

            var allLeaveDays=[];
            $.each($(".leaveDays"), function(){
                if ($(this).is(':checked')){
                    allLeaveDays.push($(this).val());
                }
            })
            var remainLeaveDays = totalLeaveDays.length - holidayLeaveDays.length - allLeaveDays.length;
            var appliedLeave = totalLeaveDays.length - holidayLeaveDays.length;


            var allLeaveDays=[];
            var allApprovedLeaveDays=[];
            var uncheckedApprovedLeaveDays=[];
            var leaveType= null;
            var remain_leave = $("#remain_leave").val();
            if($("#leave_status").is(':checked'))
            {
                leaveType=$("#leave_status").val();
            }

            if(leaveType==null)
            {
                $.each($(".leaveDays"), function(){
                    if ($(this).is(':checked')){
                        allLeaveDays.push($(this).val());
                    }
                })
                $.each($(".allLeaveDays"), function(){
                    if ($(this).is(':checked')){
                        allApprovedLeaveDays.push($(this).val());
                    }
                })
                $.each($(".allLeaveDays"), function(){
                    if (!$(this).is(':checked')){
                        uncheckedApprovedLeaveDays.push($(this).val());
                    }
                })
                if(allLeaveDays.length+allApprovedLeaveDays.length==0)
                {
                    alert('Please check any days for approved Leave');
                }
            }
            if(parseInt(remain_leave)+uncheckedApprovedLeaveDays.length < allLeaveDays.length)
            {
                alert("Sorry!!! Applicant reached the Assign Leave");
            }
            console.log(remain_leave)
            console.log(parseInt(remain_leave)+uncheckedApprovedLeaveDays.length)
            console.log(allLeaveDays.length)
            if(allLeaveDays.length+allApprovedLeaveDays.length>0 && (parseInt(remain_leave)+uncheckedApprovedLeaveDays.length>=allLeaveDays.length) || leaveType==3)
            {
                console.log("Dhukse");
                $("#approval_status").val(remainLeaveDays);
                $("#applied_leave").val(appliedLeave);
                $.ajax({
                    url:"{{url('/employee/applied/leave/application/store') }}",
                    type:'POST',
                    cache:false,
                    data: $("form#leave_approval_form").serialize(),
                    dataType:'application/json',
                    beforeSend:function ()
                    {
                        waitingDialog.show('Loading...');
                    },
                    success:function (data)
                    {
                        console.log(data);
                        waitingDialog.hide();
                    },
                    error:function (data)
                    {
                        console.log("Error");
                        waitingDialog.hide();
                        location.reload();

                    }
                })
            }
        })
    </script>
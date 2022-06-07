<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-bed"></i> Assign New Status</h4>
</div>
<div class="modal-body">
    @php
        $employee=$employeeProfile;

    @endphp

    <div class="p-2 " style="display: flex;justify-content: space-between">
        <div>

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
                 src="{{URL::asset('assets/users/images/user-non-teaching.png')}}" alt="No Image"
                 style="width:60px;height:auto">
        @endif
        </div>

        <div class=" font-bold " style="font-weight: bolder ;font-size: 1.3rem">
            <span>

            Name :{{$employeeProfile->first_name}} {{$employeeProfile->middle_name}} {{$employeeProfile->last_name}}
            </span> <br>

<span>
    Department:
            @if(!empty($employee->department()))
        {{$employee->department()->name}}
    @endif
</span><br>
            <span>
                  Designation: @if(!empty($employee->designation()))
                    {{$employee->designation()->name}}
                @endif
            </span> <br>
            <span>
                  Date of Joining:{{\Carbon\Carbon::parse($employee->doj)->format('d M Y')}}
            </span>



        </div>


    </div>
<div class="p-2 " style="padding: 10px ;margin :10px;border: 1px solid green">
    <h3 class="text-success text-center">Assign a New Status </h3>
    <form action="" id="assign_status_form" onsubmit="prev">
        <input type="hidden"  value="{{$employeeProfile->id}}">
        <table class="form-group table table-striped table-responsive">
            <tbody>

            <tr>
               <td>Active</td>
                @foreach($allStatus as  $status)
                    @if($status->category==1)

            <td>
                <label for="">   <input type="radio" value="{{$status->id}}" name="selected_status">
                   {{$status->status}}</label> </td>

            @endif
                @endforeach
            </tr>

            <tr>
                <td>Inactive</td>
                @foreach($allStatus as  $status)
                    @if($status->category==2)

                        <td>
                            <label for="">   <input type="radio" value="{{$status->id}}" name="selected_status">
                          {{$status->status}}</label> </td>

                    @endif
                @endforeach
            </tr>
            <tr>
                <td>Closed</td>
                @foreach($allStatus as  $status)
                    @if($status->category==3)

                        <td>
                            <label for="">    <input type="radio" value="{{$status->id}}" name="selected_status">
                          {{$status->status}}</label> </td>

                    @endif
                @endforeach
            </tr>
            </tbody>

        </table>
        <div class="form-group form-control">
            <label for="effective_date">Effective Date</label>

            <input type="text" id="effective_date" name="effective" >
        </div>
        <script>
            $( function() {
                $( "#effective_date" ).datepicker();
            } );
        </script>
        <button type="button" id="assign_button" class="btn-success">Assign Status</button>

    </form>
</div>
<div id="history-assign">


<div class="p-2 m-2" style="overflow: scroll">
    <table class="table-striped table table-bordered">
        <thead>
        <tr>
            <th>
                #
            </th>
            <th>
                Status Name
            </th>
            <th>
                Category
            </th>
            <th>
                Effective From
            </th>
            <th>
                Assigned On
            </th>
            <th>
                Assigned By
            </th>

        </tr>

        </thead>
            <tbody>
            @foreach($employeeProfile->employeeStatus as $status)
            <tr class="@if($status->status->category==1) bg-success @elseif($status->status->category==2) bg-warning
@elseif ($status->status->category==3) bg-danger @endif">
                <td>{{$loop->index+1}}</td>

                <td>{{$status->status->status}}</td>
                <td>@if($status->status->category==1) Active @elseif($status->status->category==2) Inactive  @elseif($status->status->category==3)
                       Closed
                    @endif</td>
                <td>{{\Carbon\Carbon::parse($status->effective_from)->format('d M Y') }}</td>
                <td>{{\Carbon\Carbon::parse($status->created_at)->format('d M Y') }}</td>


                <td>{{$status->assignedBy->name}}</td>
            </tr>
            @endforeach
            </tbody>
    </table>
</div>
</div>
</div>
<script>
    $("#assign_button").on('click',function (){
        var _token = '{{csrf_token()}}';
        var emp_id={{$employeeProfile->id}};
        var effective=$('#effective_date').val();
        var selected_status=$("input[name='selected_status']:checked").val();
        if(selected_status && effective ){
            $.ajax({
                url: '/employee/assign/save',
                type: 'POST',
                cache: false,
                data: {'emp_id': emp_id,'effective':effective,'selected_status':selected_status,'_token': _token}, //see the $_token
                datatype: 'application/json',

                beforeSend: function () {
                    waitingDialog.show('Saving');
                    // show waiting dialog
                    // waitingDialog.show('Loading...');
                },

                success: function (data) {
                    console.log(data);
                    $("#history-assign").html(data.html);
                    // hide waiting dialog
                    waitingDialog.hide();
                    // background
                },

                error: function () {
                    // hide waiting dialog
                    //waitingDialog.hide();
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        }else{
            swal("Error", "Fill  the required field ");
        }
        console.log(selected_status)

    })


</script>

<!-- DataTables -->
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<div class="box box-solid">
    <div class="et">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> View User List ({{$users->count()}})</h3>
        </div>
    </div>
    <div class="box-body">
        @if(!empty($users) AND $users->count()>0)
            <table id="example2" class="table table-striped table-bordered table-responsive text-center">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>User Id</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Category</th>
                    <th>Class/Department</th>
                    <th>Form/Designation</th>
                    <th>Role</th>
                    <th>Access Details</th>
                    <th>Password</th>
                    <th>Status</th>
                    <th>History</th>
                </tr>
                </thead>
                <tbody id="table">
                    @foreach($users as $user)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>
                                @if($role == 'student')
                                    @if($user->singelAttachment("PROFILE_PHOTO"))
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$user->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                                    @else
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
                                    @endif
                                @else
                                    @if($user->singelAttachment("PROFILE_PHOTO"))
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$user->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                                    @else
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
                                    @endif
                                @endif
                            </td>
                            <td>{{$user->first_name}} {{$user->last_name}}</td>
                            <td>{{$user->user()->username}}</td>
                            <td>@if($role == 'student') {{$user->student()->phone}} @else {{$user->phone}} @endif</td>
                            <td>{{$user->email}}</td>
                            <td>@if($role == 'student') Student @else Teaching @endif</td>
                            <td>@if($role == 'student') @if($user->batch()) {{$user->batch()->batch_name}} @endif @else @if($user->department()){{$user->department()->name}}@endif @endif</td>
                            <td>@if($role == 'student') @if($user->section()) {{$user->section()->section_name}} @endif @else @if($user->designation()) {{$user->designation()->name}} @endif @endif</td>
                            <td>{{$user->user()->role()->display_name}}</td>
                            <td>
                                <a href="{{url('/userrolemanagement/menu-accessibility/user/'.$user->user_id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Accessibility</a>
                            </td>
                            <td><a href="#">Password</a></td>
                            <td><a href="#">Status</a></td>
                            <td><a href="#">History</a></td>
                        </tr>
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
    jQuery(document).ready(function () {

        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true
        });

        // emp_web_sort
        //emp_web_sort();

        // emp_sort_order click action
        $("#emp_sort_order").click(function(){
            // checking
            if($(this).is(':checked')){
                // attendance looping
                $("#table input").each(function() {
                    // remove class
                    $(this).removeAttr('readonly');
                });
                // emp_web_sort
                //emp_web_sort();
            }else{
                // attendance looping
                $("#table input").each(function() {
                    // remove class
                    $(this).attr('readonly', 'readonly');
                });
                // emp_web_sort
                //emp_web_sort();
            }
        });

        $("#table input").keyup(function(){

            var emp_id = $(this).attr('id');
            var web_position = $(this).val();
            var _token = '{{csrf_token()}}';
            // change background color
            $(this).css("background-color", "pink");

            // checking
            if(web_position && emp_id && $.isNumeric(web_position)){
                // ajax request
                $.ajax({
                    url: '/employee/update/web-position',
                    type: 'POST',
                    cache: false,
                    data: {'emp_id': emp_id,'sort_order': web_position,'_token': _token }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        // waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        //waitingDialog.hide();
                        // background
                    },

                    error:function(){
                        // hide waiting dialog
                        //waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            }else{
                swal("Warning", 'Invalid input', "warning");
                $(this).val('');
            }

        });
    });
</script>
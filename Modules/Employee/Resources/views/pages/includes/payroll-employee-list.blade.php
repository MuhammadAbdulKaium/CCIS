<!-- DataTables -->
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<div class="box box-solid">
    <div class="et">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> View Employee List</h3>
        </div>
    </div>
    <div class="box-body">
        @if(!empty($allEmployee) AND $allEmployee->count()>0)
            <form id="salary_assign_form">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
            <table id="example2" class="table table-responsive text-center">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>User Id</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Salary category & Structure</th>
                    <th>Account</th>
                    <th>Salary</th>
{{--                    <th>Action</th>--}}
                </tr>
                </thead>
                <tbody id="table">
                @foreach($allEmployee as $index=>$employee)
                    @php
                        $salaryAssignDataSingle = $salaryAssignData->firstWhere('emp_id',$employee->id);
                    @endphp
                    <tr class="@if(isset($salaryAssign[$employee->id])) bg-success @endif">
                        <td>{{($index+1)}}
                        </td>
                        <td>
                            <input type="hidden" name="emp_id[]" value="{{$employee->id}}">
                            <a href="{{url('/employee/profile/personal/'.$employee->id)}}">{{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}}</a>
                        </td>
                        <td><a href="{{url('/employee/profile/personal/'.$employee->id)}}">{{$employee->email}}</a></td>
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
                        <td>
                            <select name="scale_id[]" id="" class="form-control">
                                @if(count($salaryScales)>0)
                                    @foreach($salaryScales as $scale)
                                        <option value="{{$scale->id}}" {{$scale->id==$scaleId?'selected':''}} @if(isset($salaryAssignDataSingle)) {{$scale->id==$salaryAssignDataSingle->salary_scale?'selected':''}} @endif>{{$scale->scale_name}}
                                            - {{$scale->gradeName->grade_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td>
                            @if(count($bankName)>0)
                                <select name="bankId[]" data-employee_id="{{$employee->id}}"
                                        class="form-control bank_name">

                                    @foreach($bankName as $bank)
                                        <option value="{{$bank->id}}" @if(isset($salaryAssignDataSingle)) {{$bank->id==$salaryAssignDataSingle->bank_details_id?'selected':''}} @endif @if(!isset($salaryAssignDataSingle)) {{$bank->id==$bank_id?'selected':''}} @endif>{{$bank->bank_name}} </option>
                                    @endforeach
                                </select>
                            @endif
                            @if(count($branchName)>0)
                                <select name="branchId[]" id="selected_branch_id_{{$employee->id}}"
                                        class="form-control branch_name">
                                    @foreach($branchName as $branch)
                                        <option value="{{$branch->id}}" {{$branch->id==$branch_id?'selected':''}} @if(isset($salaryAssignDataSingle)) {{$branch->id==$salaryAssignDataSingle->bank_branch_details_id?'selected':''}} @endif>{{$branch->branch_name}} </option>
                                    @endforeach
                                </select>
                            @endif
                            <input type="text" class="form-control" name="bank_acc_no[]" value="@if(isset($salaryAssignDataSingle)) {{$salaryAssignDataSingle->bank_acc_number}} @endif">
                        </td>
                        <td>
                            <input type="text" name="amount[]" value="@if(isset($salaryAssignDataSingle)) {{$salaryAssignDataSingle->salary_amount}} @else {{$amount}} @endif" class="form-control" >
                        </td>
{{--                        <td>--}}
{{--                            <a href="" class="btn btn-primary">Edit</a>--}}
{{--                        </td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Save</button>
            </form>
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
    function reset() {
        $('#addModal').find('input').each(function () {
            $(this).val(null)
        })
        $('#addModal').find('select').each(function () {
            $(this).val(null)
        })
    }

    function addDeatils(id) {

        $('#addModal').modal('show');
        $('#addModal').find('.modal-title').text('Add Salary Details')
        reset();
        var empId = id;
        $(".modal-body #empId").val(empId);
        $('.saveBtn').show()
        $('.updateBtn').hide()
    }

    function editDeatils(id) {
        $('#addModal').modal('show');
        $('#addModal').find('.modal-title').text('Update Salary Details')
        $('.saveBtn').hide()
        $('.updateBtn').show()
        $.ajax({
            type: "GET",
            url: "/payroll/find/assign/scale/details/" + id,

            success: function (data) {
                $('select[name="salaryGrade"]').val(data.salaryGrade)
                $('input[name="bankDetails"]').val(data.bank_details);
                console.log(data.bank_details)
            }
        })
    }

    function saleryDeatils(id) {
        $('#detailsModal').modal('show');
        $('#detailsModal').find('.modal-title').text('Salary Details')
        $.ajax({
            type: "GET",
            url: "/payroll/find/assign/scale/details/" + id,

            success: function (data) {
                $('select[name="salaryGrade"]').val(data.salaryGrade)
                $('input[name="bankDetails"]').val(data.bank_details);
                console.log(data.bank_details)
            }
        })
    }

    function getInputField() {
        var salaryGrade = $('select[name="salaryGrade"]').val();
        var empId = $('input[name="empId"]').val();
        var bankDetails = $('input[name="bankDetails"]').val();
        return {salaryGrade: salaryGrade, empId: empId, bankDetails: bankDetails}
    }

    function saveRecord() {
        // if(!confirm('Are you Sure?')) return;
        $.ajax({
            method: 'POST',
            url: '/payroll/salary/assign/add',
            data: {
                "data": getInputField(),
                "_token": "{{ csrf_token() }}"
            },
            dataType: 'JSON',
            beforeSend: function () {
                // show waiting dialog
                waitingDialog.show('Submitting...');
            },
            success: function () {
                waitingDialog.hide();
                console.log('Inserted')
                reset();
                $('#addModal').modal('hide');
                // getRecords()

            }
        })

    }

    jQuery(document).ready(function () {
        // request for section list using batch id
        jQuery(document).on('change', '.bank_name', function () {
            // get academic level id
            var bank_id = $(this).val();
            console.log(bank_id);
            var employee_id = $(this).data('employee_id');
            console.log(employee_id);
            var branch_id_name = '#selected_branch_id_' + employee_id;
            console.log(branch_id_name);
            // alert(bank_id);
            var op = "";
            $.ajax({
                url: '/payroll/bank/branch/search/' + bank_id,
                type: 'GET',

                success: function (data) {
                    console.log(data);
                    op += '<option value="">--- Select Branch ---</option>';
                    for (let i = 0; i < data.length; i++) {
                        op += '<option value="' + data[i].id + '">' + data[i].branch_name + '</option>';
                    }
                    // $("#employee_list_container").html("");
                    $(branch_id_name).html("");
                    $(branch_id_name).append(op);
                }
            })
        });


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

    });
</script>
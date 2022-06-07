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
            <form id="salary_generate_form">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <table id="example2" class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>User Id</th>
                        @if(count($salaryAdditionHead)>0)
                            @foreach($salaryAdditionHead as $head)
                                <th class="bg-success">
                                    {{$head->custom_name}}
                                </th>
                            @endforeach
                        @endif
                        <th>Gross Addition</th>
                        @if(count($salaryDeductionHead)>0)
                            @foreach($salaryDeductionHead as $head)
                                <th class="bg-danger">
                                    {{$head->custom_name}}
                                </th>
                            @endforeach
                        @endif
                        <th>Gross Deduction</th>
                        <th class="bg-primary">Net Pay</th>
                        <th>Status</th>
                        <th>Sheet Id</th>
                        <th>Voucher</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody id="table">
                    @foreach($allEmployee as $index=>$employee)
                        @php
                            $salaryAssignDataSingle = $salaryAssignData->firstWhere('emp_id',$employee->id);
                            if(isset($salaryStructures[$employee->salary_scale])){
                                $salaryAdditionDetails=$salaryStructures[$employee->salary_scale]->keyBy('salary_head_id');
                                $grossAddition=$salaryAdditionDetails->sum('amount');
                            }
                            else{
                                $salaryAdditionDetails=null;
                                $grossAddition=null;
                            }
                            if(isset($salaryDeductionRecords[$employee->id]))
                            {
                               $salaryDeduct=$salaryDeductionRecords[$employee->id]->keyBy('deduct_head_id');
                               $deductTotal=$salaryDeduct->sum('amount');
                            }
                            else{
                                $salaryDeduct=null;
                                $deductTotal=null;
                            }
                              $netPay=$grossAddition-$deductTotal;

                            if(isset($salaryGeneratedList[$employee->id]))
                            {
                                if($start_date && $end_date)
                                    {
                                      $salaryGenerate=$salaryGeneratedList[$employee->id]
                                        ->where('start_date',$start_date)->where('end_date',$end_date)->first();
                                    }
                                if(($start_date==null || $end_date==null))
                                    {
                                       $salaryGenerate=$salaryGeneratedList[$employee->id]
                                      ->where('month',$month_name)
                                       ->where('year',$year)
                                      ->whereNull('start_date')
                                      ->whereNull('end_date')
                                      ->first();
                                    }

                            }
                            else{
                                $salaryGenerate=null;
                            }

                        if($generateHistory){
                           if($start_date && $end_date)
                            {
                              $salaryGenerateHistory=$generateHistory
                                ->where('start_date',$start_date)->where('end_date',$end_date);
                            }
                        if(($start_date==null || $end_date==null))
                            {
                               $salaryGenerateHistory=$generateHistory
                               ->where('month',$month_name)->where('year',$year)->where('start_date','=',null)
                               ->where('end_date','=',null);
                            }
                        }
                        if($processHistory){
                           if($start_date && $end_date)
                            {
                              $salaryProcessHistory=$processHistory
                                ->where('start_date',$start_date)->where('end_date',$end_date);
                            }
                            if(($start_date==null || $end_date==null))
                                {
                                   $salaryProcessHistory=$processHistory
                                   ->where('month',$month_name)->where('year',$year)->where('start_date','=',null)
                                   ->where('end_date','=',null);
                                }
                        }

                        @endphp
                        <tr class="@if(isset($salaryGenerate)) bg-primary @endif">
                            <td>
                                <input type="checkbox"
                                       @if(isset($salaryGenerate)) @if($salaryGenerate->processed==1) disabled
                                       @endif @endif name="checkbox[]" value="{{$employee->id}}"
                                       class="deduct-checkbox">
                            </td>
                            <td>
                                <input type="hidden" name="emp_id[]" value="{{$employee->id}}">
                                {{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}}
                            </td>
                            <td>{{$employee->email}}</td>
                            @foreach($salaryAdditionHead as $head)
                                <td class="bg-success text-black">
                                    @isset($salaryAdditionDetails[$head->id])
                                        {{(int)($salaryAdditionDetails[$head->id]->amount/($salary_days+1))}}
                                        <input type="hidden"
                                               name="addition_head[{{$employee->id}}][{{$salaryAdditionDetails[$head->id]->salary_head_id}}]"
                                               value="{{(int)($salaryAdditionDetails[$head->id]->amount/($salary_days+1))}}">
                                    @endisset
                                </td>
                            @endforeach
                            <td> @isset($grossAddition){{(int)($grossAddition/($salary_days+1))}}@endisset</td>
                            @foreach($salaryDeductionHead as $head)
                                <td class="bg-danger text-black">
                                    @isset($salaryDeduct[$head->id])
                                        {{(int)($salaryDeduct[$head->id]->amount/($salary_days+1))}}
                                        <input type="hidden"
                                               name="deduct_head[{{$employee->id}}][{{$salaryDeduct[$head->id]->deduct_head_id}}]"
                                               value="{{(int)($salaryDeduct[$head->id]->amount/($salary_days+1))}}">
                                    @endisset
                                </td>
                            @endforeach
                            <td>{{(int)($deductTotal/($salary_days+1))}}</td>
                            <td class="bg-primary">{{(int)($netPay/($salary_days+1))}}
                                <input type="hidden" value="{{$netPay}}" name="net_pay[]">
                                <input type="hidden" value="{{$deductTotal}}" name="deductTotal[]">
                                <input type="hidden" value="@isset($grossAddition){{$grossAddition}}@endisset"
                                       name="additionTotal[]">
                                <input type="hidden" value="{{$month_name}}" name="month_name">
                                <input type="hidden" value="{{$year}}" name="year">
                                <input type="hidden" value="{{$employee->salary_scale}}" name="scale[]">
                                <input type="hidden" value="{{$start_date}}" name="start_date">
                                <input type="hidden" value="{{$end_date}}" name="end_date">
                                <input type="hidden" value="{{$salary_days}}" name="salary_days">
                            </td>
                            <td>
                                @if($salaryGenerate)
                                    <b>Generated
                                        @foreach($month_list as $key=>$month)
                                            @if($key==$salaryGenerate->month)
                                                (M-{{$month}}
                                            @endif
                                        @endforeach
                                        {{$salaryGenerate->year}} ) <br>
                                        {{$salaryGenerate->created_at}}
                                    </b>
                                @endif
                            </td>
                            <td>
                                @if(isset($salaryGenerate->sheet_id))
                                    @if($salaryGenerate->sheet_id!=null)
                                        <b>Sheet-id
                                            {{$salaryGenerate->sheet_id}} <br>
                                            {{$salaryGenerate->created_at}}
                                        </b>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="form-group">
                    <div class="col-md-3">
                        <input type="text" name="generate_narration" class="form-control"
                               placeholder="Write a Narration...">

                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary submit" data-btn-name="generate">Generate</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-2">
                        <input type="text" name="process_sheet_id" class="form-control"
                               placeholder="Write a Sheet ID...">

                    </div>
                    <div class="col-md-2">
                        <input type="text" name="remarks" class="form-control" placeholder="Remarks...">

                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-primary submit" data-btn-name="process">Process</button>
                    </div>
                </div>
            </form>
        @else
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-warning"></i> No result found. </h5>
            </div>
        @endif
        @if(count($salaryGenerateHistory))
            <div class="col-md-6">
                <table class="table">
                    <thead>
                    <th>#</th>
                    <th>Narration</th>
                    <th>Date</th>
                    </thead>
                    <tbody>
                    @foreach($salaryGenerateHistory as $history)
                        <tr>
                            <td>#</td>
                            <td>{{$history->narration}}</td>
                            <td>{{$history->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if(count($salaryProcessHistory))
            <div class="col-md-6">
                <table class="table">
                    <thead>
                    <th>#</th>
                    <th>Sheet Id</th>
                    <th>Date</th>
                    </thead>
                    <tbody>
                    @foreach($salaryProcessHistory as $history)
                        <tr>
                            <td>#</td>
                            <td>Sheet-{{$history->sheet_id}}</td>
                            <td>{{$history->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
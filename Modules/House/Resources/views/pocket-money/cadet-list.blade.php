<div class="row">
    <div class="col-sm-12">
        <div class="box box-solid">
            <div class="box-header">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-th-list"></i> Cadet List </h3>
                <div class="box-tools">
                    @if(in_array('house/pocket-money/edit-info',$pageAccessData))
                        <button class="btn btn-primary btn-sm" id="edit-modal-btn">Edit Info</button>
                    @endif
                    @if(in_array('house/pocket-money/add-balance',$pageAccessData))
                        <button class="btn btn-success btn-sm" id="balance-modal-btn">New Balance</button>
                    @endif
                    @if(in_array('house/pocket-money/allot-money',$pageAccessData))
                        <button class="btn btn-warning btn-sm" id="allotment-modal-btn">New Allotment</button>
                    @endif
                    @if(in_array('house/pocket-money/expense',$pageAccessData))
                        <button class="btn btn-danger btn-sm" id="expense-modal-btn">New Expense</button>
                    @endif
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th><input type="checkbox" id="check-all-std"></th>
							<th>Photo</th>
							<th>Cadet Info</th>
							<th>Academic Info</th>
							<th>House</th>
							<th>Account No</th>
							<th>Bank - Branch</th>
							<th>Account Balance</th>
							<th>Money In</th>
							<th>Current Blanace</th>
							<th>Previous Allotment</th>
							<th>Total Allotment</th>
							<th>Previous Expenses</th>
							<th>Previous Dues</th>
							<th>Previous Remaining</th>
							<th>Status</th>
							<th>History</th>
                        </tr>
                    </thead>
                    <tbody id="std-list-rows">
                        @foreach ($students as $student)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td><input type="checkbox" class="check-std" name="stdIds[{{ $student->std_id }}]" value="{{ $student->std_id }}"></td>
                                <td>
                                    @if($student->singelAttachment("PROFILE_PHOTO"))
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                                    @else
                                        <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
                                    @endif
                                </td>
                                <td>
                                    <a href="/student/profile/personal/{{$student->std_id}}" target="_blank">{{$student->singleStudent->nickname}} ({{$student->singleUser->username}})</a>
                                </td>
                                <td>
                                    <div>
                                        <b>Batch:</b> {{$student->singleStudent->batch_no}}, 
                                    </div>
                                    <div>
                                        @if($student->singleBatch) {{$student->singleBatch->batch_name}} - @endif
                                        @if($student->singleSection) {{$student->singleSection->section_name}} @endif
                                    </div>
                                    <div>
                                        <b>AcY: </b>@if($student->year()) {{$student->year()->year_name}} @endif, <b>AdY: </b>@if($student->enroll()->admissionYear) {{$student->enroll()->admissionYear->year_name}} @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($student->roomStudent)
                                    @isset($houses[$student->roomStudent->house_id])
                                            {{ $houses[$student->roomStudent->house_id]->name }}
                                        @endisset
                                    @endif
                                </td>
                                @isset($pocketMoneyRows[$student->std_id])
                                    <td>{{ $pocketMoneyRows[$student->std_id]->account_no }}</td>
                                    <td>
                                        @if ($pocketMoneyRows[$student->std_id]->bankBranch)    
                                            @if ($pocketMoneyRows[$student->std_id]->bankBranch->bankName)
                                                {{ $pocketMoneyRows[$student->std_id]->bankBranch->bankName->bank_name }} - 
                                                {{ $pocketMoneyRows[$student->std_id]->bankBranch->branch_name }}
                                            @endif   
                                        @endif
                                    </td>
                                    <td>{{ $pocketMoneyRows[$student->std_id]->account_balance }}</td>
                                    <td>{{ $pocketMoneyRows[$student->std_id]->money_in }}</td>
                                    <td>{{ $pocketMoneyRows[$student->std_id]->account_balance + $pocketMoneyRows[$student->std_id]->money_in }}</td>
                                    <td>{{ $pocketMoneyRows[$student->std_id]->last_allotment }}</td>
                                    <td>{{ $pocketMoneyRows[$student->std_id]->total_allotment }}</td>
                                    <td>{{ $pocketMoneyRows[$student->std_id]->total_expense }}</td>
                                    <td>
                                        @if ($pocketMoneyRows[$student->std_id]->total_allotment < $pocketMoneyRows[$student->std_id]->total_expense)
                                            {{ $pocketMoneyRows[$student->std_id]->total_expense - $pocketMoneyRows[$student->std_id]->total_allotment }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pocketMoneyRows[$student->std_id]->total_allotment >= $pocketMoneyRows[$student->std_id]->total_expense)
                                            {{ $pocketMoneyRows[$student->std_id]->total_allotment - $pocketMoneyRows[$student->std_id]->total_expense }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pocketMoneyRows[$student->std_id]->status == 1)
                                            Active
                                        @elseif($pocketMoneyRows[$student->std_id]->status == 0)
                                            Inactive
                                        @endif
                                    </td>
                                    <td>
                                        @if(in_array('house/pocket-money.histories',$pageAccessData))
                                            <a class="btn btn-info btn-xs"
                                                href="{{ url('/house/pocket-money/histories/'.$pocketMoneyRows[$student->std_id]->id) }}"
                                                data-target="#globalModal" data-toggle="modal"><i
                                                class="fa fa-history"></i></a>
                                        @endif
                                    </td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endisset
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Edit Info Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="editModalLongTitle"><b>Edit selected students account info.</b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="">Account No</label>
                        <input type="text" class="form-control" id="select-account-no">
                    </div>
                    <div class="col-sm-5">
                        <label for="">Bank - Branch</label>
                        <select name="bank_branch_id" class="form-control" id="select-bank-branch">
                            <option value="">--Select Bank - Branch--</option>
                            @foreach ($banks as $bank)
                                @foreach ($bank->branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $bank->bank_name }} - {{ $branch->branch_name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Status</label>
                        <select name="" class="form-control" id="select-status">                            
                            <option value="">--Select--</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="update-cadet-info-btn">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- New Balance Modal -->
<div class="modal fade" id="balanceModal" tabindex="-1" role="dialog" aria-labelledby="balanceModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="balanceModalLongTitle"><b>Add balance to selected students.</b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="">Account Balance</label>
                        <input type="number" class="form-control" id="select-account-balance">
                    </div>
                    <div class="col-sm-4">
                        <label for="">Money In</label>
                        <input type="number" class="form-control" id="select-money-in">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="add-new-balance-btn">Add</button>
            </div>
        </div>
    </div>
</div>
<!-- New Allotment Modal -->
<div class="modal fade" id="allotmentModal" tabindex="-1" role="dialog" aria-labelledby="allotmentModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="allotmentModalLongTitle"><b>Allot money to selected students.</b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Amount</label>
                        <input type="number" class="form-control" id="select-allotment-amount">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="allot-money-btn">Allot</button>
            </div>
        </div>
    </div>
</div>
<!-- New Expense Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="expenseModalLongTitle"><b>Add expense to selected students.</b></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Amount</label>
                        <input type="number" class="form-control" id="select-expense-amount">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="expense-money-btn">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        $('#select-bank-branch').select2();

        var searchFieldsData = $('form#cadet-search-form').serialize();

        function getStdIdsFromCheckbox() {
            var stdIds = [];
            $('#std-list-rows input:checked').each(function() {
                stdIds.push($(this).val());
            });
            return stdIds;
        }

        function childFieldsBlank(parent) {
            parent.find('input').val("");
            parent.find('select').val("");
        }

        function searchCadets(data, successCallback, errorCallback) {
            // Ajax Request Start
            $.ajax({
                url: "{{ url('/house/pocket-money/search-cadets') }}",
                type: 'GET',
                cache: false,
                data: data, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    
                },
            
                success: function (res) {
                    successCallback(res);
                },
            
                error: function (error) {
                    errorCallback(error);
                }
            });
            // Ajax Request End
        }

        $('#check-all-std').click(function () {
            if($(this).is(':checked')){
                $('.check-std').prop('checked', true);
            }else{
                $('.check-std').prop('checked', false);
            }
        });

        $('#edit-modal-btn').click(function(){
            var selectedStdIds = getStdIdsFromCheckbox();
            if (selectedStdIds.length>0) {
                $('#editModal').modal('show');
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Please select at least one cadet first',
                });
            }
        });
        $('#balance-modal-btn').click(function(){
            var selectedStdIds = getStdIdsFromCheckbox();
            if (selectedStdIds.length>0) {
                $('#balanceModal').modal('show');
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Please select at least one cadet first',
                });
            }
        });
        $('#allotment-modal-btn').click(function(){
            var selectedStdIds = getStdIdsFromCheckbox();
            if (selectedStdIds.length>0) {
                $('#allotmentModal').modal('show');
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Please select at least one cadet first',
                });
            }
        });
        $('#expense-modal-btn').click(function(){
            var selectedStdIds = getStdIdsFromCheckbox();
            if (selectedStdIds.length>0) {
                $('#expenseModal').modal('show');
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Please select at least one cadet first',
                });
            }
        });

        $('#update-cadet-info-btn').click(function () {
            var thisModal = $('#editModal');
            var stdIds = getStdIdsFromCheckbox();
            var accountNo = $('#select-account-no').val();
            var bankBranch = $('#select-bank-branch').val();
            var status = $('#select-status').val();
            $_token = "{{ csrf_token() }}";
            
            var data = {
                _token: $_token,
                account_no: accountNo,
                bank_branch_id: bankBranch,
                status: status,
                std_ids: stdIds,
            }

            // Ajax Request Start
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/pocket-money/edit-info') }}",
                type: 'GET',
                cache: false,
                data: data, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    console.log(data);

                    Toast.fire({
                        icon: (data.status==1)?'success':'error',
                        title: data.msg
                    });

                    if (data.status==1) {
                        thisModal.modal('hide');
                        childFieldsBlank(thisModal);
                        searchCadets(searchFieldsData, (res)=>{
                            // hide waiting dialog
                            waitingDialog.hide();
                            $('#std_list_container').html(res);
                        }, (error)=>{
                            Toast.fire({
                                icon: 'error',
                                title: error
                            });
                        });
                    }else{
                        // hide waiting dialog
                        waitingDialog.hide();
                    }
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                }
            });
            // Ajax Request End
        });

        $('#add-new-balance-btn').click(function () {
            var thisModal = $('#balanceModal');
            var stdIds = getStdIdsFromCheckbox();
            var accountBalance = $('#select-account-balance').val();
            var moneyIn = $('#select-money-in').val();
            $_token = "{{ csrf_token() }}";
            
            var data = {
                _token: $_token,
                account_balance: accountBalance,
                money_in: moneyIn,
                std_ids: stdIds,
            }

            // Ajax Request Start
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/pocket-money/add-balance') }}",
                type: 'GET',
                cache: false,
                data: data, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    console.log(data);

                    Toast.fire({
                        icon: (data.status==1)?'success':'error',
                        title: data.msg
                    });

                    if (data.status==1) {
                        thisModal.modal('hide');
                        childFieldsBlank(thisModal);
                        searchCadets(searchFieldsData, (res)=>{
                            // hide waiting dialog
                            waitingDialog.hide();
                            $('#std_list_container').html(res);
                        }, (error)=>{
                            Toast.fire({
                                icon: 'error',
                                title: error
                            });
                        });
                    }else{
                        // hide waiting dialog
                        waitingDialog.hide();
                    }
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                }
            });
            // Ajax Request End
        });

        $('#allot-money-btn').click(function () {
            var thisModal = $('#allotmentModal');
            var stdIds = getStdIdsFromCheckbox();
            var allotmentAmount = $('#select-allotment-amount').val();
            $_token = "{{ csrf_token() }}";
            
            var data = {
                _token: $_token,
                allotment_amount: allotmentAmount,
                std_ids: stdIds,
            }

            // Ajax Request Start
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/pocket-money/allot-money') }}",
                type: 'GET',
                cache: false,
                data: data, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    console.log(data);

                    Toast.fire({
                        icon: (data.status==1)?'success':'error',
                        title: data.msg
                    });

                    if (data.status==1) {
                        thisModal.modal('hide');
                        childFieldsBlank(thisModal);
                        searchCadets(searchFieldsData, (res)=>{
                            // hide waiting dialog
                            waitingDialog.hide();
                            $('#std_list_container').html(res);
                        }, (error)=>{
                            Toast.fire({
                                icon: 'error',
                                title: error
                            });
                        });
                    }else{
                        // hide waiting dialog
                        waitingDialog.hide();
                    }
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                }
            });
            // Ajax Request End
        });

        $('#expense-money-btn').click(function () {
            var thisModal = $('#expenseModal');
            var stdIds = getStdIdsFromCheckbox();
            var expenseAmount = $('#select-expense-amount').val();
            $_token = "{{ csrf_token() }}";
            
            var data = {
                _token: $_token,
                expense_amount: expenseAmount,
                std_ids: stdIds,
            }

            // Ajax Request Start
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/pocket-money/expense') }}",
                type: 'GET',
                cache: false,
                data: data, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    console.log(data);

                    Toast.fire({
                        icon: (data.status==1)?'success':'error',
                        title: data.msg
                    });

                    if (data.status==1) {
                        thisModal.modal('hide');
                        childFieldsBlank(thisModal);
                        searchCadets(searchFieldsData, (res)=>{
                            // hide waiting dialog
                            waitingDialog.hide();
                            $('#std_list_container').html(res);
                        }, (error)=>{
                            Toast.fire({
                                icon: 'error',
                                title: error
                            });
                        });
                    }else{
                        // hide waiting dialog
                        waitingDialog.hide();
                    }
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);
                }
            });
            // Ajax Request End
        });
    });
</script>
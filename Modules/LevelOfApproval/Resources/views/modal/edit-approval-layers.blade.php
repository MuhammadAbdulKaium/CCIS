<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-edit"></i> Update {{ $levelOfApproval->menu_name }} approval layers</h4>
</div>
<div class="modal-body">
    <form action="{{url('/levelofapproval/update/approval-layers/'.$levelOfApproval->id)}}" method="POST">
        @csrf

        <input type="hidden" name="unique_name" value="{{ $levelOfApproval->unique_name }}">

        <div class="row">
            <div class="col-md-6">
                <h5>Number of Layers: 
                    @if ($levelOfApproval->approvalLayers->count()>0)
                        <b id="total-num-of-layers">{{ $levelOfApproval->approvalLayers->count() }}</b>
                    @else
                        <b id="total-num-of-layers">1</b>
                    @endif
                </h5>
            </div>
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Step</th>
                            <th>Role</th>
                            <th>Users</th>
                            <th>All User Mandatory</th>
                            @if ($levelOfApproval->unique_name == 'purchase_order')
                                <th>PO Value</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (sizeof($approvalLayers)>0)
                            @foreach ($approvalLayers as $approvalLayer)
                                <tr>
                                    <th>
                                        <span class="step-no">{{ $approvalLayer->layer }}</span>
                                        <input type="hidden" name="step[{{ $approvalLayer->layer }}]" class="hidden-step-no" value="{{ $approvalLayer->layer }}">
                                    </th>
                                    <td>
                                        <select name="roleId[{{ $approvalLayer->layer }}]" class="form-control select-role">
                                            <option value="">--All Roles--</option>
                                            @php
                                                $selectedRole = null;
                                            @endphp
                                            @foreach ($roles as $role)
                                                @php
                                                    if ($role->id == $approvalLayer->role_id) {
                                                        $selectedRole = $role;
                                                        $selected = "selected";
                                                    }else{
                                                        $selected = null;
                                                    }
                                                @endphp
                                                <option value="{{ $role->id }}" {{ $selected }}>
                                                    {{ $role->display_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="userIds[{{ $approvalLayer->layer }}][]" class="form-control select-users" multiple>
                                            @php
                                                $savedUsers = ($approvalLayer->user_ids)?json_decode($approvalLayer->user_ids, 1):[];
                                                $filteredUsers = ($selectedRole)?$selectedRole->roleUsers:$users;
                                            @endphp
                                            @foreach ($filteredUsers as $user)
                                                @isset($users[$user->id])
                                                    @php
                                                        $selected = (in_array($user->id, $savedUsers))?'selected':null;
                                                    @endphp
                                                    <option value="{{ $user->id }}" {{ $selected }}>
                                                        {{ $user->name }}
                                                        ({{ $user->username }})
                                                    </option>
                                                @endisset
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="checkbox" name="allMandatory[{{ $approvalLayer->layer }}]" class="select-all-mandatory" value="yes" @if ($approvalLayer->all_members == 'yes') checked @endif></td>
                                    @if ($levelOfApproval->unique_name == 'purchase_order')
                                        <td><input type="number" name="poValue[{{ $approvalLayer->layer }}]" id="" class="form-control select-po-value" @if ($approvalLayer->po_value) value="{{ $approvalLayer->po_value }}" @else placeholder="PO Value" @endif></td>
                                    @endif
                                    <td><button type="button" class="btn btn-danger remove-layer-btn"><i class="fa fa-minus"></i></button></td>
                                </tr>
                            @endforeach
                        @else
                            {!! $approvalLayerRow !!}
                        @endif
                        <tr>
                            @if ($levelOfApproval->unique_name == 'purchase_order')
                                <td colspan="5"></td>
                            @else
                                <td colspan="4"></td>
                            @endif
                            <td><button type="button" class="btn btn-primary" id="add-layer-btn"><i class="fa fa-plus"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-sm-12">
                <button class="btn btn-success" style="float: right">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('.select-users').select2({
            placeholder: "--All Users--"
        });

        var approvalLayerRow = {!! json_encode($approvalLayerRow) !!};
        var numberOfLayers = {!! json_encode($levelOfApproval->approvalLayers->count()) !!};
        numberOfLayers = (numberOfLayers>0)?numberOfLayers:1;

        function reSerializeLayerSteps() {
            var steps = $('.step-no');
            var parent = null;

            steps.each((i, step) => {
                parent = $(step).parent().parent();
                $(step).text(++i);
                parent.find('.hidden-step-no').attr('name', 'step['+i+']');
                parent.find('.hidden-step-no').val(i);
                parent.find('.select-role').attr('name', 'roleId['+i+']');
                parent.find('.select-users').attr('name', 'userIds['+i+'][]');
                parent.find('.select-all-mandatory').attr('name', 'allMandatory['+i+']');
                parent.find('.select-po-value').attr('name', 'poValue['+i+']');
            });
        }

        $('#add-layer-btn').click(function () {
            var tr = $(this).parent().parent();
            tr.before(approvalLayerRow);
            $('.select-users').select2({
                placeholder: "--All Users--"
            });
            $('#total-num-of-layers').text(++numberOfLayers);
            reSerializeLayerSteps();
        });

        $(document).on('click', '.remove-layer-btn', function () {
            var tr = $(this).parent().parent();
            tr.remove();
            $('#total-num-of-layers').text(--numberOfLayers);
            reSerializeLayerSteps();
        });

        $(document).on('change', '.select-role', function () {
            var tr = $(this).parent().parent();
            var usersField = tr.find('.select-users');

            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/levelofapproval/get/persons/from/role') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'roleId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    // hide waiting dialog
                    waitingDialog.hide();

                    var txt = '';

                    data.forEach(user => {
                        txt += '<option value="'+user.id+'">'+user.name+' ('+user.username+')</option>';
                    });

                    usersField.html(txt);                    
                    usersField.select2({
                        placeholder: "--All Users--"
                    });
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
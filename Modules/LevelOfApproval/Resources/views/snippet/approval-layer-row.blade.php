<tr>
    <th>
        <span class="step-no">1</span>
        <input type="hidden" name="step[1]" class="hidden-step-no" value="1">
    </th>
    <td>
        <select name="roleId[1]" class="form-control select-role">
            <option value="">--All Roles--</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <select name="userIds[1][]" class="form-control select-users" multiple>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">
                    {{ $user->name }}
                    ({{ $user->username }})
                </option>
            @endforeach
        </select>
    </td>
    <td><input type="checkbox" name="allMandatory[1]" class="select-all-mandatory" value="yes"></td>
    @if ($levelOfApproval->unique_name == 'purchase_order')
        <td><input type="number" name="poValue[1]" id="" class="form-control select-po-value" placeholder="PO Value"></td>
    @endif
    <td><button type="button" class="btn btn-danger remove-layer-btn"><i class="fa fa-minus"></i></button></td>
</tr>
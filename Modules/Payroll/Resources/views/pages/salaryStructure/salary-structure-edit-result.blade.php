
<div class="box box-solid">
    <h3>Generated Head</h3>
    <form id="salary_structure_edit_form">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
    <table class="table edit-table">
        <thead>
        <th>Head Name</th>
        <th>Amount</th>
        <th>Addition/Deduction</th>
        <th>Placement</th>
        <th>Fixed/variable</th>
        <th>Calculation</th>
        <th>Min Amount</th>
        <th>Max Amount</th>
        <th>Remarks</th>
        </thead>
        <tbody>
            @foreach($salaryStructure as $head)
                    <tr class="structureRow">
                        <td>{{$head->headName->custom_name}}
                            <input type="hidden" name="head_id[]" value="{{$head->salary_head_id}}" class='head_id'>
                            <input type="hidden" name="structure_id" value="{{$id}}">
                        </td>
                        <td><input type="number" class="form-control" name="amount[]" value="{{$head->amount}}" required></td>
                        <td><input type="text" class="form-control" value="{{$head->type==0?'Addition':'Deduction'}}" readonly></td>
                        <td><input type="text" class="form-control" value="{{$head->placement==1?'Gross':'Extra'}}" readonly></td>
                        <td><input type="text" class="form-control" value="{{$head->fixed_type==1?'Fixed':'Variable'}}" readonly></td>
                        <td><input type="text" class="form-control" value="{{$head->calculation==0?'Automatic':'Manual'}}" readonly></td>
                        <td><input type="number" class="form-control" name="maximum_amt[]" required value="{{$head->min_amount}}"></td>
                        <td><input type="number" class="form-control" name="minimum_amt[]" required value="{{$head->max_amount}}"></td>
                        <td><input type="text" class="form-control" name="remarks[]" value="{{$head->remarks}}"></td>
                    </tr>
            @endforeach
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

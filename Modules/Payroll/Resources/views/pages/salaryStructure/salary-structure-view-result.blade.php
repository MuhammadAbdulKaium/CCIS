
<div class="box box-solid">
    <h3>Generated Head</h3>
    <form id="salary_structure_assign_form">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
    <table class="table">
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

        @foreach($salaryHeads as $head)
            @foreach($checkedHead as $checkedId)
                @if($checkedId==$head->id)
                    <tr>
                        <td>{{$head->custom_name}}
                            <input type="hidden" name="head_id[]" value="{{$head->id}}">
                            <input type="hidden" name="scale_id" value="{{$checkedScale}}">
                        </td>
                        <td><input type="number" class="form-control" name="amount[]" required></td>
                        <td><input type="text" class="form-control" value="{{$head->type==0?'Addition':'Deduction'}}" readonly></td>
                        <td><input type="text" class="form-control" value="{{$head->placement==1?'Gross':'Extra'}}" readonly></td>
                        <td><input type="text" class="form-control" value="{{$head->fixed_type==1?'Fixed':'Variable'}}" readonly></td>
                        <td><input type="text" class="form-control" value="{{$head->calculation==0?'Automatic':'Manual'}}" readonly></td>
                        <td><input type="number" class="form-control" name="maximum_amt[]" required></td>
                        <td><input type="number" class="form-control" name="minimum_amt[]" required></td>
                        <td><input type="text" class="form-control" name="remarks[]"></td>
                    </tr>
                @endif
            @endforeach
        @endforeach
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

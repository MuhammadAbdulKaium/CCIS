<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Salary Structure History </h3>
</div>
<div class="modal-body">
    <div class="table">
        <table class="table table-striped">
            <thead>
            <th>Head Name</th>
            <th>Amount</th>
            <th>Max Amount</th>
            <th>Min Amount</th>
            <th>Assign Date</th>
            <th>Assign By</th>
            </thead>
            <tbody>
            @foreach($SalaryStructuresHistory as $history)
                <tr>
                    <td>{{$history->headName->custom_name}}</td>
                    <td>{{$history->amount}}</td>
                    <td>{{$history->max_amount}}</td>
                    <td>{{$history->min_amount}}</td>
                    <td>{{$history->created_at}}</td>
                    <td>{{$history->userName->name}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>

</div>

<style>
    .pad-right{
        padding-right: 20px;
    }
</style>

<script type="text/javascript">

</script>

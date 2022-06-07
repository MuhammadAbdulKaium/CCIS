<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Transaction History
    </h4>
</div>
<!--modal-header-->
<div class="modal-body">
    <table class="table table-bordered" id="transaction-history-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</td>
                    <td>{{ $transaction->total }}</td>
                    <td>{{ $transaction->payment_for }}</td>
                    <td>{{ $transaction->carry_forwarded_due }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function () {
        $('#transaction-history-table').DataTable();
    });
</script>

<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Menu History
    </h4>
</div>
<!--modal-header-->
<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered" id="history-table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Effective Date</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($histories as $history)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{ Carbon\Carbon::parse($history['dateTime'])->format('d/m/Y') }}</td>
                            <td>{{ $history['cost'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
       $('#history-table').DataTable(); 
    });
</script>

<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Score History of {{ $student->first_name }} {{ $student->last_name }}
    </h4>
</div>
<!--modal-header-->
<div class="modal-body">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Academic Year</th>
                <th>Term</th>
                <th>Date</th>
                <th>Category</th>
                <th>Score</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recordScores as $recordScore)
                <tr>
                    <td>{{ $recordScore->academicYear->year_name }}</td>
                    <td>{{ $recordScore->term->name }}</td>
                    <td>{{ $recordScore->date }}</td>
                    <td>
                        @if ($recordScore->category_id == 0)
                            Overall
                        @else
                            {{ $recordScore->category->performance_type }}</td>
                        @endif
                    <td>{{ $recordScore->score }}</td>
                    <td>{{ $recordScore->remarks }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#date').datepicker();
    });
</script>

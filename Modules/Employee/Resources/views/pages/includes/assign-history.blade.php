

<div class="p-2 m-2" style="overflow: scroll">
    <table class="table-striped table table-bordered">
        <thead>
        <tr>
            <th>
                #
            </th>
            <th>
                Status Name
            </th>
            <th>
                Category
            </th>
            <th>
                Effective From
            </th>
            <th>
                Assigned On
            </th>
            <th>
                Assigned By
            </th>

        </tr>

        </thead>
        <tbody>
        @foreach($employeeProfile->employeeStatus as $status)
            <tr class="@if($status->status->category==1) bg-success @elseif($status->status->category==2) bg-warning
@elseif ($status->status->category==3) bg-danger @endif">
                <td>{{$loop->index+1}}</td>

                <td>{{$status->status->status}}</td>
                <td>@if($status->status->category==1) Active @elseif($status->status->category==2) Inactive  @elseif($status->status->category==3)
                        Closed
                    @endif</td>
                <td>{{\Carbon\Carbon::parse($status->effective_from)->format('d M Y') }}</td>
                <td>{{\Carbon\Carbon::parse($status->created_at)->format('d M Y') }}</td>


                <td>{{$status->assignedBy->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
<!--    {{$employeeProfile->employeeStatus}}-->
</div>


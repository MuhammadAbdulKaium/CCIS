@foreach ($transactions as $transaction)
<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">Number</th>
            <th scope="col">Identity</th>
            <th scope="col">Photo</th>
            <th scope="col">Item Requested</th>
            <th scope="col">Category</th>
            <th scope="col">Req. Qty</th>
            <th scope="col">Price-Rate</th>
            <th scope="col">Amount</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody class="selected-items-table">
        @php
            $purchaseDetails = json_decode($transaction->purchase_details, 1);
            $size = sizeof($purchaseDetails);

            if ($transaction->customer_type == 1) {
                $student = $students->firstWhere('std_id', $transaction->customer_id);
            } elseif ($transaction->customer_type == 2) {
                $employee = $employees->firstWhere('id', $transaction->customer_id);
            }
        @endphp
        @foreach ($purchaseDetails as $item)
            @php
                $category = $menuCategories->firstWhere('id', $item['categoryId']);
            @endphp
            <tr>
                @if ($loop->index == 0)
                    <td rowspan="{{ $size }}">ID: {{ $transaction->id }} <br> {{ Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</td>
                    @if ($transaction->customer_type == 1)
                        <td rowspan="{{ $size }}">ID: {{ $student->std_id }} <br> Name: Cadet {{ $student->first_name }} <br> {{ $student->singleBatch->batch_name }} | Form {{ $student->singleSection->section_name }}</td>
                        <td rowspan="{{ $size }}">
                            @if($student->singelAttachment("PROFILE_PHOTO"))
                                <img src="{{URL::asset('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px">
                            @else
                                <img src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px">
                            @endif
                        </td>
                    @elseif ($transaction->customer_type == 2)
                        <td rowspan="{{ $size }}">ID: {{ $employee->id }} <br> Name: {{ $employee->first_name }} {{ $employee->last_name }} <br> Designation: {{ $employee->empDesig($employee->id) }}</td>
                        <td rowspan="{{ $size }}>
                            @if ($employee->singelAttachment("PROFILE_PHOTO"))
                            <img style="width: 50px"
                                src="{{URL::asset('assets/users/images/'.$employee->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"
                                alt="">
                            @else
                            <img style="width: 50px"
                                src="{{URL::asset('assets/users/images/user-default.png')}}" alt="">
                            @endif
                        </td>
                    @endif
                    
                @endif
                <td>{{ $item['name'] }}</td>
                <td>{{ $category->category_name }}</td>
                <td>{{ $item['qty'] }}</td>
                <td>{{ $item['price'] }}</td>
                <td>{{ $item['qty'] * $item['price'] }}</td>
                <td><span class="text-info">Done</span></td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" style="text-align: right">Total: </td>
            <td>{{ $transaction->total }}</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right">Previous Dues: </td>
            <td>{{ $transaction->previous_dues }}</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right">Total Dues: </td>
            <td>{{ $transaction->total + $transaction->previous_dues }}</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right">Amount Given: </td>
            <td>{{ $transaction->amount_given }}</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right">Payment For: </td>
            <td>{{ $transaction->payment_for }}</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right">Change to customer: </td>
            <td>{{ $transaction->amount_given - $transaction->payment_for }}</td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: right">Carry-Forwarded Dues: </td>
            <td>{{ ($transaction->total + $transaction->previous_dues) - $transaction->payment_for }}</td>
        </tr>
    </tfoot>
</table>
@endforeach


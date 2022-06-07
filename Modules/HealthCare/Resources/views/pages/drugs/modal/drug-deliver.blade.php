<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title text-bold">
        <i class="fa fa-info-circle"></i> Deliver drug to "
        @if ($drugReport->patient_type == 1)
            {{ $drugReport->cadet->first_name }} {{ $drugReport->cadet->last_name }} ({{ $drugReport->cadet->singleUser->username }})
        @elseif ($drugReport->patient_type == 2)
            {{ $drugReport->employee->first_name }} {{ $drugReport->employee->last_name }} ({{ $drugReport->employee->singleUser->username }})
        @endif "
    </h4>
</div>
<!--modal-header-->
<div class="modal-body">
    <form id="deliver-medicine-form" method="POST" action="{{ url('/healthcare/drug/deliver/'.$drugReport->id) }}">
        @csrf

        <div class="row">
            <div class="col-sm-12">
                <ul>
                    <li><b>Prescription ID</b>: {{ $drugReport->pr_barcode }}</li>
                    <li><b>Medicine Name</b>: {{ $drugReport->drug->product_name }}</li>
                    @if ($drugReport->status != 2)
                    <li><b>Store</b>: 
                        <select name="storeId" class="inventory-store" required>
                            <option value="">--Selcet Store--</option>
                            @foreach ($inventoryStores as $inventoryStore)
                                <option value="{{ $inventoryStore->id }}">{{ $inventoryStore->store_name }}</option>
                            @endforeach
                        </select>
                    </li>
                    @endif
                    <li><b>Store Qty</b>: <span class="store-qty">0</span></li>
                    <li><b>Required Qty</b>: <span class="required-qty">{{ $drugReport->required_quantity }}</span></li>
                    <li><b>Previous Disburse</b>: <span class="previous-disburse-qty">{{ $drugReport->disbursed_quantity }}</span></li>
                    @if ($drugReport->status != 2)
                        <li><b>New Issue</b>: <input type="number" name="newIssue" class="new-issue-qty" min="1" max="{{ $drugReport->required_quantity - $drugReport->disbursed_quantity }}" value="{{ $drugReport->required_quantity - $drugReport->disbursed_quantity }}" required></li>
                    @endif
                    <li><b>Remaining</b>: <span class="remaining-qty">0</span></li>
                </ul>
            </div>
        </div>

        <button class="deliver-submit-btn" type="submit" style="display: none"></button>
    </form>
    <div class="row">
        <div class="col-sm-12">
            @if ($drugReport->status == 2)
                <div style="float: right; font-weight: bold" class="text-success">Drugs successfully delivered!</div>
            @else
                <button class="btn btn-success deliver-drugs-btn" style="float: right">Deliver</button>
            @endif
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        var productId = {!! $drugReport->product_id !!};

        var drugPaths = {
            storeQty: $('.store-qty'),
            requiredQty: $('.required-qty'),
            previousDisbursedQty: $('.new-issue-qty'),
            newIssuedQty: $('.new-issue-qty'),
            remainingQty: $('.remaining-qty'),
        }

        var drugValues = {
            storeQty: 0,
            requiredQty: {!! $drugReport->required_quantity !!},
            previousDisbursedQty: {!! $drugReport->disbursed_quantity !!},
            newIssuedQty: {!! $drugReport->required_quantity - $drugReport->disbursed_quantity !!},
            remainingQty: 0,
            pricePerItem: 0,
        }

        function setDrugQuantities(){
            drugPaths.storeQty.text(drugValues.storeQty);
            drugPaths.requiredQty.text(drugValues.requiredQty);
            drugPaths.previousDisbursedQty.text(drugValues.previousDisbursedQty);
            drugPaths.newIssuedQty.val(drugValues.newIssuedQty);
            drugPaths.remainingQty.text(drugValues.remainingQty);
        };

        $('.deliver-drugs-btn').click(function () {
            console.log(drugValues);
            if (drugValues.storeQty >= drugValues.newIssuedQty) {
                $('.deliver-submit-btn').click();
            } else {
                swal("Error!", "Only "+drugValues.storeQty+" pcs are available at the store!", "error");
            }   
        });

        $('.new-issue-qty').change(function () {
            drugValues.newIssuedQty = parseInt($(this).val());
            drugValues.remainingQty = (drugValues.requiredQty - drugValues.previousDisbursedQty) - drugValues.newIssuedQty;
            setDrugQuantities();
        });

        $('.inventory-store').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/healthcare/drug/get/stock/from/store') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'storeId': $(this).val(),
                    'productId': productId,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    drugValues.storeQty = data.current_stock;
                    drugValues.pricePerItem = data.avg_cost_price;
                    setDrugQuantities();
                }
            });
            // Ajax Request End
        });
    });
</script>
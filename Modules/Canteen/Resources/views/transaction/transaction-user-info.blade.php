@if ($type == 1)
<div class="col-sm-7">
    <ul>
        <li><b>ID:</b> <span id="customer-id">{{ $person->std_id }}</span></li>
        <li><b>Name:</b> <span id="customer-name">{{ $person->first_name }} {{ $person->last_name }}</span></li>
        <li><b>Description:</b> {{ $person->singleBatch->batch_name }} Form {{ $person->singleSection->section_name }}</li>
        <li><b>Previous Dues:</b> {{ ($lastTrans)?$lastTrans->carry_forwarded_due:'0' }}</li>
        <li><a class="btn btn-xs btn-info" href="{{ url('/canteen/transaction/history/'.$type.'/'.$person->std_id) }}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">History</a>
        </li>
    </ul>
</div>
<div class="col-sm-5">
    @if($person->singelAttachment("PROFILE_PHOTO"))
        <img src="{{URL::asset('assets/users/images/'.$person->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="max-width: 100%">
    @else
        <img src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px">
    @endif
</div>
@elseif ($type == 2)
<div class="col-sm-7">
    <ul>
        <li><b>ID:</b> <span id="customer-id">{{ $person->id }}</span></li>
        <li><b>Name:</b> <span id="customer-name">{{ $person->first_name }} {{ $person->last_name }}</span></li>
        <li><b>Designation:</b> {{ $person->empDesig($person->id) }}</li>
        <li><b>Previous Dues:</b> {{ ($lastTrans)?$lastTrans->carry_forwarded_due:'0' }}</li>
        <li><a class="btn btn-xs btn-info" href="{{ url('/canteen/transaction/history/'.$type.'/'.$person->id) }}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">History</a>
        </li>
    </ul>
</div>
<div class="col-sm-5">
    <img src="{{ asset('default-images/user.png') }}" alt="" >
    @if ($person->singelAttachment("PROFILE_PHOTO"))
    <img style="max-width: 100%"
        src="{{URL::asset('assets/users/images/'.$person->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"
        alt="">
    @else
    <img style="max-width: 100%"
        src="{{URL::asset('assets/users/images/user-default.png')}}" alt="">
    @endif
</div>
@endif

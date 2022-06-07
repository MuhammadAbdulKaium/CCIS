@extends('layouts.master')

@section('styles')
<style>
    .select2-selection--single {
        height: 33px !important;
    }

    table td{
        text-align: center;
        vertical-align: middle !important;
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Record Score |<small>House</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">House</a></li>
            <li>SOP Setup</li>
            <li class="active">Record Score</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
        <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
        <p class="alert alert-warning alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
        <p class="alert alert-danger alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Search Record Score
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="row" style="margin-bottom: 20px">
                            <form action="{{ url('/house/record-score/with-house/') }}" method="post">
                                @csrf

                                <div class="col-sm-3">
                                    <select name="houseId" id="" class="form-control select-house">
                                        <option value="">--House--</option>
                                        @foreach ($houses as $house)
                                        <option value="{{$house->id}}"
                                            {{ ($selectedHouse)?($selectedHouse->id == $house->id)?'selected':'':'' }}>
                                            {{ $house->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-success">Select</button>
                                </div>
                            </form>
                            <div class="col-sm-7">
                                @if ($selectedHouse)
                                    @if(in_array('house/record-score.create', $pageAccessData))
                                <a class="btn btn-primary"
                                    href="{{ url('/house/create/record-score/'.$selectedHouse->id) }}"
                                    style="float: right" data-target="#globalModal" data-toggle="modal"
                                    data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Add Scores</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @if ($selectedHouse)
                        <div class="row">
                            <div class="col-sm-1">
                                <select name="yearId" class="form-control search-academic-year-field" required>
                                    <option value="">Year*</option>
                                    @foreach ($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}">{{ $academicYear->year_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="mode" class="form-control search-term-field" required>
                                    <option value="">Term*</option>
                                </select> 
                            </div>
                            <div class="col-sm-2">
                                <select name="batchId" class="form-control search-batch-field" required>
                                    <option value="">--Class--</option>
                                    @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->batch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="sectionId" class="form-control search-section-field" required>
                                    <option value="">--Form--</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="studentId" class="form-control" id="select-student" required>
                                    <option value="">--Cadet--</option>
                                    @foreach ($students as $student)
                                    <option value="{{ $student->std_id }}">Id:{{ $student->singleUser->username }} -
                                        {{ $student->first_name }} {{ $student->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <button class="btn btn-success search-table-btn">Search</button>
                            </div>
                        </div>
                        @endif

                        <div class="row" style="margin-top: 20px">
                            <div class="col-sm-12 table-holder">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="loader">
                            <div class="es-spinner">
                                <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('.alert-auto-hide').fadeTo(7500, 500, function () {
            $(this).slideUp('slow', function () {
                $(this).remove();
            });
        });
        $('#select-student').select2();


        $('.search-academic-year-field').change(function () {
           // Ajax Request Start
           $_token = "{{ csrf_token() }}";
           $.ajax({
               headers: {
                   'X-CSRF-Token': $('meta[name=_token]').attr('content')
               },
               url: "{{ url('/house/get/term/from/year') }}",
               type: 'GET',
               cache: false,
               data: {
                   '_token': $_token,
                   'yearId': $(this).val(),
               }, //see the _token
               datatype: 'application/json',
           
               beforeSend: function () {},
           
               success: function (data) {
                   var txt = '<option value="">--Term--</option>';

                   data.forEach(element => {
                       txt += '<option value="'+element.id+'">'+element.name+'</option>';
                   });

                   $('.search-term-field').html(txt);
               }
           });
           // Ajax Request End 
        });

        $('.search-batch-field').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/get/sections/from/batch') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'batchId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Form--</option>';

                   data.forEach(element => {
                       txt += '<option value="'+element.id+'">'+element.section_name+'</option>';
                   });

                   $('.search-section-field').html(txt);
                }
            });
            // Ajax Request End
        });

        var house = {!! json_encode($selectedHouse) !!};

        $('.search-table-btn').click(function () {
            var houseId = house.id;
            var yearId = $('.search-academic-year-field').val();
            var termId = $('.search-term-field').val();
            var batchId = $('.search-batch-field').val();
            var sectionId = $('.search-section-field').val();
            var studentId = $('#select-student').val();

            if (houseId && yearId && termId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/house/search/record-scores') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'houseId': houseId,
                        'yearId': yearId,
                        'termId': termId,
                        'batchId': batchId,
                        'sectionId': sectionId,
                        'studentId': studentId,
                    }, //see the _token
                    datatype: 'application/json',

                    beforeSend: function () {},

                    success: function (data) {
                        console.log(data);
                        $('.table-holder').empty();
                        $('.table-holder').append(data);
                    }
                });
                // Ajax Request End
            } else {
                swal('Error', 'Please Fill Up the required fields first!', 'error');
            }
        });
    });
</script>
@stop
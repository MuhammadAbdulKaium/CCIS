@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Report |<small>Cadet Remarks</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/">Cadets</a></li>
            <li>Reports</li>
            <li class="active">Cadet Remarks</li>
        </ul>
    </section>
    
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Search Cadet & give remarks </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-2">
                        <label class="control-label" for="academic_level">Academic Level</label>
                        <select id="academic_level" class="form-control academicLevel" name="academic_level">
                            <option value="">--- Select Level* ---</option>
                            @foreach($academicLevels as $level)
                                <option value="{{$level->id}}">{{$level->level_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label" for="batch">Class</label>
                        <select id="batch" class="form-control academicBatch" name="batch">
                            <option value="" selected>--- Select Class* ---</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label" for="section">Form</label>
                        <select id="section" class="form-control academicSection" name="section">
                            <option value="" selected>--- Select Form ---</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="">Academic Year</label>
                        <select name="yearId" class="form-control search-academic-year-field" required>
                            <option value="">--- All ---</option>
                            @foreach ($academicYears as $academicYear)
                            <option value="{{ $academicYear->id }}">{{ $academicYear->year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="">Term</label>
                        <select name="termId" class="form-control search-term-field" required>
                            <option value="">--- All ---</option>
                        </select> 
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-success student-search-btn" style="margin-top: 23px">Search</button>
                    </div>
                </div>

                <div class="row" style="margin-top: 30px">
                    <div class="col-sm-12 student-list-holder">

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal"  id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        // request for batch list using level id
        jQuery(document).on('change','.academicLevel',function(){
            // console.log("hmm its change");

            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/batch') }}",
                type: 'GET',
                cache: false,
                data: {'id': level_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // clear std list container
                    $('#std_list_container_row').html('');
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="" selected>--- Select Class* ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="0" selected>--- Select Form ---</option>');
                },

                error:function(){
                    alert(JSON.stringify(data));
                }
            });
        });

        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            console.log("hmm its change");

            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // clear std list container
                    $('#std_list_container_row').html('');
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="" selected>--- Select Form ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                },
            });
        });

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
                   var txt = '<option value="">--- All ---</option>';

                   data.forEach(element => {
                       txt += '<option value="'+element.id+'">'+element.name+'</option>';
                   });

                   $('.search-term-field').html(txt);
                   $('.search-exam-field').html('<option value="">--- Select Exam ---</option>');
               }
           });
           // Ajax Request End 
        });

        var batchId = null;
        var sectionId = null;
        var yearId = null;
        var termId = null;

        $('.student-search-btn').click(function () {
            batchId = $('.academicBatch').val();
            sectionId = $('.academicSection').val();
            yearId = $('.search-academic-year-field').val();
            termId = $('.search-term-field').val();

            if (batchId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/student/search/students/for/remarks') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'batchId': batchId,
                        'sectionId': sectionId,
                        'yearId': yearId,
                        'termId': termId,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        $('.student-list-holder').html(data);
                    }
                });
                // Ajax Request End
            }else{
                swal('Error!', "Choose a class first!", 'error');
            }
        });
    });
</script>
@stop

@extends('reports::layouts.report-layout')
<!-- page content -->
@section('page-content')
    <form id="std_id_card_form">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="">
            {{--<div class="box-header with-border">--}}
            {{--<h3 class="box-title">--}}
            {{--<i class = "fa fa-filter" aria-hidden="true"></i> Filter Options--}}
            {{--</h3>--}}
            {{--</div>--}}
            <div class="">
                <div class="row">
                    <div class="col-sm-2 col-md-offset-1">
                        <div class="form-group {{ $errors->has('academic_year') ? ' has-error' : '' }}">
                            <label class="control-label" for="academic_year">Academic Year</label>
                            <select id="academic_year" class="form-control academicYear academicChange" name="academic_year" required>
                                <option value="" selected disabled>--- Select Year ---</option>
                                @foreach($academicYears as $academicYear)
                                    <option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
                                @endforeach
                            </select>
                            <div class="help-block">
                                @if ($errors->has('academic_year'))
                                    <strong>{{ $errors->first('academic_year') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group {{ $errors->has('academic_level') ? ' has-error' : '' }}">
                            <label class="control-label" for="academic_level">Academic Level</label>
                            <select id="academic_level" class="form-control academicLevel academicChange" name="academic_level" required>
                                <option value="" selected disabled>--- Select Level ---</option>
                            </select>
                            <div class="help-block">
                                @if ($errors->has('academic_level'))
                                    <strong>{{ $errors->first('academic_level') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group {{ $errors->has('batch') ? ' has-error' : '' }}">
                            <label class="control-label" for="batch">Class</label>
                            <select id="batch" class="form-control academicBatch academicChange" name="batch" required>
                                <option value="" selected disabled>--- Select Class ---</option>
                            </select>
                            <div class="help-block">
                                @if ($errors->has('batch'))
                                    <strong>{{ $errors->first('batch') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group {{ $errors->has('section') ? ' has-error' : '' }}">
                            <label class="control-label" for="section">Section</label>
                            <select id="section" class="form-control academicSection academicChange" name="section" required>
                                <option value="" selected disabled>--- Select Section ---</option>
                            </select>
                            <div class="help-block">
                                @if ($errors->has('batch'))
                                    <strong>{{ $errors->first('section') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group {{ $errors->has('semester') ? ' has-error' : '' }}">
                            <label class="control-label" for="semester">Semester</label>
                            <select id="semester" class="form-control academicSemester academicChange" name="semester" required>
                                <option value="" selected disabled>--- Select Section ---</option>
                            </select>
                            <div class="help-block">
                                @if ($errors->has('semester'))
                                    <strong>{{ $errors->first('semester') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--./box-body-->
        </div><!--./box-body-->
        <div class="box-footer text-right">
            <button type="submit" class="btn btn-info">Submit</button>
            <button type="reset" class="pull-left btn btn-default">Reset</button>
        </div>
    </form>
    {{--manage-enquiry-content-row--}}
    <div id="applicant_reports_container_row">

    </div>
@endsection

@section('page-new-script')
    <script>
        $(document).ready(function () {

            $('form#std_id_card_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '/reports/admit-card/',
                    data: $('form#std_id_card_form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        // show waiting dialog
                        // waitingDialog.show('Loading...');
                    },

                    success: function (data) {
//                        alert(JSON.stringify(data));

                        // hide waiting dialog
                        // waitingDialog.hide();
                        // statements
                        var applicant_reports_container_row=  $('#applicant_reports_container_row');
                        applicant_reports_container_row.html('');
                        applicant_reports_container_row.append(data);
                    },

                    error:function(data){
                        alert(JSON.stringify(data));
                    }
                });
            });



            jQuery(document).on('change','.academicChange',function(){
                $('#applicant_reports_container_row').html('');
            });

            //birth_date picker
            $('#applicant_dob').datepicker({
                autoclose: true
            });

            jQuery(document).ready(function () {
                jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                    $(this).slideUp('slow', function () {
                        $(this).remove();
                    });
                });
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicYear',function(){
                $.ajax({
                    url: "{{ url('/academics/find/level') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statement
                    },

                    success:function(data){
                        //console.log(data.length);
                        var op ='<option value="0" selected disabled>--- Select Level ---</option>';
                        for(var i=0; i < data.length; i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                        }

                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append('<option value="" selected disabled>--- Select Batch ---</option>');

                        // set value to the academic batch
                        $('.academicLevel').html("");
                        $('.academicLevel').append(op);
                        // reset semester
                        resetSemester();
                    },

                    error:function(){
                        // statement
                    }
                });
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicLevel',function(){
                // ajax request
                $.ajax({
                    url: "{{ url('/academics/find/batch') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statement
                    },

                    success:function(data){
                        var op='<option value="" selected disabled>--- Select Class ---</option>';
                        for(var i=0; i < data.length; i++){
                            op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                        }

                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append(op);

                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
                        // reset semester
                        resetSemester();
                    },

                    error:function(){
                        // statement
                    }
                });


                // request for section list using batch id
                jQuery(document).on('change','.academicBatch',function(){
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
                            //
                        },

                        success:function(data){
                            op+='<option value="" selected disabled>--- Select Section ---</option>';
                            for(var i=0; i < data.length; i++){
                                op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                            }
                            // refresh attendance container row
                            $('#attendanceContainer').html('');
                            // set value to the academic batch
                            $('.academicSection').html("");
                            $('.academicSection').append(op);

                            // set value to the academic secton
                            $('.academicSubject').html("");
                            $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');
                            // set value to the academic secton
                            $('.academicSession option:first').prop('selected',true);
                            // reset semester
                            resetSemester();
                        },
                        error:function(){
                            //
                        },
                    });
                });

            });

            // reset semester list
            function  resetSemester() {
                // get academic batch id
                var batch_id = $("#batch").val();
                // get academic level id
                var level_id = $("#academic_level").val();
                // select option
                var op="";
                // checking
                if(batch_id && level_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/find/batch/semester",
                        type: 'GET',
                        cache: false,
                        data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){
                            op+='<option value="" selected disabled>--- Select Semester ---</option>';
                            for(var i=0;i<data.length;i++){
                                op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                            }
                            // set value to the academic semester
                            $('.academicSemester').html('');
                            $('.academicSemester').append(op);
                        },
                        error:function(){
                            // statements
                        },
                    });
                }else{
                    op+='<option value="" selected disabled>--- Select Semester ---</option>';
                    // set value to the academic semester
                    $('.academicSemester').html('');
                    $('.academicSemester').append(op);
                }
            }
        });
    </script>
@endsection

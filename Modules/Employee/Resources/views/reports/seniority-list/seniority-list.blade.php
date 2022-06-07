@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Report |<small>HR Register</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Human Resource</a></li>
            <li>Reports</li>
            <li class="active">HR Register Report</li>
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
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> HR Register Report</h3>
            </div>
            <form id="search-register-table-form" method="POST" action="{{ url('/employee/seniority/list/report/submit') }}" target="_blank">
                @csrf

                <input type="hidden" class="select-type" name="type" value="search">
            
                <div class="box-body">
                    <div class="row">
                        @if(sizeof($institutes)>1)
                        <div class=" col-sm-2">


                                <label for="">Institute</label>
                            <select name="institute_id[]" id="institute" class="form-control" multiple>
                                @foreach ($institutes as $institute)
                                    <option value="{{ $institute->id }}">{{ $institute->institute_alias }}</option>
                                @endforeach
                            </select>




                        </div>
                        @endif
                        <div class="col-sm-3">
                            <label for="">Department</label>
                            <select name="department_id[]" id="department" class="form-control" multiple>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="">Designation</label>
                            <select name="designation_id[]" id="designation" class="form-control" multiple>
                                @foreach ($designations as $designation)
                                    <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="col-sm-1" style="display: flex;justify-content: center;align-items: center">
                                <div class="form-check " style="margin-top: 25px">
                                    <input class="form-check-input" type="checkbox" name="hide_blank"
                                           id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Hide Blank
                                    </label>
                                </div>
                            </div>
                        <div class="col-sm-4">
                            <label for="">Fields</label>
                            <select name="fields[]" id="fields" class="form-control" multiple>



                                <option value="full_name" selected> Name</option>
                                <option value="employee_no" selected>Employee No</option>
                                <option value="position_serial">Position Serial</option>
                                <option value="central_position_serial" selected>Central Position Serial</option>
                                <option value="alias">Alias</option>
                                <option value="gender">Gender</option>
                                <option value="dob" selected>Date of Birth</option>
                                <option value="doj" selected>Date of Join</option>
                                <option value="dor" selected>Date of Retirement</option>
                                <option value="department">Department</option>
                                <option value="designation" selected>Designation</option>

                                <option value="email">Email</option>
                                <option value="phone">Phone</option>
                                <option value="alt_mobile">Alt Mobile</option>

                                <option value="blood_group">Blood Group</option>
                                <option value="religion">Religion</option>
                                <option value="marital_status">Marital Status</option>
                                <option value="nationality">Nationality</option>
                                <option value="experience_year">Experience Year</option>
                                <option value="experience_month">Experience Month</option>
                                <option value="present_address" selected>Present Address</option>
                                <option value="permanent_address" selected>Permanent Address</option>
                                <!-- New Parameter  -->
                                <option value="promotions" selected>Promotions</option>
                                <option value="disciplines" selected>Discipline</option>

                                <option value="qualifications" selected>Qualifications</option>
                                <option value="trainings" selected>Trainings</option>
                                <option value="family" selected>Family</option>
                                <option value="transfers" selected>Transfers</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="report_name"> Report Name</label>
                            <input  class="form-control " type="text" value="Employee Seniority Report by designation"
                                    name="report_name">
                        </div>

                            <input type="hidden" id="pdf_page_size" value="A3-L" name="pdf_page_size">
                    </div>
                </div>
                <div class="box-footer with-border">
                    <div class="row">
                        <div class="col-sm-8"></div>
                        <div class="col-sm-4" style="text-align: right">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                               Custom page size
                            </button>
                            <button type="button" class="btn btn-success search-btn"><i class="fa fa-search"></i></button>
                            <button type="button" class="btn btn-primary print-btn"><i class="fa fa-print"></i></button>
                            <button class="print-submit-btn" style="display: none"></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="hr-register-table-holder table-responsive">
                        
        </div>
    </section>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Customize Page size for Printing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-3 form-group " >
                    <label class="" for="page_size">Page Size</label>
                    <select class="form-control" name="page_size" id="page_size">
                        <option value="A1-L">A1-L</option>
                        <option value="A2-L">A2-L</option>
                        <option selected value="A3-L">A3-L</option>
                        <option value="A4-L">A4-L</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save_size" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- Scripts --}}
@section('scripts')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#institute').select2({
            placeholder: "--All--",
        });
        $('#department').select2({
            placeholder: "--All Department--",
        });
        $('#designation').select2({
            placeholder: "--All Designation--",
        });
        $('#fields').select2({
            placeholder: "--All Fields--",
        });

        $('.search-btn').click(function () {
            $('.select-type').val('search');

            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/employee/seniority/list/report/submit') }}",
                type: 'POST',
                cache: false,
                data: $('form#search-register-table-form').serialize(),
                datatype: 'application/json',
            
                beforeSend: function () {
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    waitingDialog.hide();
                    console.log(data);

                    $('.hr-register-table-holder').html(data);
                },

                error:function(data){
                    waitingDialog.hide();
                    alert(JSON.stringify(data));
                }
            });
        });

        $('.print-btn').click(function () {
            $('.select-type').val('print');
            $('.print-submit-btn').click();
        });
        $('#save_size').on('click',function (){
            const pageSize= $('#page_size').val();
            $('#exampleModal').modal('hide');
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: pageSize+ " is now selected Printing page size ",
                showConfirmButton: false,
                timer: 1500
            })
            $('#pdf_page_size').val(pageSize);
        })
    });
</script>
@stop
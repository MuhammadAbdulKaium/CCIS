<style>
    .show_img {
        width: 50px;
        height: auto;
    }

    .cursor_pointer {
        cursor: pointer;
    }

    .hidde {
        display: none;
    }

</style>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Update Contribution Board Result
    </h4>
</div>
<form action="{{ url('/employee/profile/update/contribution-board-result/'.$cbr->id) }}" method="post"
    enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{ $cbr->employee_id }}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="exam_years"> Exam Years <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                </label>
                <input type="text" required value="{{ $cbr->exam_years }}" class="form-control" name="exam_years"
                    placeholder="YYYY" id="datepicker" />
            </div>
            <div class="col-sm-4">
                <label for="exam_name">Exam Name <sup style="color: red; font-size:18px; top:-1px;">*</sup></label>
                <input type="text" name="exam_name" required value="{{ $cbr->exam_name }}" id="exam_name"
                    class="form-control">
            </div>
            <div class="col-sm-4">
                <label for="total_cadet">Number of Cadet <sup
                        style="color: red; font-size:18px; top:-1px;">*</sup></label>
                <input type="number" required value="{{ $cbr->total_cadet }}" name="total_cadet" id="total_cadet"
                    class="form-control">

            </div>
        </div>
        <div class="row" style="margin-bottom:15px;">
            <div class="col-sm-6">
                <label for="gpa_type">Select Assign Subject or Without Subject <sup
                        style="color: red; font-size:18px; top:-1px;">*</sup></label>
                <select name="gpa_type" id="gpa_type" class="form-control">
                    <option {{ $cbr->gpa_type == 0 ? 'selected' : ' ' }} value="0">without Subject</option>
                    <option {{ $cbr->gpa_type == 1 ? 'selected' : ' ' }} value="1">Assign Subject</option>
                </select>
            </div>
            <div class="col-sm-6">

                <label for="">Number of GPA-5<sup style="color: red; font-size:18px; top:-1px;">*</sup></label>

                <div class="row">
                    @php
                        $total_gpa = json_decode($cbr->total_gpa);
                    @endphp
                    <div class="col-sm-12 without_Subject">
                        <input type="number" value="{{ $total_gpa[0]->gpa }}" name="without_gpa" id="without_gpa"
                            class="form-control" placeholder="Without Subject GPA-5 ">
                    </div>
                    <div class="col-sm-12 subject hidde">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Total GPA-5</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="subject-table-row-holder">
                                @if ($cbr->gpa_type == 1)

                                    @foreach ($total_gpa as $gpa)
                                        <tr>
                                            <td>
                                                <select name="gpa_subject[]" id="gpa_subject"
                                                    class="form-control subject">
                                                    <option value="">__select Subject__</option>
                                                    @foreach ($allSubjects as $subject)
                                                        <option {{ $gpa->subject == $subject->id ? 'selected' : ' ' }}
                                                            value="{{ $subject->id }}">{{ $subject->subject_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" value="{{ $gpa->gpa }}" name="gpa[]"
                                                    id="gpa_number" class="form-control">
                                            </td>
                                            <td style="width:70px">
                                                <span class="text-danger subject-table-remove-row-btn cursor_pointer"><i
                                                        class="fa fa-minus"></i></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td><span class="subject-table-add-row-btn text-success cursor_pointer"><i
                                                class="fa fa-plus"></i></span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

        </div>


        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <label for="remarks">Remarks</label>
                <textarea name="remarks" class="form-control" id="remarks" cols="30" rows="1">{{ $cbr->remarks }}</textarea>
            </div>
            <div class="col-sm-6">
                <label for="attachment">Attachment <sub style="color: red; font-size:10px; bottom:-1px;">max-size:
                    200kb</sub></label>
                <input type="file" name="attachment" id="attachment" class="form-control" accept=".png,.jpeg,.jpg,.pdf">
            </div>

        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right">Update</button>
    </div>
</form>
<table style="display: none">
    <tbody id="subject-table-row">
        <tr>
            <td>
                <select name="gpa_subject[]" id="gpa_subject" class="form-control subject">
                    <option value="">__select Subject__</option>
                    @foreach ($allSubjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="gpa[]" id="gpa_number" class="form-control">
            </td>

            <td style="width:70px">
                <span class="text-danger subject-table-remove-row-btn cursor_pointer"><i
                        class="fa fa-minus"></i></span>
            </td>
        </tr>
    </tbody>
</table>
<table style="display: none">
    <tbody id="withoutsubject-table-row">
        <tr>
            <td>
                <select name="notGPA_subject[]" id="gpa_subject" class="form-control subject">
                    <option value="">__select Subject__</option>
                    @foreach ($allSubjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="not_gpa[]" id="gpa_number" class="form-control">
            </td>

            <td style="width:70px">
                <span class="text-danger withoutsubject-table-remove-row-btn cursor_pointer"><i
                        class="fa fa-minus"></i></span>
            </td>
        </tr>
    </tbody>
</table>

<script type="text/javascript">
    $("#datepicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true //to close picker once year is selected
    });
    // $('#gpa_subject').select2();
    // $('#io_name').select2();
    // $('#initiative_officer').select2();
    // $('#higher_officer').select2();

    $('.subject-table-add-row-btn').click(function() {
        var html = $('#subject-table-row').html();
        $('#subject-table-row-holder').append(html);

    });
    $(document).on('click', '.subject-table-remove-row-btn', function() {
        $(this).parent().parent().remove();
    });
    $('.withoutsubject-table-add-row-btn').click(function() {
        var html = $('#withoutsubject-table-row').html();
        $('#withoutsubject-table-row-holder').append(html);
    });
    $(document).on('click', '.withoutsubject-table-remove-row-btn', function() {
        $(this).parent().parent().remove();
    });

    $(document).on('change', '#gpa_type', function(e) {
        var value = $(this).val();
        if (value == 0) {
            $('.without_Subject').removeClass('hidde');

            $('.subject').addClass('hidde');
            // 
            $("#gpa_subject").empty();
            $("#gpa_number").empty();
        }
        if (value == 1) {
            $('.subject').removeClass('hidde');
            $('.without_Subject').addClass('hidde');
            $("#without_gpa").empty();
        }
    });
    $(document).ready(function() {
        var $gpa_type = $("#gpa_type").val();
        if ($gpa_type == 0) {
            $('.without_Subject').removeClass('hidde');

            $('.subject').addClass('hidde');
            // 
            $("#gpa_subject").empty();
            $("#gpa_number").empty();
        }
        if ($gpa_type == 1) {
            $('.subject').removeClass('hidde');
            $('.without_Subject').addClass('hidde');
            $("#without_gpa").empty();
        }
    })
</script>

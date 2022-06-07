<style>
    .show_img {
        width: 50px;
        height: auto;
    }

    .cursor_pointer {
        cursor: pointer;
    }

</style>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Update Publication
    </h4>
</div>
<form action="{{ url('/employee/profile/update/publication/' . $employeePublication->id) }}" method="post"
    enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{ $employeePublication->employee_id }}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <label for="publication_title">Publication Title <sup
                        style="color: red; font-size:18px; top:-1px;">*</sup>
                </label>
                <input name="publication_title" value="{{ $employeePublication->publication_title }}"
                    id="publication_title" required class="form-control" />
            </div>
            <div class="col-sm-6">
                <label for="publication_description">Publication Description</label>
                <textarea name="publication_description" id="publication_description" class="form-control" cols="30"
                    rows="1">{{ $employeePublication->publication_description }}</textarea>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Editions <sup style="color: red; font-size:18px; top:-1px;">*</sup></th>
                            <th>Publication Date <sup style="color: red; font-size:18px; top:-1px;">*</sup></th>
                            <th>Attachment <sub style="color: red;">max-size: 600kb</sub> </th>
                            <th>Remarks</th>
                            <th>Add/Remove</th>
                        </tr>
                    </thead>
                    <tbody id="publication-table-row-holder">
                        @foreach ($employeePublication->publicationEditions as $edition)
                            <tr>
                                <td>
                                    <input type="text" required class="form-control" value="{{ $edition->editions }}"
                                        name="editions[{{ $edition->id }}]">
                                </td>
                                <td>
                                    <input type="date" required name="date[{{ $edition->id }}]" value="{{ $edition->date }}"
                                        class="form-control" id="date">
                                </td>
                                <td>
                                    <input class="form-control attachment" type="file"
                                        name="attachment[{{ $edition->id }}]" accept=".png,.jpeg,.jpg,.pdf,.doc,.docx">
                                  

                                </td>
                                <td>
                                    <input class="form-control" type="text" value="{{ $edition->remarks }}"
                                        name="remarks[{{ $edition->id }}]" id="remarks" />
                                </td>
                                <td style="width:70px">
                                    <span class="text-danger publication-table-remove-row-btn cursor_pointer"><i
                                            class="fa fa-minus"></i></span>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                    <tbody id="publication-table-row-holder">


                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td><span class="publication-table-add-row-btn text-success cursor_pointer"><i
                                        class="fa fa-plus"></i></span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right">Update</button>
    </div>
</form>
<table style="display: none">
    <tbody id="publication-table-row">
        <tr>
            <td>
                <input type="text" required class="form-control" name="editions[]">
            </td>
            <td>
                <input type="date" required name="date[]" class="form-control" id="date">
            </td>
            <td>
                <input class="form-control attachment" type="file" name="attachment[]" accept=".png,.jpeg,.jpg,.pdf,.doc,.docx">
            </td>
            <td>
                <input class="form-control" type="text" name="remarks[]" id="remarks" />
            </td>
            <td style="width:70px">
                <span class="text-danger publication-table-remove-row-btn cursor_pointer"><i
                        class="fa fa-minus"></i></span>
            </td>
        </tr>
    </tbody>
</table>
<script type="text/javascript">
    $('#punishment_by_select').select2();
    $('.publication-table-add-row-btn').click(function() {
        var html = $('#publication-table-row').html();
        $('#publication-table-row-holder').append(html);
    });
    $(document).on('click', '.publication-table-remove-row-btn', function() {
        $(this).parent().parent().remove();
    });

    $(document).on('change', '.attachment', function(e) {
        var parent = $(this).parent().parent();
        var show_img = parent.find('.show_img');
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();

            reader.onload = function() {
                show_img.attr("src", reader.result);
            }

            reader.readAsDataURL(file);
            show_img.removeClass('hidden');
        }
    });
</script>

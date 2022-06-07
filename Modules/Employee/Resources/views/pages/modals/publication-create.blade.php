<style>
    .show_img{
        width: 50px;
        height: auto;
    }
    .cursor_pointer{
        cursor: pointer;
    }
</style>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Add Publication
    </h4>
</div>
<form action="{{ url('/employee/profile/store/publication') }}" method="post" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{ $employeeInfo->id }}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <label for="publication_title">Publication Title <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                </label>
                <input name="publication_title" id="publication_title" class="form-control" required />
            </div>
            <div class="col-sm-6">
                <label for="publication_description">Publication Description</label>
                <textarea name="publication_description" id="publication_description" class="form-control" cols="30" rows="1"></textarea>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Publication <sup style="color: red; font-size:18px; top:-1px;">*</sup></th>
                            <th>Publication Date <sup style="color: red; font-size:18px; top:-1px;">*</sup></th>
                            <th>Attachment <sub style="color: red;">max-size: 600kb</sub> </th>
                            <th>Remarks</th>
                            <th>Add/Remove</th>
                        </tr>
                    </thead>
                    <tbody id="publication-table-row-holder">
                        <tr>
                            <td>
                                <input type="text" class="form-control" required name="editions[]">
                            </td>
                            <td>
                                <input type="date" name="date[]" required class="form-control" id="date">
                            </td>
                            <td>
                                <input class="form-control attachment" type="file" name="attachment[]" accept=".png,.jpeg,.jpg,.pdf,.doc,.docx">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="remarks[]" id="remarks"/>
                            </td>
                            <td style="width:70px">
                                <span class="text-danger publication-table-remove-row-btn cursor_pointer"><i class="fa fa-minus"></i></span>
                            </td>
                        </tr>       
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                            <td><span class="publication-table-add-row-btn text-success cursor_pointer"><i class="fa fa-plus"></i></span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-right">Add</button>
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
                <input class="form-control" type="text" name="remarks[]" id="remarks"/>
            </td>
            <td style="width:70px">
                <span class="text-danger publication-table-remove-row-btn"><i class="fa fa-minus"></i></span>
            </td>
        </tr>
    </tbody>
</table>
<script type="text/javascript">
    $('#punishment_by_select').select2();
    $('.publication-table-add-row-btn').click(function(){
        var html = $('#publication-table-row').html();
        $('#publication-table-row-holder').append(html);
    });
    $(document).ready(function() {
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        });
        $.validator.addMethod('extension', function(value, element, param) {
            return this.optional(element) || value.match(new RegExp("." + param + "$"));
        });
        var validator = $("#acr-update").validate({
            // Specify validation rules
            rules: {
                year: {
                    required: true,
                },
                initiative_officer: {
                    required: true,
                },
                higher_officer: {
                    required: true,
                },
                io: {
                    required: true,
                },
                ho: {
                    required: true,
                },
                io_name: {
                    required: true,
                },
                ho_name: {
                    required: true,
                },
                attachment: {
                    filesize: 200000,
                    extension: "png|jpeg|jpg|pdf",
                },



            },

            // Specify validation error messages
            messages: {
                attachment: {
                    extension: "EcceptOnly(png,jpeg,jpg,pdf)",
                    filesize: "Please upload file less than 200Kb",
                }
            },

            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },

            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).closest('.form-group').addClass('has-success');
            },

            debug: true,
            success: "valid",
            errorElement: 'span',
            errorClass: 'help-block',

            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function(form) {

                form.submit();
            }
        });
    });
    $(document).on('click', '.publication-table-remove-row-btn', function () {
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

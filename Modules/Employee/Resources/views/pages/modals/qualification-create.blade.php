<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Add Qualification
    </h4>
</div>
<form id="qulification-create-form" action="{{ url('/employee/profile/store/qualification') }}" method="post"
    enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{ $employeeInfo->id }}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Qualification Type <sup style="color: red; font-size:18px; top:-1px;">*</sup></label>
                <select name="qualification_type" class="form-control" required>
                    <option value="1">General Qualification</option>
                    <option value="2">Special Qualification</option>
                    <option value="3">Last Academic Qualification</option>
                </select>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="qualification_year">Qualification Year <sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <input type="text" required name="qualification_year" id="qualification_year" placeholder="YYYY"
                        class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <label for="qualification_name">Qualification Name <sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <input type="text" name="qualification_name" id="qualification_name" required
                        class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="qualification_institute">Qualification Board/University <sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <input type="text" name="qualification_institute" id="qualification_institute" required
                        class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="qualification_group">Qualification Groups/Division</label>
                    <input type="text" name="qualification_group" id="qualification_group" class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="qualification_marks">Qualification Marks <sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <input type="text" name="qualification_marks" id="qualification_marks" required
                        class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="qualification_institute_address">Qualification Institute & Address <sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <textarea name="qualification_institute_address" id="qualification_institute_address" required class="form-control"
                        rows="1"></textarea>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="qualification_attachment">Qualification Attachment <sub
                            style="color: red; font-size:10px; bottom:-1px;">max-size: 200kb</sub></label>
                    <input type="file" name="qualification_attachment" id="qualification_attachment"
                        class="form-control" accept=".png,.jpeg,.jpg,.pdf">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-right submit_btn">Add</button>
    </div>
</form>

<script type="text/javascript">
    $('#dateOfBirth').datepicker();
    $("#qualification_year").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true //to close picker once year is selected
    });

    $(document).ready(function() {
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        });
        $.validator.addMethod('extension', function(value, element, param) {
            return this.optional(element) || value.match(new RegExp("." + param + "$"));
        });
        var validator = $("#qulification-create-form").validate({
            // Specify validation rules
            rules: {
                qualification_year: {
                    required: true,
                    minlength: 4
                },
                qualification_name: {
                    required: true,
                    minlength: 1
                },
                qualification_institute: {
                    required: true,
                    minlength: 1
                },
                qualification_marks: {
                    required: true,
                    minlength: 1
                },
                qualification_institute_address: {
                    required: true,
                    minlength: 1
                },
                qualification_attachment: {
                    required:false,
                    filesize: 200000,
                    extension: "png|jpeg|jpg|pdf",
                },



            },

            // Specify validation error messages
            messages: {
                qualification_attachment:{
                    extension:"EcceptOnly(png,jpeg,jpg,pdf)",
                    filesize:"Please upload file less than 200Kb",
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
    })
</script>

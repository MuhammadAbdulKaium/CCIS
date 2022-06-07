<form id="employee-update" action="{{ url('/employee/profile/update/qualification/' . $qualification->id) }}"
    method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Update Qualification
        </h4>
    </div>
    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Qualification Type</label>
                <select name="qualification_type" class="form-control">
                    <option value="1" @if ($qualification->qualification_type == 1) selected @endif>General Qualification</option>
                    <option value="2" @if ($qualification->qualification_type == 2) selected @endif>Special Qualification</option>
                    <option value="3" @if ($qualification->qualification_type == 3) selected @endif>Last Academic Qualification
                    </option>
                </select>
            </div>
            <div class="col-sm-3">
               <div class="form-group">
                <label for="qualification_year">Qualification Year</label>
                <input type="text" name="qualification_year" id="qualification_year" value="{{ $qualification->qualification_year }}"
                    class="form-control">
                    <div class="help-block"></div>
               </div>
            </div>
            <div class="col-sm-5">
               <div class="form-group">
                <label for="">Qualification Name</label>
                <input type="text" name="qualification_name" value="{{ $qualification->qualification_name }}"
                    class="form-control">
                    <div class="help-block"></div>
               </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Qualification Board/University</label>
                    <input type="text" name="qualification_institute"
                        value="{{ $qualification->qualification_institute }}" class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Qualification Groups/Division</label>
                    <input type="text" name="qualification_group" value="{{ $qualification->qualification_group }}"
                        class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="">Qualification Marks</label>
                    <input type="text" name="qualification_marks" value="{{ $qualification->qualification_marks }}"
                        class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Qualification Institute Address</label>
                    <textarea name="qualification_institute_address" class="form-control"
                        rows="1">{{ $qualification->qualification_institute_address }}</textarea>
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
    <div class="box-footer">
        <button type="submit" class="btn btn-info">Update</button> <button data-dismiss="modal"
            class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>


<script type="text/javascript">
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
        var validator = $("#employee-update").validate({
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
                    filesize: 200000,
                    extension: "png|jpeg|jpg|pdf",
                },



            },

            // Specify validation error messages
            messages: {
                qualification_attachment: {
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
</script>

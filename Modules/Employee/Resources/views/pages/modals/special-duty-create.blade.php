<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Add Special Duty
    </h4>
</div>
<form id="special-duty-create" action="{{ url('/employee/profile/store/special-duty') }}" method="post" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{ $employeeInfo->id }}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="description">Description of Special Duty <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                    </label>
                    <textarea name="description" required id="description" class="form-control" cols="30" rows="1"></textarea>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="start_date">Start of Special Duty <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                    </label>
                    <input type="date" required name="start_date" id="start_date" class="form-control"
                        placeholder="mm/dd/yy">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="end_date">End of Special Duty</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" placeholder="mm/dd/yy">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="remarks">Remarks of Special Duty</label>
                <input type="text" name="remarks" id="remarks" placeholder="Remarks of Training" class="form-control">
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="attachment">Attachment <sub style="color: red; font-size:10px; bottom:-1px;">max-size:
                        200kb</sub></label>
                    <input type="file" name="attachment" id="attachment" class="form-control" accept=".png,.jpeg,.jpg,.pdf">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-right">Add</button>
    </div>
</form>

<script type="text/javascript">
    $('#punishment_by_select').select2();

    $(document).ready(function() {
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        });
        $.validator.addMethod('extension', function(value, element, param) {
            return this.optional(element) || value.match(new RegExp("." + param + "$"));
        });
        var validator = $("#special-duty-create").validate({
            // Specify validation rules
            rules: {
                description: {
                    required: true,
                },
                start_date: {
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
</script>

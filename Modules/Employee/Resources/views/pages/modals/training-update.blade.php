<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Update Training Period
    </h4>
</div>
<form id="training-create" action="{{ url('/employee/profile/update/training/'.$training->id) }}" method="post" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{ $training->employee_id }}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="training_name">Name of Training <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                    </label>
                    <input type="text" name="training_name" value="{{ $training->training_name }}" id="training_name" class="form-control"
                        placeholder="Name of Training">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="training_institute">Institute of Training <sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <input type="text" name="training_institute" value="{{ $training->training_institute }}" id="training_institute" placeholder="Institute of Training"
                        class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="training_from">Start of Training <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                    </label>
                    <input type="date" name="training_from" value="{{ $training->training_from }}" id="training_from" class="form-control"
                        placeholder="mm/dd/yy">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="training_to">End of Training <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                    </label>
                    <input type="date" name="training_to"  value="{{ $training->training_to }}" id="training_to" class="form-control" placeholder="mm/dd/yy">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="training_duration">Duration of Training<sup
                            style="color: red; font-size:18px; top:-1px;">*</sup> </label>
                    <input type="text" id="training_duration" value="{{ $training->training_duration }}" name="training_duration" class="form-control"
                        placeholder="Duration of Training">
                    <div class="help-block"></div>
                </div>
            </div>

        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="training_grading">Grading of Training <sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <input type="text" name="training_grading" value="{{ $training->training_grading }}" id="training_grading" placeholder="Grading of Training"
                        class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="remarks">Remarks of Training</label>
                    <input type="text" name="remarks" id="remarks" value="{{ $training->remarks }}" placeholder="Remarks of Training" class="form-control">
                    <div class="help-block"></div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="attachment">Attachment<sub
                        style="color: red; font-size:10px; bottom:-1px;">max-size: 200kb</sub></label>
                
                    <input type="file" name="attachment" id="attachment" class="form-control" accept=".png,.jpeg,.jpg,.pdf">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right">Update</button>
    </div>
</form>

<script type="text/javascript">
    $('#punishment_by_select').select2();

    $('#training_from').change(function() {
        $('#training_duration').empty();
        $('#training_duration').val("1 day");
    });
    $('#training_to').change(function() {
       var training_from = $('#training_from').val();
       var startDate = new Date();
       if(training_from){
        startDate = new Date(training_from);
       }
        var endDate = new Date($(this).val());
        var day = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
        $('#training_duration').empty();
        $('#training_duration').val(day+" day");
    });
    $(document).ready(function() {
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        });
        $.validator.addMethod('extension', function(value, element, param) {
            return this.optional(element) || value.match(new RegExp("." + param + "$"));
        });
        var validator = $("#training-create").validate({
            // Specify validation rules
            rules: {
                training_name: {
                    required: true,
                    minlength: 1
                },
                training_institute: {
                    required: true,
                    minlength: 1
                },
                training_from: {
                    required: true,
                },
                training_to: {
                    required: true,
                },
                training_duration: {
                    required: true,
                },
                training_grading: {
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

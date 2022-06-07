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
        <i class="fa fa-plus-square"></i> Add ACR
    </h4>
</div>
<form id="acr-create" action="{{ url('/employee/profile/store/acr') }}" method="post" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{ $employeeInfo->id }}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="year"> Years <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                    </label>
                    <input type="text" required class="form-control" name="year" placeholder="YYYY" id="datepicker" />
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="initiative_officer">IO Grading <sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <select required name="initiative_officer" id="initiative_officer" class="form-control">
                        @for ($i = 0; $i <= 100; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="higher_officer">HO Grading <sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <select required name="higher_officer" id="higher_officer" class="form-control">
                        @for ($i = 0; $i <= 100; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="io"> Recommendations IO <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                    </label>
                    <select required name="io" id="io" class="form-control">
                        <option value="1">Recommended</option>
                        <option value="2">Not Recommended</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label required for="ho">Recommendations HO <sup style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <select name="ho" id="ho" class="form-control">
                        <option value="1">Recommended</option>
                        <option value="2">Not Recommended</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>

        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="io_name"> Recommendations IO Name <sup style="color: red; font-size:18px; top:-1px;">*</sup>
                    </label>
                    <select required name="io_name" id="io_name" class="form-control">
                        <option value="">__Select__</option>
                        @foreach ($allEmployee as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->title . ' ' . $employee->first_name . ' ' . $employee->last_name }}
                                @if ($employee->singleUser)
                                    - ({{ $employee->singleUser->username }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="ho_name">Recommendations HO Name<sup
                            style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <select required name="ho_name" id="ho_name" class="form-control">
                        <option value="">__Select__</option>
                        @foreach ($allEmployee as $employee)
                            <option value="{{ $employee->id }}">
                                {{ $employee->title . ' ' . $employee->first_name . ' ' . $employee->last_name }}
                                @if ($employee->singleUser)
                                    - ({{ $employee->singleUser->username }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>

        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <label for="remarks">Remarks</label>
                <textarea name="remarks" class="form-control" id="remarks" cols="30" rows="1"></textarea>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="attachment">Attachment <sub
                        style="color: red; font-size:10px; bottom:-1px;">max-size: 200kb</sub></label>
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
    $("#datepicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true //to close picker once year is selected
    });
    $('#ho_name').select2();
    $('#io_name').select2();
    $('#initiative_officer').select2();
    $('#higher_officer').select2();
    $(document).ready(function() {
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        });
        $.validator.addMethod('extension', function(value, element, param) {
            return this.optional(element) || value.match(new RegExp("." + param + "$"));
        });
        var validator = $("#acr-create").validate({
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

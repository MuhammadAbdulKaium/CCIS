
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Add Disciplinary
    </h4>
</div>
<form id="discipline-create" action="{{url('/employee/profile/store/discipline')}}" method="post" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{$employeeInfo->id}}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="occurrence_date">Occurrence Date <sup style="color: red; font-size:18px; top:-1px;">*</sup> </label>
                    <input type="date" name="occurrence_date" id="occurrence_date" class="form-control" placeholder="mm/dd/yy" required>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="place">Place/Location <sup style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <input type="text" name="place" id="place" placeholder="Place/Location" class="form-control" required>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                @php
                   
                    $punishment_categoyr_show = ['পদোন্নতি স্থগিত - Promotion Suspend','বেতন কর্তন - Salary Deduction','চূড়ান্ত সতর্ক - Final Warning','তিরস্কার - Reprimand','কঠোরভাবে তিরস্কার - Strict Reprimand','সতর্কীকরণ - Warning','কঠোরভাবে সতর্কীকরণ - Strict Warning','অসন্তোষ - Dissatisfaction','কঠোর অসন্তোষ - Severe Dissatisfaction','গভীর অসন্তোষ - Deep Dissatisfaction','উপদেশ - Advice','ব্যাখ্যা - Explanation','পত্র প্রদান - Letter Issue','নির্দেশ - Instruction','জবাবদিহি - Accountability'];
                @endphp
                 <div class="form-group">
                     <label for="punishment_category">Punishment Category <sup style="color: red; font-size:18px; top:-1px;">*</sup></label>
                     <select name="punishment_category" id="punishment_category" class="form-control" required>
                         <option value="">--Select Punishment Category--</option>
                         @foreach ($punishment_categoyr_show as $key=>$punishment_category)
                             <option value="{{ $punishment_category }}">{{ $punishment_category }}</option>
                         @endforeach
                     </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="punishment_date">Punishment Date <sup style="color: red; font-size:18px; top:-1px;">*</sup></label>
                    <input type="date" name="punishment_date" id="punishment_date" placeholder="mm/dd/yy" class="form-control" required>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4">
                <label for="">Punishment By (Select)</label>
                <select name="punishment_by_select" id="punishment_by_select" class="form-control select_by"> 
                    <option value="">__Select__</option>
                    @foreach ($allEmployee as $employee )
                    <option value="{{ $employee->id }}">
                        {{ $employee->title." ".$employee->first_name." ".$employee->last_name }} 
                        @if ($employee->singleUser)
                        - ({{ $employee->singleUser->username }})
                    @endif
                    </option>
                        
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4">
                <label for="punishment_by_write">Punishment By (Write)</label>
               
                <input type="text" name="punishment_by_write" placeholder="Punishment By (Write)"  id="punishment_by_write"  class="form-control">
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="description">Description <sup style="color: red; font-size:18px; top:-1px;">*</sup> </label>
                    <textarea name="description" id="description" class="form-control" rows="1" required></textarea>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <label for="remarks">Remarks</label>
                <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Remarks (optional)">
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="attachment">Attachment <sub style="color: red; font-size:10px; bottom:-1px;">max-size:
                        200kb</sub></label>
                    <input type="file" id="attachment" name="attachment" class="form-control" accept=".png,.jpeg,.jpg,.pdf">
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
    // $('#occurrence_date').datepicker();
    // $('#punishment_date').datepicker();
    $('#punishment_by_select').select2();
    $(document).ready(function() {
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        });
        $.validator.addMethod('extension', function(value, element, param) {
            return this.optional(element) || value.match(new RegExp("." + param + "$"));
        });
        var validator = $("#discipline-create").validate({
            // Specify validation rules
            rules: {
                occurrence_date: {
                    required: true,
                },
                place: {
                    required: true,
                },
                punishment_category: {
                    required: true,
                },
                punishment_date: {
                    required: true,
                },
                description: {
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


<div class="modal-header">
   <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
   <h3 class="box-title"><i class="fa fa-plus-square"></i> Upadate Designaion</h3>
</div>
<form id="designation-update-form" action="{{url('/employee/designations/update', [$designationProfile->id])}}" method="POST">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <div class="modal-body">
                <div class="row">
                   <div class="col-sm-6">
                      <div class="form-group">
                         <label class="control-label" for="name">Designaion</label>
                         <input id="name" class="form-control" value="@if($designationProfile){{$designationProfile->name}}@endif" name="name" maxlength="50" type="text">
                         <div class="help-block"></div>
                      </div>
                   </div>
                   <div class="col-sm-6">
                      <div class="form-group">
                         <label class="control-label" for="alias">Alias</label>
                         <input id="alias" class="form-control" value="@if($designationProfile){{$designationProfile->alias}}@endif" name="alias" type="text">
                         <div class="help-block"></div>
                      </div>
                   </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="bengali_name">Bengali Name</label>
                        <input id="bengali_name" class="form-control" name="bengali_name" value="@if($designationProfile){{$designationProfile->bengali_name}}@endif" maxlength="50" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="strength">Strength</label>
                        <input id="strength" class="form-control" name="strength" value="@if($designationProfile){{$designationProfile->strength}}@endif" type="number">
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="make_as">Make As</label>
                        <select name="make_as" id="make_as" class="form-control">
                           <option value="0">--Select--</option>
                           <option  {{ ($designationProfile->make_as ==1)?'selected':" " }} value="1">Teaching Category</option>
                           <option {{ ($designationProfile->make_as ==2)?'selected':" " }} value="2">Officer Category</option>
                           <option {{ ($designationProfile->make_as ==3)?'selected':" " }} value="3">General Category</option>
                           <option {{ ($designationProfile->make_as ==4)?'selected':" " }} value="4">Other Category</option>
                        </select>
                        <div class="help-block"></div>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                       <label class="control-label" for="des_class">Class</label>
                       <select name="class" id="des_class" class="form-control">
                          <option value="0">--Select--</option>
                          <option {{ ($designationProfile->class ==1)?'selected':" " }} value="1">1st Class Officer</option>
                          <option {{ ($designationProfile->class ==2)?'selected':" " }} value="2">2nd Class Employee</option>
                          <option {{ ($designationProfile->class ==3)?'selected':" " }} value="3">3rd Class Employee</option>
                          <option {{ ($designationProfile->class ==4)?'selected':" " }} value="4">4th Class Employee</option>
                       </select>
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
   </div>
   <!--./modal-body-->
   <div class="modal-footer">
      <button type="submit" class="btn btn-info pull-left"></i> Update</button>  <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
   </div>
   <!--./modal-footer-->
</form>


  <script type="text/javascript">
    $(document).ready(function(){

      // validate signup form on keyup and submit
      var validator = $("#designation-update-form").validate({
                // Specify validation rules
                rules: {
                  name: {
                        required: true,
                        minlength: 1,
                        maxlength: 35,
                    },
                  alias: {
                        required: true,
                        minlength: 1,
                        maxlength: 35,
                    },
                    bengali_name: {
                        required: true,
                        minlength: 1

                    },
                    strength: {
                        required: true,
                        minlength: 1

                    },
                    make_as: {
                        required: true,
                    },
                    class: {
                        required: true,
                    },
                },

                // Specify validation error messages
                messages: {
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

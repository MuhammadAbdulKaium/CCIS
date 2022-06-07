<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Salary Scale </h3>
</div>
<form id="salaryComponent-create-form" action="{{url('/payroll/salary/scale/update')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="institute_id" value="{{session()->get('institute')}}">
    <input type="hidden" name="id" value="{{$salaryScale->id}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="errorTxt"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="custom_name">Scale Name </label>
                    <input id="scale_name" class="form-control" name="scale_name" type="text" required value="{{$salaryScale->scale_name}}">
                    <div class="help-block"></div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="grade_id">Salary Grade </label>
                    <select name="grade_id" id="" class="form-control" required>
                        @foreach($salaryGrades as $grade)
                            <option value="{{$grade->id}}">{{$grade->grade_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="maximum_amt">Maximum Amount </label>
                    <input id="maximum_amt" class="form-control" name="maximum_amt" type="number" required value="{{$salaryScale->maximum_amt}}">
                    <div class="help-block"></div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="minimum_amt">Minimum Amount </label>
                    <input id="minimum_amt" class="form-control" name="minimum_amt" type="number" required value="{{$salaryScale->minimum_amt}}">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <!--./modal-body-->
        <div class="modal-footer">
            <button type="submit" class="btn btn-info pull-left" id="create"> Update</button>
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        </div>
        <!--./modal-footer-->
    </div>
</form>

<style>
    .pad-right{
        padding-right: 20px;
    }
</style>

<script type="text/javascript">

</script>
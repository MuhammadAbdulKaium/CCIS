<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Salary Grade </h3>
</div>
<form id="salaryComponent-create-form" action="{{url('/payroll/salary/grade/update')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="institute_id" value="{{session()->get('institute')}}">
    <input type="hidden" name="id" value="{{$grade->id}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="errorTxt"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="custom_name">Grade Name </label>
                    <input id="grade_name" class="form-control" name="grade_name" type="text" value="{{$grade->grade_name}}">
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

<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Bank Branch Edit</h3>
</div>
<form id="salaryComponent-create-form" action="{{url('/payroll/bank/branch/update')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" value="{{$branch->id}}" name="id">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="errorTxt"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="custom_name">Branch Name </label>
                    <input class="form-control" name="branch_name" type="text" required value="{{$branch->branch_name}}">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="custom_name">Bank Name </label>
                    <select name="bank_id" class="form-control">
                        @foreach($bankNames as $bank)
                            <option value="{{$bank->id}}" {{$branch->bank_id==$bank->id?'selected':''}}>{{$bank->bank_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="custom_name">Branch Location </label>
                    <input class="form-control" name="branch_location" type="text" required value="{{$branch->branch_location}}">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="custom_name">Branch Phone </label>
                    <input class="form-control" name="branch_phone" type="number" required value="{{$branch->branch_phone}}">
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

<form action="{{url('/house/store/house-appoint')}}" method="POST">
    @csrf

    <input type="hidden" name="appointId" value="{{ $appoint->id }}">

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit {{ $appoint->name }}</h4>
    </div>

    <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                <label for="">Category</label>
                <select name="category" class="form-control" required>
                    <option value="hr" @if ($appoint->category == 'hr') selected @endif>HR</option>
                    <option value="fm" @if ($appoint->category == 'fm') selected @endif>FM</option>
                    <option value="cadet" @if ($appoint->category == 'cadet') selected @endif>Cadet</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="">Appointment Name</label>
                <input type="text" name="name" class="form-control" value="{{ $appoint->name }}" required>
            </div>
            <div class="col-md-3">
                <label for="">Symbol</label>
                <input type="text" name="symbol" class="form-control" value="{{ $appoint->symbol }}">
            </div>
            <div class="col-md-2">
                <label for="">Color</label>
                <input type="color" name="color" class="form-control" value="{{ $appoint->color }}">
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success">Update</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        
    });
</script>
<form action="{{ url('/house/store/record-score') }}" method="POST">
    @csrf

    <input type="hidden" name="houseId" value="{{ $house->id }}">

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Add Record Scores
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-3">
                <select name="yearId" class="form-control" id="select-modal-year" required>
                    <option value="">--Year--</option>
                    @foreach ($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}">{{ $academicYear->year_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4">
                <select name="termId" class="form-control" id="select-modal-term" required>
                    <option value="">--Term--</option>
                </select>
            </div>
            <div class="col-sm-5">
                <select name="categoryId" class="form-control" required>
                    <option value="">--Category--</option>
                    <option value="0">Overall</option>
                    @foreach ($performanceTypes as $performanceType)
                        <option value="{{ $performanceType->id }}">{{ $performanceType->performance_type }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 15px">
            <div class="col-sm-5">
                <select name="batchId" class="form-control" id="select-modal-batch" required>
                    <option value="">--Class--</option>
                    @foreach ($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->batch_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <select name="sectionId" class="form-control" id="select-modal-section">
                    <option value="">--Form--</option>
                </select>
            </div>
            <div class="col-sm-4">
                <input type="text" id="date" class="form-control hasDatepicker"
                    name="date" maxlength="10" placeholder="Date" aria-required="true"
                    size="10" required>
            </div>
        </div>
        <div class="row" style="margin-top: 15px">
            <div class="col-sm-3">
                <input type="number" name="score" class="form-control" placeholder="Score">
            </div>
            <div class="col-sm-9">
                <textarea name="remarks" class="form-control" placeholder="Remarks" rows="1"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-success pull-right">Add</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#date').datepicker();

        $('#select-modal-year').change(function () {
           // Ajax Request Start
           $_token = "{{ csrf_token() }}";
           $.ajax({
               headers: {
                   'X-CSRF-Token': $('meta[name=_token]').attr('content')
               },
               url: "{{ url('/house/get/term/from/year') }}",
               type: 'GET',
               cache: false,
               data: {
                   '_token': $_token,
                   'yearId': $(this).val(),
               }, //see the _token
               datatype: 'application/json',
           
               beforeSend: function () {},
           
               success: function (data) {
                   var txt = '<option value="">--Term--</option>';

                   data.forEach(element => {
                       txt += '<option value="'+element.id+'">'+element.name+'</option>';
                   });

                   $('#select-modal-term').html(txt);
               }
           });
           // Ajax Request End 
        });

        $('#select-modal-batch').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/get/sections/from/batch') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'batchId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Form--</option>';

                   data.forEach(element => {
                       txt += '<option value="'+element.id+'">'+element.section_name+'</option>';
                   });

                   $('#select-modal-section').html(txt);
                }
            });
            // Ajax Request End
        });
    });
</script>

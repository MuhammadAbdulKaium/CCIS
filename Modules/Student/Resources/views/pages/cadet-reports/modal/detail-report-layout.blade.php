<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Cadet Detail Report Layout</h4>
</div>

<div class="modal-body" style="overflow:auto">
    @foreach ($reportLayouts as $reportLayout)
        <form action="{{ url('/student/update/detail/report/block/'.$reportLayout->id) }}" method="POST">
            @csrf

            <div class="row" style="margin-bottom: 15px">
                <div class="col-sm-3">
                    <label for="">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $reportLayout->title }}">
                </div>
                <div class="col-sm-9">
                    <label for="">Description</label>
                    <textarea name="description" id="editor{{ $loop->index+1 }}"  class="form-control" rows="5">{{ $reportLayout->description }}</textarea>
                </div>

                <div class="col-sm-12" style="margin-top: 15px">
                    @if(in_array('student/delete/detail/report/block/{id}', $pageAccessData))
                        <a href="{{ url('/student/delete/detail/report/block/'.$reportLayout->id) }}"
                            class="btn btn-danger" style="float: right; margin-left:5px"
                            onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                            data-content="delete"><i class="fa fa-trash-o"></i></a>
                    @endif

                    @if(in_array('student/update/detail/report/block/{id}', $pageAccessData))
                        <button class="btn btn-success" style="float: right">Update</button>
                    @endif
                </div>
            </div>
        </form>

        <script>
            CKEDITOR.replace( 'editor{{ $loop->index+1 }}' );
        </script>
    @endforeach

    <h4><b>Add New Block:</b></h4>
    <form action="{{ url('/student/add/detail/report/block') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-sm-3">
                <label for="">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Title">
            </div>
            <div class="col-sm-9">
                <label for="">Description</label>
                <textarea name="description" id="editor"  class="form-control" rows="5"></textarea>
            </div>
            <div class="col-sm-12">
                <button class="btn btn-success" style="float: right; margin-top: 15px">Add</button>
            </div>
        </div>
    </form>
</div>

<script>
    CKEDITOR.replace( 'editor' );
</script>
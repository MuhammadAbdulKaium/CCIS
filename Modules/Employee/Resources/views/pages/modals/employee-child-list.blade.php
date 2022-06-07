<!-- modla header -->
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> {{$employee->first_name}}'s Childs </h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $childrens = $employee->myGuardians();
                    @endphp

                    @foreach($childrens as $children)
                        @if($children->guardian()->type == 7 || $children->guardian()->type == 8)
                            <tr>
                                <td>{{$children->guardian()->first_name}}</td>
                                <td>
                                    @if($children->guardian()->gender == 1)
                                        Male
                                    @elseif($children->guardian()->gender == 2)
                                        Female
                                    @else
                                        Other
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- modal footer -->
<div class="modal-footer">
    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
</div>
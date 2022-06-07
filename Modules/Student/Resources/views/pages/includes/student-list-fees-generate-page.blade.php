
{{-- @php  print_r($allEnrollments) @endphp --}}
<div class="col-md-12">
    <div class="box box-solid">
        <div class="et">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> View Student List</h3>
            </div>
        </div>
        <div class="card">
            <form id="cad_generate_submit_form">
                @csrf
                @if(isset($searchData))
                    @if($searchData->count()>0)
                        @php $i=1; @endphp
                        <table class="table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Student Number</th>
                                <th>Name</th>
                                <th>Roll</th>
                                <th>Month</th>
                                <th>Fees</th>
                                <th>Current Fees</th>
                                <th>Delay Fine</th>
                                <th>Fine Type</th>
                                <th>Last Payment Date</th>
                            </tr>

                            </thead>

                            <tbody>
                            @foreach($searchData as $key=>$data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->email}}</a></td>
                                    <td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->first_name}} {{$data->last_name}}</a></td>
                                    <td>{{$data->gr_no}}</td>
                                    <td>
                                        @foreach($month_list as $key=>$value)
                                            @if($month_name == $key)
                                                {{$value}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <input type="hidden" name="academic_year[{{$data->std_id}}]" value="{{$data->academic_year}}">
                                    <input type="hidden" id="cad_{{$data->std_id}}" name="std_id[{{$data->std_id}}]" value="{{$data->std_id}}">
                                    <input type="hidden"  name="batch[{{$data->std_id}}]" value="{{$data->batch}}">
                                    <input type="hidden"  name="section[{{$data->std_id}}]" value="{{$data->section}}">
                                    <input type="hidden" id="academic_level_{{$data->academic_level}}" name="academic_level[{{$data->std_id}}]" value="{{$data->academic_level}}">
                                        @isset($generateFeesData[$data->std_id])
                                            @php
                                            $generateFees=json_decode($generateFeesData[$data->std_id],1);
                                            @endphp
{{-- Check payment done or not--}}
                                            @isset($generateFees)
                                                @isset($feesCollectionData[$data->std_id])
                                                    @php
                                                    $feesCollection=json_decode($feesCollectionData[$data->std_id],1);
                                                    @endphp
                                                    @if($feesCollection['fees_generate_id']==$generateFees['id'])
                                                    <td class="bg-danger">
                                                        <input type="number" name="amount[{{$data->std_id}}]" value="{{$generateFees['fees']}}" class="form-control" disabled>
                                                    </td>
                                                    @else
                                                        <td class="bg-info">
                                                            <input type="number" name="amount[{{$data->std_id}}]" value="{{$generateFees['fees']}}" class="form-control">
                                                        </td>
                                                    @endif
                                            @else
                                                <td class="bg-info">
                                                    <input type="number" name="amount[{{$data->std_id}}]" value="{{$generateFees['fees']}}" class="form-control">
                                                </td>
                                            @endisset
{{-- Check payment done or not--}}
                                            @else
                                            <td class="bg-success">
                                                <input type="number" name="amount[{{$data->std_id}}]" value="{{$data->singleEnroll->tution_fees}}" class="form-control">
                                            </td>
                                            @endisset
                                        @else
                                            <td class="bg-success">
                                                <input type="number" name="amount[{{$data->std_id}}]" value="{{$data->singleEnroll->tution_fees}}" class="form-control">
                                            </td>
                                        @endisset

                                    <td class="bg-success">
                                        {{$data->singleEnroll->tution_fees}}
                                    </td>
                                    <td>
                                        <input type="number" id="fine_{{$data->std_id}}" name="fine[{{$data->std_id}}]" value="{{$fine}}" class="form-control">
                                    </td>
                                    <td>
                                        <select name="fine_type[{{$data->std_id}}]" class="form-control" readonly>
                                            <option value="1" {{ 1 == $fine_type ? 'selected' : '' }}>Per day</option>
                                            <option value="2" {{ 2 == $fine_type ? 'selected' : '' }}>Fixed</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" value="{{$month_name}}" class="form-control" name="month_name[{{$data->std_id}}]" readonly>
                                        <input type="date" value="{{$payment_last_date}}" class="form-control" name="payment_last_date[{{$data->std_id}}]" readonly>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @php $i++; @endphp
                    @else
                        <h5 class="text-center"> <b>Sorry!!! No Result Found</b></h5>
                    @endif
                @endif
                <button type="submit" class="btn btn-primary" id="assignData">Generate</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#example2").DataTable();
        $('#example1').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false
        });

        // paginating
        $('.pagination a').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href').replace('store', 'find');
            loadRolePermissionList(url);
            // window.history.pushState("", "", url);
            // $(this).removeAttr('href');
        });
        // loadRole-PermissionList
        function loadRolePermissionList(url) {
            $.ajax({
                url: url,
                type: 'POST',
                cache: false,
                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        var std_list_container_row = $('#std_list_container_row');
                        std_list_container_row.html('');
                        std_list_container_row.append(data.html);
                    }else{
                        alert(data.msg)
                    }
                },
                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        }
    });
</script>
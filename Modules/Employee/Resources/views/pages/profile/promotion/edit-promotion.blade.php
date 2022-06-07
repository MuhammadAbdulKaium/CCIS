
    <section class="content-header">
        <h1>
            <i class="fa fa-info-circle"></i> Confirm | <small>Employee</small>
        </h1>

    </section>
    <section class="content">
        @if(Session::has('success'))
            <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
            </div>
        @elseif(Session::has('alert'))
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
            </div>
        @elseif(Session::has('warning'))
            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
            </div>
        @endif


            <form action="{{route('employee.promotion.store',$promotionInfo->id)}}" method="POST">
                @csrf

                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i> Preview Selected Details
                                    </div>
                                    <table class="table">
                                        <colgroup>
                                            <col style="width:125px">
                                        </colgroup>
                                        <tbody>
                                        <tr>
                                            <th class="text-center">Department </th>
                                            <td>@if($employee->singleDepartment)
                                                    <input type="hidden" name="id" value="{{$employee->id}}">
                                                {{$employee->singleDepartment->name}}</td>
                                                @endif
                                        </tr>
                                        <tr>
                                            <th class="text-center">Designation</th>
                                            <td>@if($employee->singleDesignation)
                                                    {{$employee->singleDesignation->name}}</td>
                                            @endif</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Category</th>
                                            <td>
                                                @if($employee->category===1)
                                                    Teaching
                                                @elseif($employee->category===2)
                                                Non-Teaching

                                                @endif


                                            </td>
                                        </tr>
                                        <tr>
                                            @php
                                            $lastDate=\Carbon\Carbon::parse($promotionInfo->last_promotion_date)
                                            ->format('m/d/Y');

                                                if(sizeof($lastPromotion)>0){
                                                   foreach ($lastPromotion as $pr){
                                                      $last_join=$pr->promotion_date;
                                                      break;

                                                   }
                                                   }else{
                                                       $last_join=$employee->doj;
                                                   }
                                                   $last_join=\Carbon\Carbon::make($last_join)->format('m/d/Y');



                                            @endphp

                                            <th class="text-center">Last Promotion </th>
                                            <td class="form-group">
                                                <div class="d-flex " style="display: flex;">
                                                    <input type="checkbox" id="customdate"
                                                           @if($lastDate!==$last_join)
                                                                   checked
                                                                   @endif
                                                           class="checkbox " >
                                                    <label for="joinDate"> &nbsp;Custom date</label>
                                                </div>




                                                <input type="text" name="last_promotion_date" id="custom-join-date"
                                                       value="{{$lastDate  }}"
                                                       class="datepicker
                                                form-control"
                                                @if($lastDate===$last_join)

                                                    disabled
                                                    @endif
                                                >
                                                <input type="hidden" name="system_last_promotion"
                                                       value="{{$last_join}}">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-3 text-center">
                                <div class="panel panel-warning">
                                    <div class="panel-body">
                                        <h4> <i class="fa fa-cog"></i> Apply Action </h4>
                                        <div class="form-group">
                                            <div class="col-sm-8">
                                                <input id="confirm_promo_action" name="promo_action_type"  type="hidden">
                                            </div>
                                        </div>
                                        <h4 class="text-yellow"><strong></strong></h4>
                                        <h4><i class="fa fa-hand-o-right"></i></h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <i class="fa fa-check-circle"></i> Select Promote Details
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-4 text-center">
                                                    <label class="control-label" for="academic_year">
                                                        Department</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <select id=""  class="form-control academicYear"
                                                                name="department" >
                                                            @foreach($departments as $department)


                                                                <option
                                                                        @if(
                                                                        $promotionInfo->department ===$department->id)
                                                                                selected
                                                                                @endif
                                                                        value="{{$department->id}}">{{$department->name}}</option>

                                                            @endforeach

                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4 text-center">
                                                    <label class="control-label" for="batch">Designation</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <select id="batch" class="form-control academicBatch"
                                                                name="designation" >
                                                            @foreach($designations as $designation)


                                                                <option
                                                                        @if(
                                                                        $promotionInfo->designation===$designation->id)
                                                                        selected
                                                                        @endif
                                                                        value="{{$designation->id}}">{{$designation->name}}</option>

                                                            @endforeach
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4 text-center">
                                                    <label class="control-label" for="section">Category</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <select id="section" class="form-control academicSection"
                                                                name="category" required>
                                                            <option value="1"  @if($promotionInfo->category==1) selected @endif
                                                                >Teaching</option>
                                                            <option value="2"  @if($promotionInfo->category==2) selected
                                                                    @endif
                                                            >Non Teaching</option>
<!--                                                            <option value="3"  @if($promotionInfo->category==3) selected
                                                                    @endif
                                                            >Officers</option>-->
                                                        </select>
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4 text-center">
                                                    <label class="control-label" for="section">Promotion Board</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <input type="text" name="promotion_board"
                                                               value="{{$promotionInfo->promotion_board}}"
                                                               required
                                                               placeholder="Board name"
                                                               class="form-control ">
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--<div id="course-notice" class="alert bg-warning text-warning text-bold" style="display:none">--}}
                                            {{--<i class="fa fa-warning" aria-hidden="true">--}}
                                            {{--</i> No course available in selected academic year.--}}
                                            {{--<a href="#" target="_blank" style="color:inherit">Click here to create</a>--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>

                            </div>
                        @if (in_array('employee/promotion.approve', $pageAccessData))
                            <!-- Promotion Board Tab  -->
                            <div class="col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i> <b>Promotion Board Decision</b>
                                    </div>
                                    <table class="table">
                                        <colgroup>
                                            <col style="width:125px">
                                        </colgroup>
                                        <tbody>
                                        <tr>
                                            <th class="text-center">Status </th>
                                            <td class="form-group">

                                                <select name="status" id="status" class="form-control select">
                                                    <option value="pending" class="form-control
                                                    text-warning">Pending</option>
                                                    <option value="approved" class="form-control text-success">Approved</option>
                                                    <option value="rejected" class="form-control
                                                    text-danger">Rejected</option>

                                                </select>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th class="text-center">Board Remarks</th>
                                            <td>
                                                <div class="form-group">
                                                    <textarea name="board_remarks" class="form-control" >{{$promotionInfo->board_remarks}}</textarea>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Board Reasoning</th>
                                            <td>
                                                <div class="form-group">
                                                    <textarea name="reasoning" id="reasoning" class="form-control"
                                                    >{{$promotionInfo->reasoning}}</textarea>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!--./box-body-->
                    <div class="box-footer">
                        <a class="btn btn-primary" href="{{ url()->previous() }}"><i class="fa fa-times" aria-hidden="true"></i>
                            Cancel</a>
                        <button type="submit" class="btn btn-success pull-right" onclick="return confirm('Are you sure to Continue ?')"><i class="fa fa-floppy-o" aria-hidden="true"></i> Confirm &amp; Submit</button>
                    </div>
                </div>

                {{--selected student list--}}

            </form>


    </section>
    <script>


    </script>
    <script>
        $(document).ready(function (){
            $('.datepicker').datepicker({
                autoclose: true,


            });

            $("#customdate").on("change",function (){
                if($("#customdate").prop('checked')){
                    document.getElementById("custom-join-date").removeAttribute("disabled");

                }else {

                    document.getElementById("custom-join-date").setAttribute("disabled",true);
                    $("#custom-join-date").val("{{$last_join}}");

                }
            })
            $("#status").on("change",function (){
                if($("#status").val()==="approved" || $("#status").val()==="rejected"){
                    $('#reasoning').prop('required',true);
                }else{
                    $('#reasoning').prop('required',false);
                }
                console.log($("#status").val());
            });
        })

    </script>

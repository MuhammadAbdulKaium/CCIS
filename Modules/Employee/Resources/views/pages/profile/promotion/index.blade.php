
@extends('employee::layouts.profile-layout')

@section('profile-content')

    <p class="text-right">
        @if (in_array('employee/promotion.promote', $pageAccessData))
@if(sizeof($newPromotion)==0)
            <a class="btn btn-primary btn-sm" href="/employee/profile/promote/{{$employeeInfo->id}}"
               oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal"
               data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Promote Employee</a>

@endif
        @endif
    </p>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th rowspan="2">
                    #
                </th>
                <th rowspan="1" colspan="3" class="text-center bg-warning">
                   Previous
                </th>
                <th rowspan="1" colspan="3" class="text-center bg-success">
                    Current
                </th>

                <th rowspan="2" class="align-middle" >
                    Promotion Board
                </th>
                <th rowspan="2" class="align-middle">
                    Board Remarks
                </th>

                <th rowspan="2">
                    Reasoning
                </th>
                <th rowspan="2">
                   Last promotion Date
                </th>
                <th rowspan="2">
                    Promoted on
                </th>
                <th rowspan="2">
                   Authorized By
                </th>
                <th rowspan="2" style="vertical-align: middle">
                    Status
                </th>

            </tr>
            <tr>
                <th class=" bg-warning">
                    College
                </th>
                <th  class=" bg-warning">
                   Department
                </th>
                <th  class=" bg-warning">
                    Designation
                </th>
                <th class="bg-success">
                    College
                </th>
                <th class="bg-success">
                    Department
                </th >
                <th class="bg-success">
                    Designation
                </th>
            </tr>
            </thead>
            <tbody>
                @foreach($promotions as $promotion)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>

                        @if($allCampus &&  isset($allInstitute[$promotion->prev_institute]))
                                {{$allInstitute[$promotion->prev_institute]->institute_alias}}
                            @endif
                        </td>
                        <td>

                            @if($allDept &&  isset($allDept[$promotion->previous_department]))
                                {{$allDept[$promotion->previous_department]->name}}
                            @endif
                        </td>
                        <td>

                            @if($allDesignation &&  isset($allDesignation[$promotion->previous_designation]))
                                {{$allDesignation[$promotion->previous_designation]->name}}
                            @endif
                        </td>

                        <td>

                            @if($allCampus &&  isset($allInstitute[$promotion->prev_institute]))
                                {{$allInstitute[$promotion->prev_institute]->institute_alias}}
                            @endif
                        </td>
                        <td>

                            @if($allDept &&  isset($allDept[$promotion->department]))
                                {{$allDept[$promotion->department]->name}}
                            @endif
                        </td>
                        <td>

                            @if($allDesignation &&  isset($allDesignation[$promotion->designation]))
                                {{$allDesignation[$promotion->designation]->name}}
                            @endif
                        </td>
                        <td>

                           {{$promotion->promotion_board}}
                        </td>
                        <td>

                            {{$promotion->board_remarks}}
                        </td>
                        <td>

                            {{$promotion->reasoning}}
                        </td>
                        <td>

                            {{$promotion->last_promotion_date}}
                        </td>
                        <td>
                            @if($promotion->status=='approved')
                            {{$promotion->promotion_date}}
                            @endif
                        </td>
                        <td>
                            @if($promotion->authorized)

                                {{$promotion->authorized->name}}
                                @endif

                        </td>

                        <td>


                            @if($promotion->status=="pending")
                                <span  class="text-warning">Pending</span>
                            @elseif($promotion->status=="approved")
                                <span   class="text-success">Approved</span>
                            @else
                                <span  class="text-danger">Not Approved</span>
                                @endif
                            @if($promotion->status=="pending")
                                @if (in_array('employee/promotion.edit', $pageAccessData))
                                    <a class="btn btn-primary btn-sm"
                                       href="{{route('employee.promotion.edit',$promotion->id)}}"
                                       oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal"
                                       data-modal-size="modal-lg"><i class="fa fa-pencil-square-o"
                                                                     aria-hidden="true"></i> Edit</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
<style>
    .custom-badge{
        border-radius: 5%;
        font-weight: bolder;
        color: whitesmoke;
        border: 1px solid gray;
        padding: 4px;
        margin: 5px;
    }
</style>

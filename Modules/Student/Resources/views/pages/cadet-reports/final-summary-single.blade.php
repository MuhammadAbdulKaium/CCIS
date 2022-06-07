<style>
    th
    {
        vertical-align: bottom;
        text-align: center;
    }

    th span
    {
        -ms-writing-mode: tb-rl;
        -webkit-writing-mode: vertical-rl;
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
    }
</style>

<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Final Summary Sheet  <span style="color: blue"><i class="fa fa-print"></i></span> </h4>
</div>
<div class="modal-body table-responsive">

    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th rowspan="3" ><span>YEAR</span></th>
            <th rowspan="1" colspan="{{sizeof($physicalFitness)}}">Physical Fitness</th>
            <th rowspan="1" colspan="{{sizeof($selfVirtues)}}">Personal Traits / Self Virtues</th>
            <th rowspan="1" colspan="3">Academic</th>
            <th rowspan="1" colspan="6">Co-Curricular</th>
            <th rowspan="1" colspan="12">Extra-Curricular</th>
            <th rowspan="3"><span>Monthly Fee</span></th>
        </tr>
        <tr>
            @foreach($physicalFitness as $pF)
            <th rowspan="1">{{$loop->index+1}}</th>

            @endforeach


            @foreach($selfVirtues as $sV)
            <th rowspan="1">{{$loop->index+1}}</th>

            @endforeach

            <th rowspan="1">1</th>
            <th rowspan="1">2</th>
            <th rowspan="1">3</th>

            <th rowspan="1">1</th>
            <th rowspan="1">2</th>
            <th rowspan="1">3</th>
            <th rowspan="1">4</th>
            <th rowspan="1">5</th>
            <th rowspan="1">6</th>

            <th rowspan="1">1</th>
            <th rowspan="1">2</th>
            <th rowspan="1">3</th>
            <th rowspan="1">4</th>
            <th rowspan="1">5</th>
            <th rowspan="1">6</th>
            <th rowspan="1">7</th>
            <th rowspan="1">8</th>
            <th rowspan="1">9</th>
            <th rowspan="1">10</th>
            <th rowspan="1">11</th>
            <th rowspan="1">12</th>



        </tr>
        <tr >
            @foreach($physicalFitness as $pF)
            <th  rowspan="1"><span>{{$pF->activity_name}}</span></th>

            @endforeach


            @foreach($selfVirtues as $sV)
            <th  rowspan="1"><span>{{$sV->activity_name}}</span></th>
            @endforeach
            <th class='rotate' rowspan="1"><span>JSC Result</span></th>
            <th class='rotate' rowspan="1"><span>SSC Result</span></th>
            <th class='rotate' rowspan="1"><span>Last Exam Result</span></th>

            <th class='rotate' rowspan="1"><span>Olympiad</span></th>
            <th class='rotate' rowspan="1"><span>Debate(Bangal and English)</span></th>
            <th class='rotate' rowspan="1"><span>Extempore(Bangal and English)</span></th>
            <th class='rotate' rowspan="1"><span>Recitation(Bangal and English)</span></th>
            <th class='rotate' rowspan="1"><span>Music</span></th>
            <th class='rotate' rowspan="1"><span>Acting</span></th>

            <th class='rotate' rowspan="1"><span>Swimming</span></th>
            <th class='rotate' rowspan="1"><span>Squash</span></th>
            <th class='rotate' rowspan="1"><span>Football</span></th>
            <th class='rotate' rowspan="1"><span>Cricket</span></th>
            <th class='rotate' rowspan="1"><span>Basketball</span></th>
            <th class='rotate' rowspan="1"><span>Volleyball</span></th>
            <th class='rotate' rowspan="1"><span>Lawn Tennis</span></th>
            <th class='rotate' rowspan="1"><span>Indoor  Games</span></th>
            <th class='rotate' rowspan="1"><span>Obstacle  Course</span></th>
            <th class='rotate' rowspan="1"><span>Cross  Country</span></th>
            <th class='rotate' rowspan="1"><span>Cycling</span></th>
            <th class='rotate' rowspan="1"><span>Athletics</span></th>

        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="200" style="text-align: left">{{$student ->batch()->batch_name}}</td>
        </tr>

        </tbody>
    </table>
</div>
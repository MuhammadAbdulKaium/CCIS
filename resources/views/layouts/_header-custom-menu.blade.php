
<style>
    .label-design {
        position: absolute;
        top: 9px;
        right: 7px;
        text-align: center;
        font-size: 9px;
        padding: 2px 3px;
        line-height: .9;
    }
    /* .approval-notification-icon a{
        vertical-align: middle;
        font-size: 25px;
        position: relative;
    }
    .approval-notification-icon a i{
        vertical-align: middle;
        font-size: 25px;
        color: #0b460b;
    }
    .approval-notification-icon a i:hover{
        color: lightgreen;
        transition: 0.3s;
    }
    .notification-badge{
        position: absolute;
        background: red;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: inline-block;
        text-align: center;
        font-weight: bold;
        line-height: 18px;
        font-size: 12px;
        right: -5px;
        top: 3px;
    } */
</style>

<div class="col-md-3" id="user_info" style="float: right">
    <div class="logo-button">
        <ul class="logo-nav logo-nav-right">
            @php
                // user profile
                $user = Auth::user();
                // campus id
                $campusId = session()->get('campus');
                // institute id
                $instituteId = session()->get('institute');

                $numOfApprovalNotifications = numOfApprovalNotifications();
            @endphp

            {{--@role(['admin'])--}}
            {{--<div class="btn-group dropdown-menus-open" style="color: #000 !important;">--}}
            {{--<li><a class="dropdown-toggle-campus" aria-expanded="false" title="Select Campus"><span class=" fa fa-institution"></span></a>--}}
            {{--<ul class="dropdown-menus menu-campus">--}}
            {{--<li><a href="#">Select Campus</a></li>--}}
            {{--@foreach($campusList as $myCampus)--}}
            {{--@php $campus = $myCampus->campus() @endphp--}}
            {{--@if($campus)--}}
            {{--<li class="{{session()->get('campus')==$campus->id?'campus-active':''}}">--}}
            {{--<a class="{{session()->get('campus')==$campus->id?'bg-green-active':''}}" href="/setting/institute/campus/{{$campus->id}}">--}}
            {{--{{$campus->name}}--}}
            {{--</a>--}}
            {{--</li>--}}
            {{--@endif--}}
            {{--@endforeach--}}
            {{--</ul>--}}
            {{--</div>--}}
            {{--@endrole--}}

        


        <!-- Notifications: style can be found in dropdown.less -->
            {{--<li class="dropdown notifications-menu">--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                    {{--<i class="fa fa-bell-o"></i>--}}
                    {{--<span class="label label-warning label-design ">10</span>--}}
                {{--</a>--}}
                {{--<ul class="dropdown-menu">--}}
                    {{--<li class="header">You have 10 notifications</li>--}}
                    {{--<li>--}}
                        {{--<!-- inner menu: contains the actual data -->--}}
                        {{--<ul class="menu">--}}
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--<i class="fa fa-users text-aqua"></i> 5 new members joined today--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--<i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the--}}
                                    {{--page and may cause design problems--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--<i class="fa fa-users text-red"></i> 5 new members joined--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--<i class="fa fa-shopping-cart text-green"></i> 25 sales made--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--<i class="fa fa-user text-red"></i> You changed your username--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li class="footer-view-link"><a href="#">View all</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}



           

            {{--<li><a href="#" class="pro-custom"><img src="{{URL::asset('template-2/img/profile.jpeg')}}" height="40px" width="40px" style="border-radius:50%" alt="profile" class="pro-pic"></a></li>--}}



            {{--user photo--}}
            @php $photo = null; @endphp
            {{--student profile--}}
            @role(['student'])
            @php $photo = $user->student()->singelAttachment("PROFILE_PHOTO"); @endphp
            @endrole
            {{--parent profile--}}
            @role(['parent'])
            {{--@php $photo = $user->parent()->singelAttachment("PROFILE_PHOTO"); @endphp--}}
            @endrole

            <li class="dropdown">
                <a class="btn account dropdown-toggle" data-toggle="dropdown" href="index.html#" aria-expanded="true">
                    @if($photo)
                        <img src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" height="40px" width="40px" style="border-radius:50%" alt="profile" class="pro-pic">
                    @else
                        <img src="{{URL::asset('assets/users/images/user-default.png')}}" height="40px" width="40px" style="border-radius:50%" alt="profile" class="pro-pic">
                    @endif
                    <span class="hidden-xs">{{ Auth::user()->name }} ({{Auth::user()->roles()->first()->display_name}}) </span>
                </a>
                <ul class="dropdown-menu mini-dropdown">
                    @role(['teacher'])
                    <li><a href="{{url('/employee/profile/personal/'.$user->employee()->id)}}"><i class="fa fa-user"></i> Profile</a></li>
                    @endrole
                    @role(['student'])
                    <li><a href="{{url('/student/profile/personal/'.$user->student()->id)}}"><i class="fa fa-user"></i> Profile</a></li>
                    @endrole
                    <li><a href="{{URL::to('changepassword')}}"><i class="fa fa-cog"></i> Change Password</a></li>
                    <li><a href="#"><i class="fa fa-envelope"></i> Messages</a></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> Logout</a></li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </ul>
            </li>



            {{--<li class="dropdown user user-menu">--}}
            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
            {{--@if($photo)--}}
            {{--<img class="user-image" src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" alt="No Image" style="width:30px;height:30px">--}}
            {{--@endif--}}
            {{--<span class="hidden-xs">{{ Auth::user()->name }} ({{Auth::user()->roles()->first()->display_name}}) </span>--}}
            {{--</a>--}}
            {{--<ul class="dropdown-menu">--}}
            {{--<!-- User image -->--}}
            {{--<li class="user-header">--}}
            {{--<img class="img-circle" src="/user/image?name=default.png" alt="NI">--}}
            {{--@if($photo)--}}
            {{--<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">--}}
            {{--@else--}}
            {{--<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">--}}
            {{--@endif--}}
            {{--<p style="font-size: 18px;">{{ Auth::user()->name }} <br/> ({{ Auth::user()->email }})--}}
            {{--</p>--}}
            {{--</li>--}}

            {{--<!-- Menu Footer-->--}}
            {{--<li class="user-footer">--}}
            {{--<div class="pull-left">--}}
            {{--<a class="btn btn-default btn-flat" href="#" style="font-size:12px">Change Password</a>--}}
            {{--</div>--}}
            {{--<div class="pull-right">--}}
            {{--<a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault();--}}
            {{--document.getElementById('logout-form').submit();"style="font-size:12px">Sign out</a>--}}

            {{--</div>--}}
            {{--</li>--}}
            {{--</ul>--}}
            {{--</li>--}}


        </ul>
    </div>
</div>

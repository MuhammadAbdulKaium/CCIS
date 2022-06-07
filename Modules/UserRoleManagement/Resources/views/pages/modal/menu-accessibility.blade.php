<form action="{{url('/userrolemanagement/save/menu-accessibility')}}" method="POST">
    @csrf

    <input type="hidden" name="roleId" value="{{($role)?$role->id:null}}">
    <input type="hidden" name="userId" value="{{($user)?$user->id:null}}">

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Menu permissions for @if($role) {{$role->display_name}} @endif @if($user) {{$user->name}} @endif
        </h4>
    </div>

    <!--modal-header-->
    <div class="modal-body">
        @php
            $modules = $allRoutes->where('parent_uid', null);

            function test($allRoutes, $routes, $permissions, $margin){
                echo '<ul class="list-group" style="margin-left: '.$margin.'px; display: none">';
                foreach ($routes as $route){
                    $checkStatus = (isset($permissions[$route->id]))?"checked":"";

                    if ($route->has_child){
                        echo '<li class="list-group-item"><input class="menu-check" type="checkbox" name="routes[]" value="'.$route->id.'" '.$checkStatus.
                        '> <span class="menu-label">'.$route->label.'</span>
                        <i class="fa fa-chevron-down menu-arrow-down" style="float: right"></i>
                        <i class="fa fa-chevron-up menu-arrow-up" style="float: right; display:none"></i></li>';

                        $nextRoutes = $allRoutes->where('parent_uid', $route->uid);
                        test($allRoutes, $nextRoutes, $permissions, $margin);
                    } else{
                        echo '<li class="list-group-item"><input class="menu-check" type="checkbox" name="routes[]" value="'.$route->id.'" '.$checkStatus.
                        '> <span class="menu-label">'.$route->label.'</span></li>';
                    }
                }
                echo '</ul>';
            }
        @endphp

        <div style="margin-left: 15px; margin-bottom: 10px">
            <input type="checkbox" class="select-all-check"> Select All
        </div>

        <ul class="list-group">
            @foreach($modules as $module)
                @php
                    if ($rolePermissions){
                        $checkStatus = (isset($rolePermissions[$module->id]))?"checked":"";
                    } elseif ($userPermissions){
                        $checkStatus = (isset($userPermissions[$module->id]))?"checked":"";
                    }
                @endphp

                <li class="list-group-item"><input class="menu-check" type="checkbox" name="routes[]" value="{{$module->id}}" {{$checkStatus}}>
                    <span class="menu-label">{{$module->label}}</span>
                    <i class="fa fa-chevron-down menu-arrow-down" style="float: right"></i>
                    <i class="fa fa-chevron-up menu-arrow-up" style="float: right; display:none"></i></li>
                @php
                    $routes = $allRoutes->where('parent_uid', $module->uid);
                    if ($rolePermissions){
                        test($allRoutes, $routes, $rolePermissions, 50);
                    } elseif ($userPermissions){
                        test($allRoutes, $routes, $userPermissions, 50);
                    }
                @endphp
            @endforeach
        </ul>
    </div>

    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-info pull-right">Save</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('.menu-arrow-down').click(function (){
            var next = $(this).parent().next();

            if (next[0].className == 'list-group'){
                $(this).hide();
                $(this).next().show();
                next.slideToggle();
            }
        });

        $('.menu-arrow-up').click(function (){
            var next = $(this).parent().next();

            if (next[0].className == 'list-group'){
                $(this).hide();
                $(this).prev().show();
                next.slideToggle();
            }
        });

        $('.menu-check').click(function (){
            var ul = $(this).parent().parent();
            var next = $(this).parent().next();

            if (next[0]){
                if (next[0].className == 'list-group'){
                    if ($(this).is(":checked")){
                        next.find('.menu-check').prop('checked', true);
                    }else{
                        next.find('.menu-check').prop('checked', false);
                    }
                }
            }

            if ($(this).is(":checked")){
                function check(input){
                    var prevInput = input.parent().parent().prev().find('.menu-check');

                    if (prevInput.length > 0){
                        prevInput.prop('checked', true);
                        check(prevInput);
                    }

                    return;
                }

                check($(this));
            }
        });

        $('.menu-label').click(function (){
            $(this).prev().click();
        });

        $('.select-all-check').click(function (){
            if ($(this).is(":checked")){
                $('.menu-check').prop('checked', true);
            }else{
                $('.menu-check').prop('checked', false);
            }
        });
    });
</script>

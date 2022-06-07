<style>
    .my-tooltip {
        background: #fff;
        position: absolute;
        min-width: 300px;
        height: auto;
        z-index: 10;
        display: none;
        padding: 8px;
        box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
    }

</style>
<div class="box box-solid">
    <div class="box-header">
        <h4><i class="fa fa-plus-square"></i> List</h4>
    </div>
    <div class="box-body">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>
                        <input class="form-check-input all_check" type="checkbox" value=""> All
                    </th>
                    <th>Day</th>
                    <th>Date</th>
                   
                    @php
                        $rows = 8;
                        $cols = 10;
                        
                    @endphp
                    @for ($i = 1; $i <= $cols; $i++)
                        <th>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="slots[]"
                                    value="{{ $i }}" id="slot">
                                <label class="form-check-label" for="slot">
                                    Slot{{ $i }}
                                </label>
                            </div>
                        </th>
                    @endfor
                    <th>Costing</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($period as $date)
                @php
                    $totalRowCost = 0;
                @endphp
              
                    <tr>
                        <td><input type="checkbox" class="checkbox date-checkbox" name="dates[]"
                                value="{{ $date->format('d/m/Y') }}"></td>
                        <td>{{ $date->format('l') }}</td>
                        <td>{{ $date->format('d/m/Y') }}</td>
                       
                        @for ($i = 1; $i <= 10; $i++)
                            @php
                                $schedule = $previousSchedules->where('date', $date->format('Y-m-d'))->firstWhere('slot', $i);
                            @endphp

                            @if ($schedule)
                                @php
                                    $menu = $foodMenus->firstWhere('id', $schedule->menu_id);
                                    $menuItems = $menu->menu_items ? json_decode($menu->menu_items, 1) : [];
                                @endphp
                                <td>
                                    @php
                                        $totalValue = 0;
                                        $totalColCost = 0;
                                        
                                    @endphp
                                    @forelse ($menuItems as $item)
                                        @php
                                            $foodMenuItem = $foodMenuItems->firstWhere('id', $item['id']);
                                            if ($foodMenuItem) {
                                                $totalColCost += $foodMenuItem->cost * $item['qty'];
                                            }
                                        @endphp
                                    @endforeach
                                    <span class="menu-name">
                                        <span>Time: <span
                                                class="text-success">{{ Carbon\Carbon::parse($schedule->time)->format('g:i A') }}</span></span><br>
                                        <span class="text-danger">{{ $menu->menu_name }}</span><br>
                                        <span data-toggle="modal" data-target="#totalPositions"
                                            style="cursor:pointer">Total Persons: <span
                                                class="text-primary ">{{ $schedule->persons }}</span></span><br>
                                        <span>Total Costing: {{ $totalColCost }} </span>
                                        @php
                                            $totalRowCost += $totalColCost;
                                        @endphp
                                    </span>

                                    <div class="my-tooltip">
                                        <h5><b># {{ $menu->menu_name }}</b></h5>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Qty</th>
                                                    <th>H. Value</th>
                                                    <th>Costing</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalValue = 0;
                                                    $totalCost = 0;
                                                @endphp
                                                @forelse ($menuItems as $item)
                                                    @php
                                                        $foodMenuItem = $foodMenuItems->firstWhere('id', $item['id']);
                                                        if ($foodMenuItem) {
                                                            $uom = $uoms->firstWhere('id', $foodMenuItem->uom_id);
                                                            $totalValue += $foodMenuItem->value * $item['qty'];
                                                            $totalCost += $foodMenuItem->cost * $item['qty'];
                                                        }
                                                    @endphp
                                                    @if ($foodMenuItem)
                                                        <tr>
                                                            <td>{{ $foodMenuItem->item_name }}</td>
                                                            <td>{{ $item['qty'] }}
                                                                {{ $uom ? $uom->symbol_name : '' }}</td>
                                                            <td>{{ $foodMenuItem->value * $item['qty'] }}</td>
                                                            <td>{{ $foodMenuItem->cost * $item['qty'] }}</td>
                                                        </tr>
                                                    @endif
                                                @empty
                                                    <tr>
                                                        <td colspan="5" style="text-align: center">No Items assigned
                                                        </td>
                                                    </tr>
                                                @endforelse
                                                <tr>
                                                    <td></td>
                                                    <td>Total:</td>
                                                    <td>{{ $totalValue }}</td>
                                                    <td>{{ $totalCost }}</td>
                                                </tr>
                                            </tbody>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endfor
                        <td>{{ $totalRowCost }}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // $(document).ready(function () {


    //     $('.menu-name').mouseover(function () {

    //         $(this).parent().find('.my-tooltip').css('display', 'block'); 

    //     });
    //     $('.menu-name').mouseout(function () {
    //         $(this).parent().find('.my-tooltip').css('display', 'none');            
    //     });


    // });
</script>

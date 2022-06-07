<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Schedule Table</title>
    <style>
        .clearfix {
            overflow: auto;
        }


        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        img {
            width: 100%;
        }

        .header {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .logo {
            width: 10%;
            float: left;
        }

        .headline {
            width: 82%;
            float: right;
            padding: 0 20px;
            text-align: left;
            /* margin-top: 30px; */
        }

        h1,
        h3,
        h4,
        p {
            margin: 5px 0;
        }

        h2 {
            margin: 10px 0;
        }

        table,
        td,
        th {
            border: 1px solid black;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .footer-bottom {
            position: fixed;
            width: 100%;
            height: 100%;
            bottom: -100%;
        }

        .footer-bottom .left {
            width: 80%;
            float: left;
            color: rgb(145, 32, 32);
            font-size: 20px;
            font-weight: 500;
        }

        .footer-bottom .right {
            width: 20%;
            float: right;
            text-align: right;
            color: rgb(145, 32, 32);
            font-size: 20px;
            font-weight: 500;
        }

        .footer-bottom .right:after {
            content: counter(page);
        }

        caption {
            margin-bottom: 10px;
        }

        .singleFoodMenu {
            width: 48%;
            padding: 0 10px;
            display: inline-block;
        }

    </style>
</head>

<body>
    <div class="header clearfix">
        <div class="logo">
            <img src="{{ public_path('assets/users/images/' . $institute->logo) }}" alt="logo">
        </div>
        <div class="headline">
            <h2 class="cadet_Name">{{ $institute->institute_name }}({{ $campus->name }})</h2>
            <p>{{ $institute->address1 }} </p>
            <p>
                <strong>Phone:</strong>{{ $institute->phone }} <strong>Email:</strong> <a
                    href="{{ $institute->email }}" target="_blank">{{ $institute->email }}</a> <strong>Web:</strong>
                <a href=" {{ $institute->website }}" target="_blank"> {{ $institute->website }}</a>
            </p>
        </div>
    </div>

    <div class="clearfix" style="margin-top:50px;">
        @foreach ($singleFoodMenus as $menu)
            @php
                $totalValue = 0;
                $totalCost = 0;
            @endphp
            <div class="singleFoodMenu clearfix ">

                @if ($menu->menu_items)
                    <caption>
                        <b>{{ $menu->menu_name }}</span></b>
                    </caption>
                    @php
                        $menuItems = $menu->menu_items ? json_decode($menu->menu_items, 1) : [];
                    @endphp
                    <table>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>H.value</th>
                            <th>Costing</th>
                        </tr>
                        @foreach ($menuItems as $item)
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
                                    <td>{{ $item['qty'] }} {{ $uom ? $uom->symbol_name : '' }}</td>
                                    <td>{{ $foodMenuItem->value * $item['qty'] }}</td>
                                    <td>{{ $foodMenuItem->cost * $item['qty'] }}</td>
                                </tr>
                            @endif
                        @endforeach

                        <tr>
                            <th colspan="2" style="text-align: right; padding-right:10px;">Total</th>
                            <th>{{ $totalValue }}</th>
                            <th>{{ $totalCost }}</th>
                        </tr>
                    </table>
                @endif
            </div>
        @endforeach
    </div>
    <div class="clearfix"></div>
    <table>
        <caption><b>Food Menu Schedules (slot 1 - 5)</b></caption>
        <thead>
            <tr>
                <th>Day</th>
                <th>Date</th>
                @for ($i = 1; $i <= 5; $i++)
                    <th>Slot{{ $i }}</th>
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
                    <td>{{ $date->format('l') }}</td>
                    <td>{{ $date->format('d/m/Y') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $schedule = $previousSchedules->where('date', $date->format('Y-m-d'))->firstWhere('slot', $i);
                        @endphp

                        @if ($schedule)
                            @php
                                $totalColCost = 0;
                                $menu = $foodMenus->firstWhere('id', $schedule->menu_id);
                                $menuItems = $menu->menu_items ? json_decode($menu->menu_items, 1) : [];
                            @endphp

                            <td>
                                @forelse ($menuItems as $item)
                                    @php
                                        $foodMenuItem = $foodMenuItems->firstWhere('id', $item['id']);
                                        if ($foodMenuItem) {
                                            $totalColCost += $foodMenuItem->cost * $item['qty'];
                                        }
                                    @endphp
                                @endforeach
                                <span>Time: {{ Carbon\Carbon::parse($schedule->time)->format('g:i A') }}</span><br>
                                <span>{{ $menu->menu_name }}</span><br>
                                <span>Total Persons: {{ $schedule->persons }}</span><br>
                                <span>Total Costing: {{ $totalColCost }} </span><br>
                                @php
                                    $totalRowCost += $totalColCost;
                                @endphp
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

    <table>
        <caption><b>Food Menu Schedules (slot 6 - 10)</b></caption>
        <thead>
            <tr>
                <th>Day</th>
                <th>Date</th>
                @for ($i = 6; $i <= 10; $i++)
                    <th>Slot{{ $i }}</th>
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
                    <td>{{ $date->format('l') }}</td>
                    <td>{{ $date->format('d/m/Y') }}</td>
                    @for ($i = 6; $i <= 10; $i++)
                        @php
                            $schedule = $previousSchedules->where('date', $date->format('Y-m-d'))->firstWhere('slot', $i);
                        @endphp

                        @if ($schedule)
                            @php
                                $totalColCost = 0;
                                $menu = $foodMenus->firstWhere('id', $schedule->menu_id);
                                $menuItems = $menu->menu_items ? json_decode($menu->menu_items, 1) : [];
                            @endphp
                            <td>
                                @forelse ($menuItems as $item)
                                    @php
                                        $foodMenuItem = $foodMenuItems->firstWhere('id', $item['id']);
                                        if ($foodMenuItem) {
                                            $totalColCost += $foodMenuItem->cost * $item['qty'];
                                        }
                                    @endphp
                                @endforeach
                                <span>Time: {{ Carbon\Carbon::parse($schedule->time)->format('g:i A') }}</span><br>
                                <span>{{ $menu->menu_name }}</span><br>
                                <span>Total Persons: {{ $schedule->persons }}</span><br>
                                <span>Total Costing: {{ $totalColCost }} </span><br>
                                @php
                                    $totalRowCost += $totalColCost;
                                @endphp
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
    <div class="footer-bottom clearfix">
        <p class="left">
            Printed from {{ $institute->institute_alias }} ICT {{ Auth::user()->username }}, on
            {{ date('Y/m/d H:i:s') }}
        </p>
        <p class="right">
            Page 1 of
        </p>
    </div>
</body>

</html>

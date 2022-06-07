<?php

namespace Modules\UserRoleManagement\Entities\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RouteImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        if(!empty($row[0])){
            return ([
                'type'              => $row[0],
                'priority'          => $row[1],
                'label'             => $row[2],
                'route_link'        => $row[3],
                'route_method'      => $row[4],
                'route_name'        => $row[5],
                'unique_id'         => $row[6],
                'parent_uid'        => $row[7],
                'has_child'         => $row[8],
                'order_no'          => $row[9],
            ]);
        }
    }
}

<?php

namespace Modules\Student\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Student\Entities\UserType;

class StudentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();

        UserType::insert([
            ['title' => 'Cadet', 'created_by' => 1],
            ['title' => 'HR/FM', 'created_by' => 1]
        ]);

        // $this->call("Modules\Student\Database\Seeders\StudnetInfoTableSeeder");
    }
}

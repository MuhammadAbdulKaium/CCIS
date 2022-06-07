<?php

use Illuminate\Database\Seeder;
use Modules\Academics\Database\Seeders\AcademicsDatabaseSeeder;
use Modules\Student\Database\Seeders\StudentDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $this->call(AcademicsDatabaseSeeder::class);
        $this->call(StudentDatabaseSeeder::class);
    }
}

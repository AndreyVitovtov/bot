
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder {

    /**
     * Run the ads_admin.roles seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('roles')->insert([
            ["id" => "1","name" => "admin"],
            ["id" => "2","name" => "moderator"]
        ]);
    }
}

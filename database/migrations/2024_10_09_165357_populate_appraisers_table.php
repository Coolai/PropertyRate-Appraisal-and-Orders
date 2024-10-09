<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('appraisers')) {
            $appraisers_file_path = public_path('appraisers.csv');
            $appraisers_file = file($appraisers_file_path);

            foreach ($appraisers_file as $line) {
                $data = str_getcsv($line);
                $appraiser_user_id = $data[0];
                $rank = $data[1];
                $zip_code = $data[2];
                $county = $data[3];

                DB::table('appraisers')->insert([
                    'appraiser_user_id' => $appraiser_user_id,
                    'rank' => $rank,
                    'zip_code' => $zip_code,
                    'county' => $county
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('appraisers')) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::table('appraisers')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
};

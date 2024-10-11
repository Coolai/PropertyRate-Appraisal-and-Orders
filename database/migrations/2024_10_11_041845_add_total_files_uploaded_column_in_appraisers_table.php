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
        Schema::table('appraisers', function (Blueprint $table) {
            $table->integer('total_files_uploaded')->nullable()->after('county');
        });

        $sample_tfu = [6,5,211,86,14,14,6,39,8,5,113,8,1,1,13,26,21,3,4,4,7,45,7,219,13,79,1,63,1,3,4,2,9,16,176,189,13,5,275,12,2,1,20,244,184,2,5,30,180,28,5,1,8,15,7,82,1,125,29,1,149,8,55,1,6,2,292,2,2,1,4,20,1,184,141,7,2,6,25,119,6,14,40,1,285,1,2,1,13,1,167,5,9,4,51,3,4,4,1,1,9,88,1,112,1,1,47,81,3,1,17,1,114,32,196,5,41,4,81,1,3,1,72,7,2,4,1,3,166,3,1,9,85,1,18,6,1,2,6,314,85,2,17,14,10,3,1,8,5,8,2,1,8,1,12,1,19,123,127,2,4,1,4,169,6,54,19,987,3,2,2,1,2,1,7,18,10,1,2,28,291,398,35,86,6,1,6,23,37,431,3,1,1,9,9,23,1,26,1,1,6,39,1,1,2,4,1,8,4,2,2,207,1,2,7,2,2,8,18,227,30,3,8,9,10,9,2,2,3,203,3,28,4,2,1,65,234,4,5,1,10,4,2,1,1,6,4,2,470,1,368,32,10,7,2,22,1,2,3,265,13,30,14,5,24,1,1,1,1,2,1,2,6,1,9,14,1,28,1,28,1,6,1,6,1,1,1,2,3,4,2,1,1,2,200,1,1,1,9,10,4,1,1,4,1,6,1,337,22,262,1,1,13,10,10,1,1,413,1,2,1,12,93,2,4,2,301,60,241,3,10,313,44,2,2,1,1,7,61,3,153,3,178,260,11,11,6,9,2,6,6,1,2,41,5,7,10,1,4,9,5,3,2,3,1,21,2,1,40,1,2,3,5,1,53,1,2,3,3,1,6,1,38,4,85,6,3,1,4,1,5,2,8,1,23,10,18,2,55,2,1,9,24,36,3,109,5,43,9,6,12,1,27,1,1,2,1,8,21,50,2,1,1,5,1,107,314,13,3,2,7,1,14,2,21,1,2,10,1,1,3,2,3,47,7,2,8,1,14,2,33,9,194,6,23,1,15,1,9,1,3,3,3,7,9,2,1,1,1,1,1,191,1,10,1,281,2,1,4,1,2,8,3,49,1,8,2,1,1,6,66,25,11,2,3,1,17,3,13,29,1,2,1,1,1,3,1,2,3,1,8,1,1,1,1,44,8,2,63,115,1,11,1,3,1,1,1,1,1,22,2,1,1,1,1,17,3,5,4,2,2,];

        foreach ($sample_tfu as $key => $tfu) {
            $id = ($key + 1);
            DB::table('appraisers')->where('id', $id)->update([
                'total_files_uploaded' => $tfu
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appraisers', function (Blueprint $table) {
            $table->dropColumn('total_files_uploaded');
        });
    }
};
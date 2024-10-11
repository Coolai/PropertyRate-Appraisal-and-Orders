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
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedBigInteger('appraiser_id')->nullable()->after('status');
                $table->foreign('appraiser_id')->references('id')->on('appraisers')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                $table->dropForeign('orders_appraiser_id_foreign');
                $table->dropColumn('appraiser_id');
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            });
        }
    }
};

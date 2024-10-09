<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appraisers', function (Blueprint $table) {
            $table->id();

            $table->integer('appraiser_user_id');
            $table->tinyInteger('rank')->default(0);
            $table->integer('zip_code'); // probably convert this to a string (varchar)
            $table->string('county');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appraisers');
    }
};

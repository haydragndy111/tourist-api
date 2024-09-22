<?php

use App\Models\Tour;
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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guide_id');
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('program_id');
            $table->tinyInteger('status')->default(Tour::STATUS_CLOSED);
            $table->string('name');
            $table->float('price');
            $table->integer('number');
            $table->timestamps();

            $table->foreign('guide_id')->references('id')->on('guides')->cascadeOnDelete();
            $table->foreign('driver_id')->references('id')->on('drivers')->cascadeOnDelete();
            $table->foreign('program_id')->references('id')->on('programs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};

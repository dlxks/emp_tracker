<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quarterlies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')
                ->constrained('records')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('quarter');
            $table->bigInteger('employed')->nullable();
            $table->bigInteger('unemployed')->nullable();
            $table->bigInteger('untracked')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quarterlies');
    }
};

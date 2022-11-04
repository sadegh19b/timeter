<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('times');
    }
};

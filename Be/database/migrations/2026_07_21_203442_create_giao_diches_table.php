<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('giao_diches', function (Blueprint $table) {
            $table->id();
            $table->double('creditAmount');
            $table->string('description');
            $table->string('refNo');
            $table->integer('id_hoa_don')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giao_diches');
    }
};

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
        Schema::create('tbl_responden', function (Blueprint $table) {
            $table->id();
            $table->string('nama_responden');
            $table->string('pt')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('email')->nullable();
            $table->integer('id_layanan')->nullable();
            $table->string('nama_layanan')->nullable();
            $table->string('saran')->nullable();
            $table->datetime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_responden');
    }
};

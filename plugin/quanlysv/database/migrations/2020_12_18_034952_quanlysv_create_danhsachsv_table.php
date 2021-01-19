<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class QuanlysvCreateDanhsachsvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danhsachsvs', function (Blueprint $table) {
            $table->id();
            $table->string('ma_sv', 255)->default('');
            $table->string('ma_lop', 255)->default('');
            // $table->foreign('ma_lop')->references('ma_lop')->on('danhsachlops');
            $table->string('ten_sv', 255)->default('');
            $table->string('ho_sv', 255)->default('');
            $table->date('ngay_sinh')->nullable();
            $table->string('dia_chi', 255)->nullable();
            $table->string('status', 60)->default('published');
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
        Schema::dropIfExists('danhsachsvs');
    }
}

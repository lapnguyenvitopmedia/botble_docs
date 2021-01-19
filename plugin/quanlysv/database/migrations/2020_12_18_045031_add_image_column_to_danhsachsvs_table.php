<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageColumnToDanhsachsvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('danhsachsvs', function (Blueprint $table) {
            $table->string('gioi_tinh', 255);
            $table->string('anh', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('danhsachsvs', function (Blueprint $table) {
            $table->dropColumn('gioi_tinh');
            $table->dropColumn('anh');
        });
    }
}

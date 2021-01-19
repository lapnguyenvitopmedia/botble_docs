<?php

namespace Botble\Quanlysv;

use Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('quanlysvs');

        //danh sach lop
        Schema::dropIfExists('danhsachlops');

        //danh sach sv
        Schema::dropIfExists('danhsachsvs');
    }
}

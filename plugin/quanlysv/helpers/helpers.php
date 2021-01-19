<?php

use Botble\Quanlysv\Repositories\Interfaces\DanhsachsvInterface;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachlopInterface;

if (!function_exists('get_danhsachSV')) {
    function get_danhsachSV()
    {
        return app(DanhsachsvInterface::class)->getAllSV();
    }
}

if (!function_exists('get_danhsachLop')) {
    function get_danhsachLop()
    {
        return app(DanhsachlopInterface::class)->getAll();
    }
}

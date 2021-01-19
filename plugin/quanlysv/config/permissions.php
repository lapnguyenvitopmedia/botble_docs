<?php

return [
    [
        'name' => 'Quanlysvs',
        'flag' => 'quanlysv.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'quanlysv.create',
        'parent_flag' => 'quanlysv.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'quanlysv.edit',
        'parent_flag' => 'quanlysv.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'quanlysv.destroy',
        'parent_flag' => 'quanlysv.index',
    ],

    //danh sach lop
    [
        'name' => 'Danhsachlops',
        'flag' => 'danhsachlop.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'danhsachlop.create',
        'parent_flag' => 'danhsachlop.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'danhsachlop.edit',
        'parent_flag' => 'danhsachlop.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'danhsachlop.destroy',
        'parent_flag' => 'danhsachlop.index',
    ],

    //danh sach sv
    [
        'name' => 'Danhsachsvs',
        'flag' => 'danhsachsv.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'danhsachsv.create',
        'parent_flag' => 'danhsachsv.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'danhsachsv.edit',
        'parent_flag' => 'danhsachsv.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'danhsachsv.destroy',
        'parent_flag' => 'danhsachsv.index',
    ],
];

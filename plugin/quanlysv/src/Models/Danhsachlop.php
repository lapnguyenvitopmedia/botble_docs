<?php

namespace Botble\Quanlysv\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Danhsachlop extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'danhsachlops';

    /**
     * @var array
     */
    protected $fillable = [
        'ma_lop',
        'chuyen_nganh',
        'status',
    ];

    public function danhsachsvs()
    {
        return $this->hasMany(Danhsachsv::class, 'id', 'ma_lop');
    }

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}

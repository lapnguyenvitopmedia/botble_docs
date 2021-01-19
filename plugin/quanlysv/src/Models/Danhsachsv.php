<?php

namespace Botble\Quanlysv\Models;

use Botble\Base\Traits\EnumCastable;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use RvMedia;
use Html;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class Danhsachsv extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'danhsachsvs';

    /**
     * @var array
     */
    protected $fillable = [
        'ma_sv',
        'ma_lop',
        'ten_sv',
        'ho_sv',
        'ngay_sinh',
        'dia_chi',
        'gioi_tinh',
        'anh',
        'status',
    ];
    // public function getFullNameAttribute()
    // {
    //     return "{$this->ten_sv} {$this->ho_sv}";
    // }

    public function danhsachlops(): BelongsTo
    {
        return $this->belongsTo(Danhsachlop::class, 'ma_lop', 'id');
    }


    //ma_lop Accessor
    // public function getMaLopAttribute()
    // {
    //     return $this->danhsachlops->ma_lop;
    // }

    //gioi_tinh Accessor
    public function getGioiTinhAttribute($value)
    {
        switch ($value) {
            case 0:
                return 'Nam';
            case 1:
                return 'Nữ';
        }
    }

    //anh Accessor
    public function getAnhAttribute($value)
    {
        $hinh_anh = Html::image(
            RvMedia::getImageUrl($value, 'thumb', false, RvMedia::getDefaultImage()),
            $value,
            ['width' => 50]
        );
        return $hinh_anh;
    }

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];
}

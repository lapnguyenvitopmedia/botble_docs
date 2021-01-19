<?php

namespace Botble\Quanlysv\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Quanlysv\Http\Requests\DanhsachsvRequest;
use Botble\Quanlysv\Models\Danhsachsv;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachlopInterface;

class DanhsachsvForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $lops = app(DanhsachlopInterface::class)->pluck('ma_lop','id');
        // $lops = [0 => trans('plugins/quanlysv::danhsachlops.select_class')] + $lops;
        $lops = [0 => '--Chọn lớp--'] + $lops;

        $this
            ->setupModel(new Danhsachsv)
            ->setValidatorClass(DanhsachsvRequest::class)
            ->withCustomFields()
            ->add('ma_sv', 'text', [
                // 'label'      => trans('core/base::forms.name'),
                'label'      => 'Mã Sinh viên',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    // 'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'placeholder'  => 'Nhập Mã sinh viên',
                    'data-counter' => 120,
                ],
            ])
            ->add('ma_lop', 'customSelect', [
                'label'      => 'Lớp',
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => $lops,
            ])
            ->add('ten_sv', 'text', [
                'label'      => 'Tên',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Nhập Tên sinh viên',
                    'data-counter' => 120,
                ],
            ])
            ->add('ho_sv', 'text', [
                'label'      => 'Họ',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Nhập Họ sinh viên',
                    'data-counter' => 120,
                ],
            ])
            ->add('gioi_tinh', 'customSelect', [
                'label'      => 'Giới tính',
                'choices'    => ['Nam','Nữ'],
            ])
            ->add('ngay_sinh', 'text', [
                'label'      => 'Ngày sinh',
                'attr'       => [
                    'class' => 'form-control datepicker',
                    'data-date-format' => config('core.base.general.date_format.js.date'),
                ],
            ])
            ->add('dia_chi', 'text', [
                'label'      => 'Địa chỉ',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'placeholder'  => 'Nhập địa chỉ',
                    'data-counter' => 120,
                ],
            ])
            // ->add('status', 'customSelect', [
            //     'label'      => trans('core/base::tables.status'),
            //     'label_attr' => ['class' => 'control-label required'],
            //     'attr'       => [
            //         'class' => 'form-control select-full',
            //     ],
            //     'choices'    => BaseStatusEnum::labels(),
            // ])
            ->add('anh', 'mediaImage', [
                'label'      => 'Ảnh',
                'label-attr' => ['class'=>'control-label'],
            ])
            ->setBreakFieldPoint('anh');
    }
}

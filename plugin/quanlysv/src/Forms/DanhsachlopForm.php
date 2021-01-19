<?php

namespace Botble\Quanlysv\Forms;

use Botble\Base\Forms\FormAbstract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Quanlysv\Http\Requests\DanhsachlopRequest;
use Botble\Quanlysv\Models\Danhsachlop;

class DanhsachlopForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $this
            ->setupModel(new Danhsachlop)
            ->setValidatorClass(DanhsachlopRequest::class)
            ->withCustomFields()
            ->add('ma_lop', 'text', [
                // 'label'      => trans('core/base::forms.name'),
                'label'      => 'Mã lớp',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    // 'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'placeholder'  => 'Nhập Mã lớp',
                    'data-counter' => 120,
                ],
            ])->add('chuyen_nganh', 'text', [
                'label'      => 'Chuyên Ngành',
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => 'Nhập Chuyên ngành',
                    'data-counter' => 120,
                ],
            ])
            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'form-control select-full',
                ],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->setBreakFieldPoint('status');
    }
}

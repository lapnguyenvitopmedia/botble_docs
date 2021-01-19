<?php

namespace Botble\Quanlysv\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DanhsachsvRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ma_sv'   => 'required',
            'ma_lop'   => 'required|not_in:0',
            'ten_sv'   => 'required',
            'ho_sv'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}

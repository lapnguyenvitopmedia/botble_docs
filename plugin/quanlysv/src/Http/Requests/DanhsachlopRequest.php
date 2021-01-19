<?php

namespace Botble\Quanlysv\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DanhsachlopRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ma_lop'   => 'required',
            'chuyen_nganh'   => 'required',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}

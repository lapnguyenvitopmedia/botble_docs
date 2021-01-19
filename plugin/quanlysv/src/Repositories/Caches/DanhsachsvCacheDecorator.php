<?php

namespace Botble\Quanlysv\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachsvInterface;

class DanhsachsvCacheDecorator extends CacheAbstractDecorator implements DanhsachsvInterface
{
    public function getAllSV()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

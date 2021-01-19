<?php

namespace Botble\Quanlysv\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachlopInterface;

class DanhsachlopCacheDecorator extends CacheAbstractDecorator implements DanhsachlopInterface
{
    public function getAll()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}

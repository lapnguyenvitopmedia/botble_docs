<?php

namespace Botble\Quanlysv\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachlopInterface;

class DanhsachlopRepository extends RepositoriesAbstract implements DanhsachlopInterface
{
    public function getAll(){
        return $this->model->get();
    }
}

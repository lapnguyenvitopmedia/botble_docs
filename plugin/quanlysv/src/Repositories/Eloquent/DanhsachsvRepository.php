<?php

namespace Botble\Quanlysv\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachsvInterface;

class DanhsachsvRepository extends RepositoriesAbstract implements DanhsachsvInterface
{
    public function getAllSV(){
        return $this->model->get();
    }
}

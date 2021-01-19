<?php

namespace Botble\Quanlysv\Tables;

use Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachsvInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Botble\Quanlysv\Models\Danhsachsv;
use Html;

class DanhsachsvTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * DanhsachsvTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param DanhsachsvInterface $danhsachsvRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, DanhsachsvInterface $danhsachsvRepository)
    {
        $this->repository = $danhsachsvRepository;
        $this->setOption('id', 'plugins-danhsachsv-table');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['danhsachsv.edit', 'danhsachsv.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    //filter dssv theo lớp
    public function filterByLop($lop_id)
    {
        $filterRequest = [
            'filter_table_id' => 'plugins-danhsachsv-table',
            'class' => 'Botble\Quanlysv\Tables\DanhsachsvTable',
            'filter_columns' => ['danhsachlops.ma_lop'],
            'filter_operators' => ['='],
            'filter_values' => [$lop_id]
        ];
        return $filterRequest;
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('ma_sv', function ($item) {
                if (!Auth::user()->hasPermission('danhsachsv.edit')) {
                    return $item->ma_sv;
                }
                return Html::link(route('danhsachsv.edit', $item->id), $item->ma_sv);
            })
            // ->editColumn('ma_lop', function ($item) {
            //     if (!Auth::user()->hasPermission('danhsachsv.edit')) {
            //         return $item->danhsachlops->ma_lop;
            //     }
            //     return Html::link(route('danhsachsv.index', $this->filterByLop($item->id)), $item->danhsachlops->ma_lop);

            //     // return Html::link(route('danhsachlop.edit', $item->ma_lop), $item->ma_lop);
            // })
            ->editColumn('gioi_tinh', function ($item) {
                return $item->gioi_tinh;
            })
            ->editColumn('anh', function ($item) {
                return $item->anh;
            })
            ->editColumn('ngay_sinh', function ($item) {
                return date_from_database($item->ngay_sinh, 'd/m/Y');
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return $this->getOperations('danhsachsv.edit', 'danhsachsv.destroy', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $select = [
            'danhsachsvs.id',
            'danhsachsvs.anh',
            'danhsachsvs.ma_sv',
            'danhsachsvs.ma_lop',
            'danhsachsvs.ten_sv',
            'danhsachsvs.ho_sv',
            'danhsachsvs.ngay_sinh',
            'danhsachsvs.dia_chi',
            'danhsachsvs.gioi_tinh',
            'danhsachsvs.created_at',
            'danhsachsvs.status',
        ];

        $query = $model
            ->with([
                'danhsachlops' => function ($query) {
                    $query->select(['danhsachlops.id', 'danhsachlops.ma_lop','danhsachlops.chuyen_nganh']);
                },
            ])
            // ->join('danhsachlops', 'danhsachlops.id', '=', 'danhsachsvs.ma_lop')
            ->select($select);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id' => [
                'name'  => 'danhsachsvs.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'anh' => [
                'name'  => 'danhsachsvs.anh',
                'title' => 'Ảnh',
                'width' => '70px',
                'class' => 'no-sort',
            ],
            'ma_sv' => [
                'name'  => 'danhsachsvs.ma_sv',
                // 'title' => trans('core/base::tables.name'),
                'title' => 'MSV',
                'class' => 'text-left',
            ],
            'danhsachlops.ma_lop' => [
                'name'  => 'danhsachlops.ma_lop',
                'title' => 'Lớp',
                'class' => 'text-left',
            ],
            'ho_sv' => [
                'name'  => 'danhsachsvs.ho_sv',
                'title' => 'Họ',
                'class' => 'text-left',
                'width' => '50px',
            ],
            'ten_sv' => [
                'name'  => 'danhsachsvs.ten_sv',
                'title' => 'Tên',
                'class' => 'text-left',
            ],
            'gioi_tinh' => [
                'name'  => 'danhsachsvs.gioi_tinh',
                'title' => 'Giới tính',
                'class' => 'text-left',
            ],
            'ngay_sinh' => [
                'name'  => 'danhsachsvs.ngay_sinh',
                'title' => 'Ngày sinh',
                'class' => 'text-left',
                'width' => '70px',
            ],
            'danhsachlops.chuyen_nganh' => [
                'name'  => 'danhsachlops.chuyen_nganh',
                'title' => 'Chuyên ngành',
                'class' => 'text-left',
            ],
            // 'dia_chi' => [
            //     'name'  => 'danhsachlops.dia_chi',
            //     'title' => 'Địa chỉ',
            //     'class' => 'text-left',
            // ],
            // 'created_at' => [
            //     'name'  => 'danhsachsvs.created_at',
            //     'title' => trans('core/base::tables.created_at'),
            //     'width' => '100px',
            // ],
            // 'status' => [
            //     'name'  => 'danhsachsvs.status',
            //     'title' => trans('core/base::tables.status'),
            //     'width' => '100px',
            // ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('danhsachsv.create'), 'danhsachsv.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Danhsachsv::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('danhsachsv.deletes'), 'danhsachsv.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'danhsachsvs.ma_sv' => [
                // 'title'    => trans('core/base::tables.ma_sv'),
                'title'    => 'Mã SV',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachlops.ma_lop' => [
                'title'    => 'Mã Lớp',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachsvs.ten_sv' => [
                'title'    => 'Tên SV',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachsvs.ho_sv' => [
                'title'    => 'Họ SV',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachsvs.ngay_sinh' => [
                'title'    => 'Ngày Sinh',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachsvs.dia_chi' => [
                'title'    => 'Địa Chỉ',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            // 'danhsachsvs.status' => [
            //     'title'    => trans('core/base::tables.status'),
            //     'type'     => 'select',
            //     'choices'  => BaseStatusEnum::labels(),
            //     'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            // ],
            // 'danhsachsvs.created_at' => [
            //     'title' => trans('core/base::tables.created_at'),
            //     'type'  => 'date',
            // ],
        ];
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        // return $this->getBulkChanges();
        return [
            'danhsachsvs.ma_sv' => [
                // 'title'    => trans('core/base::tables.ma_sv'),
                'title'    => 'Mã SV',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachlops.ma_lop' => [
                'title'    => 'Lớp',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachsvs.ten_sv' => [
                'title'    => 'Tên SV',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachsvs.ho_sv' => [
                'title'    => 'Họ SV',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachsvs.ngay_sinh' => [
                'title'    => 'Ngày Sinh',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachsvs.dia_chi' => [
                'title'    => 'Địa Chỉ',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ]
        ];
    }
}

<?php

namespace Botble\Quanlysv\Tables;

use Auth;
use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachlopInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;
use Botble\Quanlysv\Models\Danhsachlop;
use Html;

class DanhsachlopTable extends TableAbstract
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
     * DanhsachlopTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param DanhsachlopInterface $danhsachlopRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, DanhsachlopInterface $danhsachlopRepository)
    {
        $this->repository = $danhsachlopRepository;
        $this->setOption('id', 'plugins-danhsachlop-table');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['danhsachlop.edit', 'danhsachlop.destroy'])) {
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
            ->editColumn('ma_lop', function ($item) {
                if (!Auth::user()->hasPermission('danhsachlop.edit')) {
                    return $item->ma_lop;
                }

                return Html::link(route('danhsachsv.index', $this->filterByLop($item->id)), $item->ma_lop);
                // return Html::link(route('danhsachlop.edit', $item->id), $item->ma_lop);
            })
            ->editColumn('chuyen_nganh', function ($item) {
                if (!Auth::user()->hasPermission('danhsachlop.edit')) {
                    return $item->chuyen_nganh;
                }
                return Html::link(route('danhsachlop.edit', $item->id), $item->chuyen_nganh);
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
                return $this->getOperations('danhsachlop.edit', 'danhsachlop.destroy', $item);
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
            'danhsachlops.id',
            'danhsachlops.ma_lop',
            'danhsachlops.chuyen_nganh',
            'danhsachlops.created_at',
            'danhsachlops.status',
        ];

        $query = $model->select($select);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id' => [
                'name'  => 'danhsachlops.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'ma_lop' => [
                'name'  => 'danhsachlops.ma_lop',
                // 'title' => trans('core/base::tables.name'),
                'title' => 'Mã Lớp',
                'class' => 'text-left',
            ],
            'chuyen_nganh' => [
                'name'  => 'danhsachlops.chuyen_nganh',
                'title' => 'Chuyên Ngành',
                'class' => 'text-left',
            ],
            // 'created_at' => [
            //     'name'  => 'danhsachlops.created_at',
            //     'title' => trans('core/base::tables.created_at'),
            //     'width' => '100px',
            // ],
            // 'status' => [
            //     'name'  => 'danhsachlops.status',
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
        $buttons = $this->addCreateButton(route('danhsachlop.create'), 'danhsachlop.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Danhsachlop::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('danhsachlop.deletes'), 'danhsachlop.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'danhsachlops.ma_lop' => [
                'title'    => 'Mã Lớp',
                // 'title'    => trans('core/base::tables.ma_lop'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'danhsachlops.chuyen_nganh' => [
                'title'    => 'Chuyên Ngành',
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            // 'danhsachlops.status' => [
            //     'title'    => trans('core/base::tables.status'),
            //     'type'     => 'select',
            //     'choices'  => BaseStatusEnum::labels(),
            //     'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            // ],
            // 'danhsachlops.created_at' => [
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
        return $this->getBulkChanges();
    }
}

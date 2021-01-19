<?php

namespace Botble\Quanlysv\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Quanlysv\Http\Requests\DanhsachsvRequest;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachsvInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Quanlysv\Tables\DanhsachsvTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Quanlysv\Forms\DanhsachsvForm;
use Botble\Base\Forms\FormBuilder;

class DanhsachsvController extends BaseController
{
    /**
     * @var DanhsachsvInterface
     */
    protected $danhsachsvRepository;

    /**
     * @param DanhsachsvInterface $danhsachsvRepository
     */
    public function __construct(DanhsachsvInterface $danhsachsvRepository)
    {
        $this->danhsachsvRepository = $danhsachsvRepository;
    }

    /**
     * @param DanhsachsvTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(DanhsachsvTable $table)
    {
        page_title()->setTitle(trans('plugins/quanlysv::danhsachsv.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/quanlysv::danhsachsv.create'));

        return $formBuilder->create(DanhsachsvForm::class)->renderForm();
    }

    /**
     * @param DanhsachsvRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(DanhsachsvRequest $request, BaseHttpResponse $response)
    {
        $danhsachsv = $this->danhsachsvRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DANHSACHSV_MODULE_SCREEN_NAME, $request, $danhsachsv));

        return $response
            ->setPreviousUrl(route('danhsachsv.index'))
            ->setNextUrl(route('danhsachsv.edit', $danhsachsv->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $danhsachsv = $this->danhsachsvRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $danhsachsv));

        page_title()->setTitle(trans('plugins/quanlysv::danhsachsv.edit') . ' "' . $danhsachsv->name . '"');

        return $formBuilder->create(DanhsachsvForm::class, ['model' => $danhsachsv])->renderForm();
    }

    /**
     * @param $id
     * @param DanhsachsvRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, DanhsachsvRequest $request, BaseHttpResponse $response)
    {
        $danhsachsv = $this->danhsachsvRepository->findOrFail($id);

        $danhsachsv->fill($request->input());

        $this->danhsachsvRepository->createOrUpdate($danhsachsv);

        event(new UpdatedContentEvent(DANHSACHSV_MODULE_SCREEN_NAME, $request, $danhsachsv));

        return $response
            ->setPreviousUrl(route('danhsachsv.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $danhsachsv = $this->danhsachsvRepository->findOrFail($id);

            $this->danhsachsvRepository->delete($danhsachsv);

            event(new DeletedContentEvent(DANHSACHSV_MODULE_SCREEN_NAME, $request, $danhsachsv));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $danhsachsv = $this->danhsachsvRepository->findOrFail($id);
            $this->danhsachsvRepository->delete($danhsachsv);
            event(new DeletedContentEvent(DANHSACHSV_MODULE_SCREEN_NAME, $request, $danhsachsv));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

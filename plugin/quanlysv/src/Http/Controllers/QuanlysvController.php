<?php

namespace Botble\Quanlysv\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Quanlysv\Http\Requests\QuanlysvRequest;
use Botble\Quanlysv\Repositories\Interfaces\QuanlysvInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Quanlysv\Tables\QuanlysvTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Quanlysv\Forms\QuanlysvForm;
use Botble\Base\Forms\FormBuilder;

class QuanlysvController extends BaseController
{
    /**
     * @var QuanlysvInterface
     */
    protected $quanlysvRepository;

    /**
     * @param QuanlysvInterface $quanlysvRepository
     */
    public function __construct(QuanlysvInterface $quanlysvRepository)
    {
        $this->quanlysvRepository = $quanlysvRepository;
    }

    /**
     * @param QuanlysvTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(QuanlysvTable $table)
    {
        page_title()->setTitle(trans('plugins/quanlysv::quanlysv.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/quanlysv::quanlysv.create'));

        return $formBuilder->create(QuanlysvForm::class)->renderForm();
    }

    /**
     * @param QuanlysvRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(QuanlysvRequest $request, BaseHttpResponse $response)
    {
        $quanlysv = $this->quanlysvRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(QUANLYSV_MODULE_SCREEN_NAME, $request, $quanlysv));

        return $response
            ->setPreviousUrl(route('quanlysv.index'))
            ->setNextUrl(route('quanlysv.edit', $quanlysv->id))
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
        $quanlysv = $this->quanlysvRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $quanlysv));

        page_title()->setTitle(trans('plugins/quanlysv::quanlysv.edit') . ' "' . $quanlysv->name . '"');

        return $formBuilder->create(QuanlysvForm::class, ['model' => $quanlysv])->renderForm();
    }

    /**
     * @param $id
     * @param QuanlysvRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, QuanlysvRequest $request, BaseHttpResponse $response)
    {
        $quanlysv = $this->quanlysvRepository->findOrFail($id);

        $quanlysv->fill($request->input());

        $this->quanlysvRepository->createOrUpdate($quanlysv);

        event(new UpdatedContentEvent(QUANLYSV_MODULE_SCREEN_NAME, $request, $quanlysv));

        return $response
            ->setPreviousUrl(route('quanlysv.index'))
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
            $quanlysv = $this->quanlysvRepository->findOrFail($id);

            $this->quanlysvRepository->delete($quanlysv);

            event(new DeletedContentEvent(QUANLYSV_MODULE_SCREEN_NAME, $request, $quanlysv));

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
            $quanlysv = $this->quanlysvRepository->findOrFail($id);
            $this->quanlysvRepository->delete($quanlysv);
            event(new DeletedContentEvent(QUANLYSV_MODULE_SCREEN_NAME, $request, $quanlysv));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

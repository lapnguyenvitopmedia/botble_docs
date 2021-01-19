<?php

namespace Botble\Quanlysv\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Quanlysv\Http\Requests\DanhsachlopRequest;
use Botble\Quanlysv\Repositories\Interfaces\DanhsachlopInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Quanlysv\Tables\DanhsachlopTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Quanlysv\Forms\DanhsachlopForm;
use Botble\Base\Forms\FormBuilder;

class DanhsachlopController extends BaseController
{
    /**
     * @var DanhsachlopInterface
     */
    protected $danhsachlopRepository;

    /**
     * @param DanhsachlopInterface $danhsachlopRepository
     */
    public function __construct(DanhsachlopInterface $danhsachlopRepository)
    {
        $this->danhsachlopRepository = $danhsachlopRepository;
    }

    /**
     * @param DanhsachlopTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(DanhsachlopTable $table)
    {
        page_title()->setTitle(trans('plugins/quanlysv::danhsachlop.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/quanlysv::danhsachlop.create'));

        return $formBuilder->create(DanhsachlopForm::class)->renderForm();
    }

    /**
     * @param DanhsachlopRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(DanhsachlopRequest $request, BaseHttpResponse $response)
    {
        $danhsachlop = $this->danhsachlopRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DANHSACHLOP_MODULE_SCREEN_NAME, $request, $danhsachlop));

        return $response
            ->setPreviousUrl(route('danhsachlop.index'))
            ->setNextUrl(route('danhsachlop.edit', $danhsachlop->id))
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
        $danhsachlop = $this->danhsachlopRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $danhsachlop));

        page_title()->setTitle(trans('plugins/quanlysv::danhsachlop.edit') . ' "' . $danhsachlop->name . '"');

        return $formBuilder->create(DanhsachlopForm::class, ['model' => $danhsachlop])->renderForm();
    }

    /**
     * @param $id
     * @param DanhsachlopRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, DanhsachlopRequest $request, BaseHttpResponse $response)
    {
        $danhsachlop = $this->danhsachlopRepository->findOrFail($id);

        $danhsachlop->fill($request->input());

        $this->danhsachlopRepository->createOrUpdate($danhsachlop);

        event(new UpdatedContentEvent(DANHSACHLOP_MODULE_SCREEN_NAME, $request, $danhsachlop));

        return $response
            ->setPreviousUrl(route('danhsachlop.index'))
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
            $danhsachlop = $this->danhsachlopRepository->findOrFail($id);

            $this->danhsachlopRepository->delete($danhsachlop);

            event(new DeletedContentEvent(DANHSACHLOP_MODULE_SCREEN_NAME, $request, $danhsachlop));

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
            $danhsachlop = $this->danhsachlopRepository->findOrFail($id);
            $this->danhsachlopRepository->delete($danhsachlop);
            event(new DeletedContentEvent(DANHSACHLOP_MODULE_SCREEN_NAME, $request, $danhsachlop));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

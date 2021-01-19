<?php

namespace Botble\Quanlysv\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Quanlysv\Http\Requests\TestControllerRequest;
use Botble\Quanlysv\Repositories\Interfaces\TestControllerInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Quanlysv\Tables\TestControllerTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Quanlysv\Forms\TestControllerForm;
use Botble\Base\Forms\FormBuilder;

class TestControllerController extends BaseController
{
    /**
     * @var TestControllerInterface
     */
    protected $testControllerRepository;

    /**
     * @param TestControllerInterface $testControllerRepository
     */
    public function __construct(TestControllerInterface $testControllerRepository)
    {
        $this->testControllerRepository = $testControllerRepository;
    }

    /**
     * @param TestControllerTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(TestControllerTable $table)
    {
        page_title()->setTitle(trans('packages/{-module}::testcontroller.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('packages/{-module}::testcontroller.create'));

        return $formBuilder->create(TestControllerForm::class)->renderForm();
    }

    /**
     * @param TestControllerRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(TestControllerRequest $request, BaseHttpResponse $response)
    {
        $testController = $this->testControllerRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(TEST_CONTROLLER_MODULE_SCREEN_NAME, $request, $testController));

        return $response
            ->setPreviousUrl(route('testcontroller.index'))
            ->setNextUrl(route('testcontroller.edit', $testController->id))
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
        $testController = $this->testControllerRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $testController));

        page_title()->setTitle(trans('packages/{-module}::testcontroller.edit') . ' "' . $testController->name . '"');

        return $formBuilder->create(TestControllerForm::class, ['model' => $testController])->renderForm();
    }

    /**
     * @param $id
     * @param TestControllerRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, TestControllerRequest $request, BaseHttpResponse $response)
    {
        $testController = $this->testControllerRepository->findOrFail($id);

        $testController->fill($request->input());

        $this->testControllerRepository->createOrUpdate($testController);

        event(new UpdatedContentEvent(TEST_CONTROLLER_MODULE_SCREEN_NAME, $request, $testController));

        return $response
            ->setPreviousUrl(route('testcontroller.index'))
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
            $testController = $this->testControllerRepository->findOrFail($id);

            $this->testControllerRepository->delete($testController);

            event(new DeletedContentEvent(TEST_CONTROLLER_MODULE_SCREEN_NAME, $request, $testController));

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
            $testController = $this->testControllerRepository->findOrFail($id);
            $this->testControllerRepository->delete($testController);
            event(new DeletedContentEvent(TEST_CONTROLLER_MODULE_SCREEN_NAME, $request, $testController));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}

<?php

namespace Botble\Quanlysv\Providers;

use Botble\Quanlysv\Models\Quanlysv;
use Illuminate\Support\ServiceProvider;
use Botble\Quanlysv\Repositories\Caches\QuanlysvCacheDecorator;
use Botble\Quanlysv\Repositories\Eloquent\QuanlysvRepository;
use Botble\Quanlysv\Repositories\Interfaces\QuanlysvInterface;
use Botble\Base\Supports\Helper;
use Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class QuanlysvServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(QuanlysvInterface::class, function () {
            return new QuanlysvCacheDecorator(new QuanlysvRepository(new Quanlysv));
        });

        //danh sach lop
        $this->app->bind(\Botble\Quanlysv\Repositories\Interfaces\DanhsachlopInterface::class, function () {
            return new \Botble\Quanlysv\Repositories\Caches\DanhsachlopCacheDecorator(
                new \Botble\Quanlysv\Repositories\Eloquent\DanhsachlopRepository(new \Botble\Quanlysv\Models\Danhsachlop)
            );
        });

        //danh sach sv
        $this->app->bind(\Botble\Quanlysv\Repositories\Interfaces\DanhsachsvInterface::class, function () {
            return new \Botble\Quanlysv\Repositories\Caches\DanhsachsvCacheDecorator(
                new \Botble\Quanlysv\Repositories\Eloquent\DanhsachsvRepository(new \Botble\Quanlysv\Models\Danhsachsv)
            );
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    public function boot()
    {
        $this->setNamespace('plugins/quanlysv')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web']);

        Event::listen(RouteMatched::class, function () {
            if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
                \Language::registerModule([Quanlysv::class]);

                //danh sach lop
                \Language::registerModule([\Botble\Quanlysv\Models\Danhsachlop::class]);

                //danh sach sv
                \Language::registerModule([\Botble\Quanlysv\Models\Danhsachsv::class]);
            }

            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-quanlysv',
                'priority'    => 1,
                'parent_id'   => null,
                'name'        => 'plugins/quanlysv::quanlysv.name',
                'icon'        => 'fa fa-list',
                'url'         => route('quanlysv.index'),
                'permissions' => ['quanlysv.index'],
            ]);

            //danh sach lop
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-danhsachlop',
                'priority'    => 0,
                'parent_id'   => 'cms-plugins-quanlysv',
                'name'        => 'plugins/quanlysv::danhsachlop.name',
                'icon'        => 'fa fa-book',
                'url'         => route('danhsachlop.index'),
                'permissions' => ['danhsachlop.index'],
            ]);

            //danh sach sv
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-danhsachsv',
                'priority'    => 1,
                'parent_id'   => 'cms-plugins-quanlysv',
                'name'        => 'plugins/quanlysv::danhsachsv.name',
                'icon'        => 'fa fa-graduation-cap',
                'url'         => route('danhsachsv.index'),
                'permissions' => ['danhsachsv.index'],
            ]);
        });

        $this->app->booted(function () {
            if (function_exists('add_shortcode')) {
                add_shortcode('danhSachSV', 'Danh Sách Sinh Viên', 'Danh Sách Sinh Viên', function () {
                    return view('plugins/quanlysv::partials.danhsachSV');
                });
                add_shortcode('danhSachLop', 'Danh Sách Lớp', 'Danh Sách Lớp', function () {
                    return view('plugins/quanlysv::partials.danhsachLop');
                });
            }
        });
    }


}

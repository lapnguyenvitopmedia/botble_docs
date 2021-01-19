<?php

namespace Botble\Quanlysv\Providers;

use Html;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Botble\Contact\Repositories\Interfaces\ContactInterface;
use Theme;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @throws \Throwable
     */
    public function boot()
    {
        $this->app->booted(function () {
            if (function_exists('add_shortcode')) {
                if (function_exists('add_shortcode')) {
                    add_shortcode('danhSachSV', 'Danh Sách Sinh Viên', 'Danh Sách Sinh Viên',
                    [$this, 'form']);
                }
                shortcode()
                    ->setAdminConfig('danhsachSV', view('plugins/quanlysv::partials.short-code-admin-config')->render());
            }
        });
    }

    /**
     * @param string $options
     * @return string
     *
     * @throws \Throwable
     */
    // public function registerTopHeaderNotification($options)
    // {
    //     if (Auth::user()->hasPermission('contacts.edit')) {
    //         $contacts = $this->app->make(ContactInterface::class)
    //             ->getUnread(['id', 'name', 'email', 'phone', 'created_at']);

    //         if ($contacts->count() == 0) {
    //             return null;
    //         }

    //         return $options . view('plugins/contact::partials.notification', compact('contacts'))->render();
    //     }
    //     return null;
    // }

    /**
     * @param int $number
     * @param string $menuId
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    // public function getUnreadCount($number, $menuId)
    // {
    //     if ($menuId == 'cms-plugins-contact') {
    //         $unread = $this->app->make(ContactInterface::class)->countUnread();
    //         if ($unread > 0) {
    //             return Html::tag('span', (string) $unread, ['class' => 'badge badge-success'])->toHtml();
    //         }
    //     }

    //     return $number;
    // }

    /**
     * @return string
     * @throws \Throwable
     */
    public function form($shortcode)
    {
        $view = apply_filters('plugins/contact::forms.contact');

        // if (defined('THEME_OPTIONS_MODULE_SCREEN_NAME')) {
        //     $this->app->booted(function () {
        //         Theme::asset()
        //             ->usePath(false)
        //             ->add('contact-css', asset('vendor/core/plugins/contact/css/contact-public.css'), [], [], '1.0.0');

        //         Theme::asset()
        //             ->container('footer')
        //             ->usePath(false)
        //             ->add(
        //                 'contact-public-js',
        //                 asset('vendor/core/plugins/contact/js/contact-public.js'),
        //                 ['jquery'],
        //                 [],
        //                 '1.0.0'
        //             );
        //     });
        // }

        if ($shortcode->view && view()->exists($shortcode->view)) {
            $view = $shortcode->view;
        }
        return view($view)->render();
    }
}

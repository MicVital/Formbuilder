<?php

namespace Modules\Formbuilder\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterFormbuilderSidebar implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('formbuilder::formbuilder.title.form builder'), function (Item $item) {
                $item->weight(10);
                $item->icon('fa fa-list-alt');
                $item->authorize(
                    $this->auth->hasAccess('user.users.index') or $this->auth->hasAccess('user.roles.index')
                );

                $item->item(trans('formbuilder::formbuilder.title.forms'), function (Item $item) {
                    $item->weight(10);
                    $item->icon('fa fa-list-alt');
                    $item->route('admin.formbuilder.formbuilder.index');
                    $item->authorize(
                        $this->auth->hasAccess('formbuilder.formbuilders.index')
                    );
                });

                $item->item(trans('formbuilder::formbuilder.title.submissions'), function (Item $item) {
                    $item->weight(11);
                    $item->icon('fa fa-envelope-o');
                    $item->route('admin.formbuilder.submissions.index');
                    $item->authorize(
                        $this->auth->hasAccess('formbuilder.submission.index')
                    );
                });
            });
        });


        return $menu;
    }
}

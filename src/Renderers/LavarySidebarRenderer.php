<?php
/**
 * Class LavarySidebarRenderer
 *
 * @author del
 */

namespace Delatbabel\NestedMenus\Renderers;

use Delatbabel\NestedMenus\Models\Menu as MenuModel;
use Illuminate\Support\Str;
use Lavary\Menu\Builder as LavaryBuilder;
use Lavary\Menu\Menu as LavaryMenu;
use Lavary\Menu\Item as MenuItem;

/**
 * Class LavarySidebarRenderer
 *
 * This implementation of the RendererInterface uses the Lavary Menu
 * system to build and render an AdminLTE compatible sidebar menu.
 *
 * An AdminLTE compatible sidebar menu should look like this:
 *
 * ```html
 * <!-- Sidebar Menu -->
 * <ul class="sidebar-menu">
 *     <li class="header">HEADER</li>
 *     <!-- Optionally, you can add icons to the links -->
 *     <li class="active"><a href="#"><i class='fa fa-link'></i> <span>Link</span></a></li>
 *     <li><a href="#"><i class='fa fa-link'></i> <span>Another Link</span></a></li>
 *     <li class="treeview">
 *         <a href="#"><i class='fa fa-link'></i> <span>Multilevel</span> <i class="fa fa-angle-left pull-right"></i></a>
 *         <ul class="treeview-menu">
 *             <li><a href="#">Link in level 2</a></li>
 *             <li><a href="#">Link in level 2</a></li>
 *         </ul>
 *     </li>
 * </ul><!-- /.sidebar-menu -->
 * ```
 *
 * @link https://packagist.org/packages/lavary/laravel-menu
 */
class LavarySidebarRenderer implements RendererInterface
{
    /**
     * Get the menu data to be passed to the menu->add function
     *
     * Lavary Menu requires an array of data to be passed to the menu->add()
     * function call.
     *
     * @param MenuModel $menuModel
     * @return array
     */
    protected function getMenuData(MenuModel $menuModel)
    {
        // Menu headers only have one class and no link.
        if ($menuModel->isRoot()) {
            return ['class' => 'header'];
        }

        $menu_data = array();

        // Provide a route if there is one in the table otherwise provide a URL.
        if (! empty($menuModel->route)) {
            $menu_data['route'] = $menuModel->route;
        } elseif (! empty($menuModel->url)) {
            $menu_data['url'] = $menuModel->url;
        }

        // If this node has children, make it a treeview
        if (! $menuModel->isLeaf()) {
            $menu_data['class'] = 'treeview';
        }

        return $menu_data;
    }

    /**
     * Build a child menu item as the descendent of a previously created menu item.
     *
     * @param MenuModel $descendant
     * @param MenuItem|MenuBuilder  $menuItem parent menu item
     * @return MenuItem
     */
    protected function renderChildNode(MenuModel $descendant, $menuItem)
    {
        /** @var MenuItem $childNode */
        $childNode = $menuItem->add($descendant->name, $this->getMenuData($descendant));
        return $childNode;
    }

    /**
     * Build a menu and return the menu object
     *
     * This function only supports 2 levels of children.
     *
     * @param MenuModel $menuModel
     * @return LavaryBuilder
     */
    public function renderToObject(MenuModel $menuModel)
    {
        // Get the menu name, which will be the name of the variable shared
        // to all of the views.
        $menu_name = Str::studly($menuModel->name);

        // Create the first item in the menu.  Don't use the facade because
        // it may not work if the alias hasn't been created, and I don't document
        // creating the alias in my own README because the alias name conflicts
        // with one of my own class names.
        $lavaryMenu = new LavaryMenu();

        $lavaryMenu->make($menu_name, function($menu) use ($menuModel) {
            $menu_data = $this->getMenuData($menuModel);

            // The first menu item may be a header.
            /** @var LavaryBuilder $menu */
            /** @var MenuItem $menuItem */
            if (empty($menu_data['url']) && empty($menu_data['route'])) {
                $menuItem = $menu->raw($menuModel->name, $menu_data);
            } else {
                $menuItem = $menu->add($menuModel->name, $menu_data);

                // Append and prepend
                $menuItem->prepend('<i class="fa fa-link""></i> <span>')
                    ->append('</span>');
            }

            // Create all of the first level children as siblings of the header.
            /** @var MenuModel $descendant */
            foreach ($menuModel->getImmediateDescendants() as $descendant) {
                $descendantItem = $this->renderChildNode($descendant, $menu);

                // Create all of the second level children
                foreach ($descendant->getImmediateDescendants() as $grandchild) {
                    $this->renderChildNode($grandchild, $descendantItem);
                }
            }
        });

        // LavaryMenu::make puts a LavaryBuilder item in the collection,
        /** @var LavaryBuilder $builder */
        $builder = $lavaryMenu->get($menu_name);
        return $builder;
    }

    /**
     * Build a menu and return the rendered menu.
     *
     * @param MenuModel $menuModel
     * @return string
     */
    public function renderToHtml(MenuModel $menuModel)
    {
        // Create the menu
        $menuObject = $this->renderToObject($menuModel);

        // The outer UL should have class = sidebar-menu
        // Any inner UL should have class = treeview-menu
        return $menuObject->asUl(['class' => 'sidebar-menu'], ['class' => 'treeview-menu']);
    }
}

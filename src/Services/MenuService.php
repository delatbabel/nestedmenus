<?php
/**
 * Class MenuService
 *
 * @author del
 */

namespace Delatbabel\NestedMenus\Services;

use Delatbabel\NestedMenus\Models\Menu;
use Delatbabel\NestedMenus\Renderers\LavarySidebarRenderer;
use Illuminate\Support\Str;

/**
 * Class MenuService
 *
 * Rendering menu structures from the database.
 *
 * ### Example
 *
 * <code>
 * \@inject('menus', 'Delatbabel\NestedMenus\Services\MenuService')
 *
 * <!-- Using the regular make method -->
 * <title> {!! $menus->make('menu-slug') !!} </title>
 *
 * <!-- Using a magic getter -->
 * <title> {!! $menus->menu_slug !!} </title>
 * </code>
 *
 * @see Delatbabel\NestedMenus\Models\Menu
 * @see Delatbabel\NestedMenus\Renderers\LavarySidebarRenderer
 */
class MenuService
{
    /**
     * Render a menu structure from the database
     *
     * @param string $menukey
     * @return string|null
     */
    public function make($menukey)
    {
        $menukey    = Str::slug($menukey);
        $menu       = Menu::where('slug', '=', $menukey)->first();
        $renderer   = new LavarySidebarRenderer();
        return $renderer->renderToHtml($menu);
    }

    /**
     * Magic getter method
     *
     * @param string $menukey
     * @return null|string
     */
    public function __get($menukey)
    {
        return $this->make($menukey);
    }
}

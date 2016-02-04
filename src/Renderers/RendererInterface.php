<?php
/**
 * Interface RendererInterface
 *
 * @author del
 */

namespace Delatbabel\NestedMenus\Renderers;

use Delatbabel\NestedMenus\Models\Menu as MenuModel;

/**
 * Interface RendererInterface
 *
 * Defines the functions for a renderer
 */
interface RendererInterface
{
    /**
     * Build a menu and return the menu object
     *
     * @param MenuModel $menuModel
     * @return mixed
     */
    public function renderToObject(MenuModel $menuModel);

    public function renderToHtml(MenuModel $menuModel);
}

<?php
/**
 * ShowMenu
 */
namespace Delatbabel\NestedMenus\Console\Commands;

use Illuminate\Console\Command;
use Delatbabel\NestedMenus\Models\Menu;
use Delatbabel\NestedMenus\Renderers\LavarySidebarRenderer;

/*
 * ShowMenu
 *
 * This is a script to show what a menu looks like.
 */
class ShowMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nestedmenus:showmenu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a menu for testing purposes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Find the menu -- searching by slug is a good way to identify
        // menus.
        /** @var Menu $menu */
        $menu = Menu::where('slug', '=', 'example-menu')->first();

        // Create the renderer.
        $renderer = new LavarySidebarRenderer();

        // Use the renderer to render the menu to HTML.
        $rendered = $renderer->renderToHtml($menu);

        // Output the HTML to the console just as an example.
        $this->output->writeln($rendered);
    }
}

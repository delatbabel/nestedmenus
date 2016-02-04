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
        /** @var Menu $menu */
        $menu = Menu::first();

        $renderer = new LavarySidebarRenderer();
        $rendered = $renderer->renderToHtml($menu);

        $this->output->writeln($rendered);
    }
}

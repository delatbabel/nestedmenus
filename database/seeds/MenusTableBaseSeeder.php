<?php

use Delatbabel\NestedMenus\Models\Menu;
use Illuminate\Database\Seeder;

class MenusTableBaseSeeder extends Seeder
{
    /**
     * Return an array of nodes.
     *
     * Over-ride this function in your real seeder class.
     *
     * @return array
     */
    protected function getNodes()
    {
        /**
         * The menus in your site, e.g. Products, Blog etc.
         *
         * Replace this with whatever you want in your initial seeder.  Note
         * the structure of each node -- node_name => children where children
         * must be an array.
         */
        return [
            'Example Menu' => [
                'url'       => '',
                'children'  => [
                    'Example List'    => [
                        'url'       => 'sysadmin/example',
                        'children'  => [],
                    ],
                    'Example Create'    => [
                        'url'       => 'sysadmin/example/create',
                        'children'  => [],
                    ],
                    'Example Edit'      => [
                        'url'       => 'sysadmin/example/edit',
                        'children'  => [
                            'Example Edit 1'      => [
                                'url'       => 'sysadmin/example/edit/1',
                                'children'  => [],
                            ],
                            'Example Edit 2'      => [
                                'url'       => 'sysadmin/example/edit/2',
                                'children'  => [],
                            ],
                            'Example Edit 3'      => [
                                'url'       => 'sysadmin/example/edit/3',
                                'children'  => [],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Create all of the child nodes of a root node.
     *
     * @param Menu  $root_node
     * @param array $nodes
     */
    protected function createNodes($root_node, $nodes)
    {
        foreach ($nodes as $node_name => $node_data) {

            // Create the highest level child node.
            $child_node = $root_node->children()->create([
                'name'  => $node_name,
                'url'   => $node_data['url'],
            ]);

            // Update the description, just for fun
            $child_node->description = $child_node->path;
            $child_node->save();

            // Create all of the children of the child node, if there are any.
            if (! empty($node_data['children'])) {
                $this->createNodes($child_node, $node_data['children']);
            }
        }
    }

    public function run()
    {
        $nodes = $this->getNodes();

        // Build the above list of nodes as a heirarchical tree
        // of categories.
        foreach ($nodes as $node_name => $node_data) {

            // Create each root node.
            $root_node = Menu::create([
                'name'  => $node_name,
                'url'   => $node_data['url'],
            ]);

            // Create the children of the root node.
            if (! empty($node_data['children'])) {
                $this->createNodes($root_node, $node_data['children']);
            }
        }
    }
}

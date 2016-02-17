# nestedmenus

Database storage of nested menus, including rendering

## Based On

* [Baum](http://etrepat.com/baum/) for data storage.
* [AdminLTE](https://almsaeedstudio.com/) menu templates.
* [Lavary Menu](https://packagist.org/packages/lavary/laravel-menu) for menu rendering.

## Comes with

* Migration for the `menus` table
* Menu Model (that extends Baum/Node so you can use all the handy methods from this excellent nested set implementation)
* Seed for building the menu nodes, one for each type of menu

## Installation

Add these lines to your composer.json file:

```
    "require": {
        "delatbabel/nestedmenus": "~1.0"
    },
```

Once that is done, run the composer update command:

```
    composer update
```

Alternatively just run this command:

```
    composer require delatbabel/nestedmenus
```

### Register Service Provider

After composer update completes, add this line to your config/app.php file in the 'providers' array:

```
    Delatbabel\NestedMenus\NestedMenusServiceProvider::class
```

### Publish the Migrations

Publish the migrations

```
    php artisan vendor:publish
```

Run the migration

```
    php artisan migrate
```

Ensure the categories `types` are set correctly in the seeder file.  You can initialise this to
whatever you like.

### Run the Seeders

Run the seed (this will create the base menu structure as defined in the seeder)

```
    php artisan db:seed
```

You may prefer to build your own **MenusTableSeeder** class based on the code in
**MenusTableBaseSeeder** to seed your own initial set of menus.

# Usage

This class relies on the behind-the-scenes capabilities of Baum.  For details on the use
of that see the [README on github](https://github.com/etrepat/baum) or the
[Baum web site](http://etrepat.com/baum/)

## Creating the Menu Structure

See the MenusTableBaseSeeder for an example of how to build the menu structure in the database.
You can in fact just extend this seeder to create your own seeder, just overriding the function
getNodes() to provide the menu structure to seed into the database.

Menu editors and the such like are still to be done.

The important parts of the database structure look like this when completed:

```
+----+-----------+------+------+-------+----------------|----------------+-------------------------+-------+
| id | parent_id | lft  | rgt  | depth | slug           | name           | url                     | route |
+----+-----------+------+------+-------+----------------|----------------+-------------------------+-------+
|  1 |      NULL |    1 |   14 |     0 | example-menu   | Example Menu   |                         | NULL  |
|  2 |         1 |    2 |    3 |     1 | example-list   | Example List   | sysadmin/example        | NULL  |
|  3 |         1 |    4 |    5 |     1 | example-create | Example Create | sysadmin/example/create | NULL  |
|  4 |         1 |    6 |   13 |     1 | example-edit   | Example Edit   | sysadmin/example/edit   | NULL  |
|  5 |         4 |    7 |    8 |     2 | example-edit-1 | Example Edit 1 | sysadmin/example/edit/1 | NULL  |
|  6 |         4 |    9 |   10 |     2 | example-edit-2 | Example Edit 2 | sysadmin/example/edit/2 | NULL  |
|  7 |         4 |   11 |   12 |     2 | example-edit-3 | Example Edit 3 | sysadmin/example/edit/3 | NULL  |
+----+-----------+------+------+-------+----------------|----------------+-------------------------+-------+
```

Note that at the moment the renderers available only support a 2 level menu structure.
Level 0 is the heading. At level 1 are the various options, and at level 2 are the
sub-options for each option.

See the [Baum](http://etrepat.com/baum/) functions for more detail on creating and
manipulating this structure.

## Loading the Menu Structure

The menu structure can be loaded from the database using any normal Eloquent Model code, for
example:

```php
    $menu = \Delatbabel\NestedMenus\Models\Menu::where('slug', '=', 'example-menu')->first();
```

Note that you only need to load the top level of the menu in order to be able to render the
entire menu.

## Rendering the Menu Structure

See the ShowMenu class under src/Console/Commands for the details on how to do this.  It's
really simple:

```php
    $renderer = new LavarySidebarRenderer();
    $rendered = $renderer->renderToHtml($menu);
```

Rendering to different menu structures requires different renderer classes.  I will build
more of these over time.

Placing the rendered menu on a view is as simple as this:

```php
    return View::make("dashboard.example")
        ->with('sidebar_menu', $sidebar_menu);
```

# TODO

Integrate additional menu front end creators. There are a few out there.  I have decided to
allow for integration of both of these:

* https://packagist.org/packages/vespakoen/menu
* https://packagist.org/packages/lavary/laravel-menu

## Lavary Menu

Completed a LavarySidebarRenderer class.  I had to extend the Lavary menu classes in order
to do this, so instead of their main repo use the VCS mentioned in the composer.json in this
package.  I have submitted a PR to the Lavary repo for this change.  See
[PR #100](https://github.com/lavary/laravel-menu/pull/100).

Need to have separate renderer classes for the other menu types (header menus, pulldown menus, etc).

## Vespakoen Menu

TBD

# Architecture

## Rationale

I want to be able to create [AdminLTE](https://almsaeedstudio.com/) based menus, which look
like this:

```html
<!-- Sidebar Menu -->
<ul class="sidebar-menu">
    <li class="header">HEADER</li>
    <!-- Optionally, you can add icons to the links -->
    <li class="active"><a href="#"><i class='fa fa-link'></i> <span>Link</span></a></li>
    <li><a href="#"><i class='fa fa-link'></i> <span>Another Link</span></a></li>
    <li class="treeview">
        <a href="#"><i class='fa fa-link'></i> <span>Multilevel</span> <i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li><a href="#">Link in level 2</a></li>
            <li><a href="#">Link in level 2</a></li>
        </ul>
    </li>
</ul><!-- /.sidebar-menu -->
```

I want to be able to create them dynamically rather than embed them in the view files.

I want to be able to store the menu structure in a database table so that it can be
modified at run time or without having to change any code.

## Imports

After using [Baum](http://etrepat.com/baum/) successfully for my
[Nested Categories](https://github.com/delatbabel/nestedcategories) package, It seemed
the obvious choice for heirarchical storage of the menu structure.

The only thing that remained is the creation of the views.  I figured that there were
a few options:

* Use a Laravel / Blade template to create the menu and import it into a page as a section.
* Use a package to create the menu structure and format it for display.

I found many packages on line to format the menu structure for display, but the two that
I settled on were these:

* https://packagist.org/packages/vespakoen/menu
* https://packagist.org/packages/lavary/laravel-menu

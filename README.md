# nestedmenus

Database storage of nested menus, including rendering

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

# TODO

Integrate one of the menu front end creators. There are a few out there but which one should
I choose?  I have decided to allow for integration of both of these.

* https://packagist.org/packages/vespakoen/menu
* https://packagist.org/packages/lavary/laravel-menu

## Lavary Menu

Commenced a LavarySidebarRenderer class.  Still a lot of work to do on the rendering.

* If the parent menu item class is treeview then make the next UL class = "treeview-menu"
  This can't be done without extending the Lavary/Menu/Builder class or doing it all with
  a custom template.

Need to have separate renderer classes for the other menu types.

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

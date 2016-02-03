# nestedmenus

Database storage of nested menus, including rendering

## Comes with

* Migration for the `categories` table
* Menu Model (that extends Baum/Node so you can use all the handy methods from this excellent nested set implementation)
* Seed for building the root nodes, one for each type of hierarchy, specified in your config file

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
    Delatbabel\NestedCategories\NestedMenusServiceProvider::class
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

Run the seed (this will create root nodes for each of your category `types`)

```
    php artisan db:seed --class="MenusTableBaseSeeder"
```

You may prefer to build your own **MenusTableSeeder** class based on the code in
**MenusTableBaseSeeder** to seed your own initial set of menus.

# Usage

This class relies on the behind-the-scenes capabilities of Baum.  For details on the use
of that see the [README on github](https://github.com/etrepat/baum) or the
[Baum web site](http://etrepat.com/baum/)

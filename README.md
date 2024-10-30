# phpfun - aka PHP FUNctional framework

This is an experimental project to test if it is possible to create a php framework that uses (mostly) only primitive types closures, and functions.

### Base principles are:

- no classes
- therefore no autoloads, you have to require/include the library you wish to use
- K.I.S.S as much as possible
- consistent directory structure
  - `lib` contains common libraries (e.g.: database management, events etc)
  - `src` contains the application's concrete implementation

### Entrypoint

You should consider including `app.php` file to your entrypoint as it loads all essential functions and constants that makes your work easier.

## Events

You can listen and dispatch events

```php
<?php

require_once LIB . 'event.php';

event_listen('something.happened', fn ($payload) => var_dump('Something happened at index: ' . $payload));

for ($i = 0; $i < 10; $i++) {
  sleep(1);
  event_dispatch('something.happened', $i);
}

```

## Database (WIP)

```php
<?php

require_once LIB . 'db/db.php';

db_rows('select * from users'); // select all rows table
db_row('select * from users where id=?', $_REQUEST['id']); // select a single row

db('create table posts....'); // or just simply execute any queries

```

### Relations (WIP)

_Relational features are still under development, this is just a concept!_

You can define database relations in a relatively simple way:

```php
<?php

const user_with_posts = 'has_many:posts(post_id,id,posts)';
const user_with_school = 'belongs_to:schools(school_id,id,school)';

db_row('select * from users where id=?', [1], with: [with_posts, with_school]);

// will return something like this:
// ['id' => 1, 'name' => 'John Doe', 'school_id' => 42, 'posts' => [['id' => 1, 'title' => 'Post 1'], ['id' => 2, 'title' => 'Post 2']], 'school' => ['id' => 42, 'name' => 'My primary school']]
```




### Migrations

This framework has a stupidly simple solution for managing database migrations.

usage

```bash

# Make a migration.
php lib/console/migrate.php make create_users_table

```

As you will see, this will generate a file {timestamp}_create_users_table.php

The file will return an associative array with to elements, `up` and `down`, which are closures


```php
<?php

return [
    'up' => fn () => db(<<<SQL
        CREATE TABLE user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )
    SQL),
    'down' => fn () => db('DROP TABLE user')
];

```

After writing the migration, you can run the `up` command:


```bash
php lib/console/migrate.php up
```


To roll back migration

_Currently this command rolls back all migrations, this must be changed in the future to roll back only last one!_


```bash
php lib/console/migrate.php down
```

### Routing (TBD)

### Http (TBD)

# Drupal 8 Skeleton

This is a Drupal 8 Skeleton (empty) installation based on this article: https://blog.netgloo.com/2016/05/14/configuring-drupal-8-for-a-simple-git-development-workflow/

There are some changes during installation and configuration from the original article.

## Development workflow

When working on the project the first time we have to:

  - Clone the project from the central Git repository on local PC.
  - Load the database dump from `database/exports/`.
  - Create the `sites/default/settings/settings.local.php` file, copying it from `sites/example.settings.local.php` and configure it with local settings.
  - Run `composer install` within the project root. If there is no **composer** installation you can get it from: https://getcomposer.org/download/ or https://getcomposer.org/composer.phar
  - Rebuild the drupal cache with `drush cache-rebuild`.
  - Start the server with `drush runserver 8000`.

## Git Hooks

We can configure a couple of hooks for easily deploying into server.

With this two hooks we will export DB from localmachine and import DB into server.

**NOTE: All remote server data will be lost on PULL**

### Local machine

File: `.git/hooks/pre-commit`

```
#!/bin/sh

repo_dir=`git rev-parse --show-toplevel`

mysqldump -u root -p --databases DB_NAME > $repo_dir/database/exports/DB_NAME-`date +"%Y-%m-%d--%H:%M"`.sql

git add $repo_dir
```

### Remote server

File: `.git/hooks/post-merge`

```
#!/bin/sh

repo_dir=`git rev-parse --show-toplevel`
repo_user=`stat -c %U $repo_dir`
repo_group=`stat -c %G $repo_dir`

last_sql_file=`ls -atr $repo_dir/database/exports/*sql  | tail -n1`
echo -e "\e[1m\e[31mImporting DB: \e[0m\e[34m$last_sql_file\e[0m"
mysql -u root -p DB_NAME < $last_sql_file

chown -R $repo_user:$repo_group $repo_dir

cd $repo_dir
drush cr
cd -
```

## This repo creation and configuration

This repository was created based on this article: http://blog.netgloo.com/2016/05/14/configuring-drupal-8-for-a-simple-git-development-workflow/

For more information and some extra configuration tips read the article.

## Drupal 8 installation

```
$ drush pm-download drupal --drupal-project-rename=drupal-8-skeleton
$ mysql -u root -p -e "GRANT ALL PRIVILIGES ON d8skeleton.* to d8skeleton@localhost IDENTIFIED BY 'dbSecret1234'"
$ cd drupal-8-skeleton
$ drush site-install standard --db-url='mysql://d8skeleton:dbSecret1234@localhost/d8skeleton' --account-name=d8Admin --account-pass=secret1234 --site-name="Drupal 8 Skeleton"
```

## Drupal 8 configuration

```
$ chmod +w sites/default
$ mkdir sites/default/settings
$ cp sites/default/settings.php sites/default/settings/settings.shared.php
$ chmod 644 sites/default/settings/settings.shared.php
```

Open `sites/default/settings.php` and replace all its content with:

```
<?php

include __DIR__ . '/settings/settings.shared.php';
```

#### Configuring `settings.local.php`

```
$ cp sites/example.settings.local.php sites/default/settings/settings.local.php
```

Edit `sites/default/settings/settings.local.php` and place this at the end of the file:

```
$databases['default']['default'] = array (
    'database' => 'd8skeleton',
    'username' => 'd8skeleton',
    'password' => 'dbSecret1234',
    'prefix' => '',
    'host' => 'localhost',
    'port' => '3306',
    'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
    'driver' => 'mysql',
);
```

Then, on the same file `sites/default/settings/settings.local.php`, search the `settings['rebuild_access']` line within the file and comment it. Decomment all the `settings['cache']` settings in order to disable the cache for the development environment. In the production server we can leave these lines commented in order to enable the caching system.

Open `sites/default/settings/settings.shared.php` and delete all database configuration `$databases['default']`, and then add this lines at the end in order to load local settings:

```
if (file_exists(__DIR__ . '/settings.local.php')) {
    include __DIR__ . '/settings.local.php';
}
```

#### Disabling Twig cache

We can disable Twig’s cache here adding this code to `sites/development.services.yml`:

```
# Disable Twig cache
parameters:
  twig.config:
    debug : true
    auto_reload: true
    cache: false
```

### Testing installation

```
$ drush cache-rebuild
$ drush runserver 8000
```

### Creating Database Exports

```
$ mkdir -p database/exports
$ mysqldump -u root -p --databases d8skeleton > database/exports/d8skeleton-`date +"%Y-%m-%d--%H:%M"`.sql
```

### `.htaccess` configuration

Add to `.htaccess` this lines at the begining to protect some files:

```
<FilesMatch "\.(engine|inc|install|make|module|profile|po|sh|.*sql|theme|twig|tpl(\.php)?|xtmpl|yml)(~|\.sw[op]|\.bak|\.orig|\.save)    ?$|^(\.(?!well-known).*|Entries.*|Repository|Root|Tag|Template|composer|composer\.(json|lock|phar))$|^#.*#$|\.php(~|\.sw[op]|\.bak|\    .orig|\.save)$|README\.(txt|md)$">
    order allow,deny
    deny from all
</FilesMatch>
```

### `.gitignore` configuration

```
# Ignore core when managing all of a project's dependencies with Composer
# including Drupal core.
# /core

# Core's dependencies are managed with Composer.
/vendor

# Ignore configuration files that may contain sensitive information.
/sites/*/settings/settings.local.php

# Ignore paths that contain user-generated content.
#/sites/*/files/*
#/sites/*/private/*
#!/sites/*/files/config_*

# Ignore SimpleTest multi-site environment.
/sites/simpletest

# Ignore paths that contain user-generated content.
# /sites/*/files/*
# /sites/*/private/*
# !/sites/*/files/config_*

# Swap and temporary files
*.swp
*.swo

~$*
.DS_Store

.sass-cache/

.git/hooks/*
```

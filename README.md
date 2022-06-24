PHPStan issue 6650 reproducer
-----------------------------

Steps to reproduce issue:

- clone repository and run `composer install`
- run `composer phpstan:packages`, there should be an error:
  ```
  1/1 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

  ------ ------------------------------------------------------------------------------
   Line   One/src/Foo.php
  ------ ------------------------------------------------------------------------------
   12     Method class@anonymous/One/src/Foo.php:11::b() has no return type specified.
          ✏️ One/src/Foo.php
  ------ ------------------------------------------------------------------------------

  [ERROR] Found 1 error
  ```
- Now, generate baseline file:
  ```
  vendor/bin/phpstan analyse -cutils/phpstan/analysis/packages.neon --generate-baseline=packages/One/phpstan-baseline.neon packages/One
  ```
- run analysis again, in theory there should not be any issue (since previously reported issue was ignored with baseline), but in practice there is still error + information about not matched ignore rule:
  ```
  1/1 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%

  ------ ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
   Line   One/src/Foo.php
  ------ ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

          Ignored error pattern #^Method class@anonymous/src/Foo\.php\:11\:\:b\(\) has no return type specified\.$# in path /Volumes/Projects/~Github/phpstan/issue-6650-reproducer/packages/One/src/Foo.php was not matched in reported errors.
          ✏️  One/src/Foo.php

   12     Method class@anonymous/One/src/Foo.php:11::b() has no return type specified.
          ✏️ One/src/Foo.php
  ------ ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

  [ERROR] Found 2 errors
  ```

It's because path in reported error is `class@anonymous/One/src/Foo.php:11::b()` while baseline have `class@anonymous/src/Foo.php:11::b()` (missing `One/` dir level).

It's also reproduced in Github Actions.

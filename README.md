# async-password

**DON'T USE**

## API compatibility

This package's API aims to be compatible with the native `password_*` API.

However due to the difference between parameter type checking for internal functions and userland functions in weak mode this package may throw `TypeError`s where the native functions would simply spit out an `E_WARNING`.

To prevent these API incompatibilities use `strict_types` when invoking the functions in this package.

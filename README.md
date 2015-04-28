Tool for validating translations in October CMS.
This tool will compare a translated language, using English as a reference, and tell you if anything is missing or has been removed.

## Install

In your project's root directory:
```
$ composer require krisawzm/oc-lang-compare
```
This installs an executable at `vendor/bin/oc-lang-compare`


## Usage

### Validate single directory:

```sh
$ oc-lang-compare compare:dir path/to/lang lang-code
```

### Validate everything in /modules

```sh
$ oc-lang-compare compare:modules lang-code
```

This will automatically check the `modules/backend/lang`, `modules/cms/lang` and `modules/system/lang` directories.

## Examples

Validates the Norwegian language in modules/backend/lang:

```sh
$ vendor/bin/oc-lang-compare compare:dir modules/backend/lang nb-no
```


Note: All directories are relative to your working directory.

Note 2: Running from your project's root directory, you'll have to include the `vendor/bin` path.


## Validating plugin translations

This tool runs independent from both October CMS and Laravel. This means you'll be able to validate plugin translations without installing the plugin in October beforehand. Simply clone the plugin repo and run the installation command.
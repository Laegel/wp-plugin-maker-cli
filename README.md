# wp-plugin-maker-cli
CLI for WP Plugin Maker

## Install
```
composer global require laegel/wp-plugin-maker-cli
```
Yup, it's a global package, so don't install it locally or don't expect it to work!

Configure your ~/.composer/vendor/bin directory in your $PATH to be able to use it globally.

## Commands

### init
**\-\-name**: dash-cased string, will be used to create the plugin folder name. The main namespace of the plugin will be transformed from dash-case to WordPress class naming convention (Underscore_Case) 

This command will initialize a clean plugin. Must be used in your **WordPress plugins directory**.

### generate
**\-\-name**: Underscore_Cased string, will be used as class name. "\_Controller" will be appended to the name.
**[\-\-folder]**: The folder in which the controller will be saved. Can be "All" (default value), "Admin", "Front", "CLI" or "Rest".
**[\-\-type]**: The controller type. Can be "controller" (default value) or "custom_type". 

You can generate a new controller/custom type with this command. Must be used in your generated plugin directory.

### build

To generate the actions/filters files punctually, use build. Must be used in your generated plugin directory.

### watch

To generate the actions/filters files and keep working on your plugin, use watch. Must be used in your generated plugin directory.

## Generated structure

```
my-plugin
|-- .gitignore
|-- composer.json
|-- plugin.php
|-- require-admin.php (after build)
|-- require-cli.php (after build)
|-- require-front.php (after build)
|-- require-rest.php (after build)
|-- wpm-info.php
|-- src
|   |-- Admin
|   |   |-- (empty)
|   |-- All
|   |   |-- (empty)
|   |-- CLI
|   |   |-- (empty)
|   |-- Front
|   |   |-- (empty)
|   |-- Rest
|   |   |-- (empty)
|   |-- Plugin.php
|   vendor
|   |-- (packages)
```

**What are the other files?**

- .gitignore: As you may use Git to save your project, this file is already generated. How kind!
- composer.json: Your plugin will be Composer-ready (but you won't have to register it on Packagist, thanks to [WP-Packagist](https://wpackagist.org/))
- plugin.php: The plugin init file, where your plugin metadata are saved. You can update your metadata but you shouldn't change the rest unless you really know what you're doing (don't do it).
- wpm-info.php: Weird file! But used by WPM CLI to generate files. And maybe other stuff. You might use it to keep some special plugin data (version, for example, if you don't forget to sync it)

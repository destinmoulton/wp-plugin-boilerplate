# PLUGIN_NAME

This plugin was generated via wp-plugin-boilerplate.

URL: https://github.com/destinmoulton/wp-plugin-boilerplate

# Admin Tools

The `admin/tools` folder is a location to store admin sections.

Each tool creates its own menu entry in WP Admin under the
PLUGIN_NAME menu entry.

Tools are structured in the following way:

```
  /admin
    /tools
      /tool-name
        class-tool-name.php
```

The tool, defined in `class-tool-name.php` needs to be CamelCase, ie(`class ToolName {`)

# Features

## Logger

The Logger is meant to allow rapid development and debugging of this plugin and when customizing the theme.
You will probably use the Console logging as it is extremely convenient, however if the console is not available because
of page breakage, then you can enable the file logging.

The Logger is composed of 3 parts:

- Logger Class
    - Defined in `includes/class-logger.php`
    - Logging options are stored in a cookie
    - Log is output to either:
        - JS Console via WP footer hook
        - File during class destruction
- Admin Logger Tool
    - Defined as an Admin Tool: `admin/tools/log-tool`
    - "Log Tool" is located in the "PLUGIN_NAME" left Admin menu
        - Enable and Disable Console or File Logging
        - View the File Log
- Global Logger Function
    - Defined in `functions\logger.php`
    - Provides PLUGIN_FUNC_PREFIX_log() Function

## Settings

Settings for the plugin are stored in one Wordpress option as an array.

The Settings feature is composed of two parts:

- Settings Class
    - Defined in `includes/class-settings.php`
    - Setup default settings in this class
    - \PLUGIN_PACKAGE\Settings::get("single-setting-key");
    - \PLUGIN_PACKAGE\Settings::set("setting-key", <setting_value>);
- Admin Settings Tool
    - Defined as an Admin Tool: `admin/tools/settings-tool`
        - Edit the
    - "Settings" can be edited in the "PLUGIN_NAME" left Admin Menu

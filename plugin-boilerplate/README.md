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
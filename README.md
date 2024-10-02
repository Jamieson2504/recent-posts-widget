# My Posts Widget

Contributors: Mike Jamieson\
Author link: https://mike-jamieson.co.uk/

This plugin creates a new widget option for displaying recent posts. By default, this displays 3 posts with options to AJAX filter by category or tag.

However this can be extended using the `apply_filters()` function.

## Filters

1. `apply_filters('widget_title', 'YOUR_TITLE');` will edit the widget title
2. `apply_filters('my_plugin_cat_args', YOUR_NEW_ARGS);` will edit the first taxonomy filter options
3. `apply_filters('my_plugin_tag_args', YOUR_NEW_ARGS);` will edit the second taxonomy filter options
4. `apply_filters('my_plugin_query_args', YOUR_NEW_ARGS);` will edit the main query options


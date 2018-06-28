A WordPress Theme Based on _s and Bootstrap 4
===

A basic Bootstrap 4 Wordpress theme based on [underscores](https://underscores.me/).

- Underscores layouts are bootstrapped.
- Core output is filtered to use bootstrap classes (Comment/search form etc).
- Nav walker for bootstrapped menu included.
- Simple nav walker (For single level menus without excessive markup).
- Comment walker for bootstrapped comments included.
- Includes gulp file to watch/compile/compress scss, js and images.
- Uses [browserSync](https://browsersync.io/) to reload browsers after file changes.
- Includes [lazysizes](https://github.com/aFarkas/lazysizes) for lazyloading srcset images (object-fit plugin enabled as an example).
- Includes [slick slider](http://kenwheeler.github.io/slick/) (optional)

## Requirements

 - node/npm
 - gulp installed globally (`npm install -g gulp`)
 

Getting Started
---------------

**Renaming functions and files:**

1. Search for `'_s'` (inside single quotations) to capture the text domain.
2. Search for `_s_` to capture all the function names.
3. Search for `Text Domain: _s` in `style.css`.
4. Search for <code>&nbsp;_s</code> (with a space before it) to capture DocBlocks.
5. Search for `_s-` to capture prefixed handles.


Then, update the stylesheet header in `style.css`, the links in `footer.php` with your own information and rename `_s.pot` from `languages` folder to use the theme's slug. Next, update or delete this readme.

**Installation**

1. Edit `gulpfile.js` and update the browserSync proxy location to your localhost path
2. Run `npm install` to install dependencies
3. Run `gulp install` to copy library scss files into the main scss folder
4. Run `gulp` to generate compiled assets and watch folders

**Enable Errors**

Whilst in development, it's best practice to enable error messages so that issues can be fixed as they arise.

Edit the wp-config.php file in the root of your site and modify the debug lines to:


    //define('WP_DEBUG', false);
    
    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', true);
    define('WP_DEBUG_DISPLAY', true);
    @ini_set('display_errors', 1);


## Styles

Running `gulp install` copies the main bootstrap.scss file from `node_modules` into the scss folder. It replaces the paths to imported files and imports `_custom.scss` to override default variables.

The original variables file is also copied into the `scss` folder as `_variables-reference.scss`.

`style.scss` imports `bootstrap.scss`. **style.scss is the file to add your custom scss to.**

When running `gulp`, any changes to files in the `scss` folder will regenerate the compiled css files in `assets/css` (compressed) and `css` (nested).

To add your own scss files, add them to the `scss` folder without a `_` prefix.

Only minimal underscores styles are imported but the original files are left here if you need to include anything else.

All styles are enqueued in `functions.php`.

## JavaScript

Adding or editing files in the `js` folder whilst running `gulp` will compile them to the `assets/js` folder.

Bootstrap and Fontawesome are loaded from a cdn.

All scripts are enqueued in `functions.php`.

## Images

Any images added to the `images` folder whilst running `gulp` are automatically compressed and added to the `assets/images` folder.

2 compression types are available. If the default `imagescompress` reduces the quality of your images you can use `imagesreduced` by changing the `watch` task in `gulpfile.js`.

## Custom Functions

Custom functions are added in `inc/template-functions.php`.

This contains modified versions of Underscores functions as well as new ones:
 
- Replacing images in content with lazyloading ones.
- Custom lazy sizes function to generate lazyloaded srcset tags which don't include the default square thumbnail images.
- Overrides for comment form styling.
- Various filters and actions to remove some standard output from the head of the page.
- Overrides for some common plugins to clean up their output.

If you want to enable emoji scripts or oembed, this is the place to look.

## Contributing

Any issues and pull requests for bugs are welcome.

As I use this as a basis for clients websites, feature requests are unlikely to be included (unless they are awesome!). Feel free to fork and add your own features.


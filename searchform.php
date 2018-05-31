<form role="search" method="get" class="search-form form-inline" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label for="search-box" class="sr-only"><?php echo _x( 'Search for:', 'label' ); ?></label>
    <input type="search" id="search-box" class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
  <input type="submit" class="search-submit btn btn-brand" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" />
</form>

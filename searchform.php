<?php
/**
 * Search form
 *
 * @package Obulma
 */

?>
<form role="search" method="get" id="searchform" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php $form_id = wp_rand( 100, 9999 ); ?>
	<div class="field has-addons">
		<div class="control has-icons-left">
			<label class="screen-reader-text" for="<?php echo esc_attr( 's' . $form_id ); ?>"><?php esc_html_x( 'Search for:', 'label', 'obulma' ); ?></label>
			<input type="text" value="<?php the_search_query(); ?>" name="s" id="<?php echo esc_attr( 's' . $form_id ); ?>" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'obulma' ); ?>" class="input" /><span class="icon is-small is-left"><i class="fas fa-search"></i></span>
		</div>
		<div class="control">
			<input type="submit" name="submit" value="<?php esc_attr_e( 'Search', 'obulma' ); ?>" class="button is-link" />
		</div>
	</div>
</form>

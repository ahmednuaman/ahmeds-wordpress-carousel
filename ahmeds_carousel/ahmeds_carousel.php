<?php
/*
Plugin Name: Ahmed's Carousel
Plugin URI: https://github.com/ahmednuaman/ahmeds-wordpress-carousel
Description: This is a simple carousel plugin
Version: 1
Author: Ahmed Nuaman
Author URI: http://www.ahmednuaman.com
License: GPL
*/

error_reporting( E_ALL ^ E_NOTICE );

add_action( 'admin_menu',	'ahmeds_carousel_menu' );
add_action( 'wp_footer',	'ahmeds_carousel_footer' );

function ahmeds_carousel()
{
	$carousel_slides 	= get_option( 'carousel_slides' );
	
	?><div class="ahmeds_carousel"><ul><?php
	
	for ( $x = 0; $x < count( $carousel_slides[ 0 ][ 'hidden' ] ); $x++ )
	{
		$image_url	= $carousel_slides[ 0 ][ 'image_url' ][ $x ];
		$image_cta	= $carousel_slides[ 0 ][ 'image_cta' ][ $x ];
		$html 		= $carousel_slides[ 0 ][ 'html' ][ $x ];
		
		if ( !$image_url && !$html )
		{
			continue;
		}
		
		?><li><div><?php
			if ( $html )
			{
				echo $html;
			}
			else
			{
				?>
					<a href="<?=$image_cta;?>" style="background-image: url(<?=$image_url;?>)"></a>
				<?php
			}
		?></div></li><?php
	}
	
	?></ul></div><?php
}

function ahmeds_carousel_menu()
{
	add_menu_page( 'Carousel Settings', 'Carousel Settings', 'manage_options', __FILE__, '_ahmeds_carousel_admin' );
	
	add_action( 'admin_init', 'ahmeds_carousel_register' );
}

function ahmeds_carousel_footer()
{
	$carousel_vars	= get_option( 'carousel_vars' );
	?>
	<script src="/wp-content/plugins/ahmeds_carousel/ahmeds_carousel.js"></script>
	<script>
	<!--
		<?php echo $carousel_vars[ 'animation_ease' ] ? 'AHMEDS_CAROUSEL.animationEase = \'' . $carousel_vars[ 'animation_ease' ] . '\';' : '' ?>
		<?php echo $carousel_vars[ 'animation_interval' ] ? 'AHMEDS_CAROUSEL.animationIntervalTime = \'' . $carousel_vars[ 'animation_interval' ] . '\';' : '' ?>
		<?php echo $carousel_vars[ 'animation_time' ] ? 'AHMEDS_CAROUSEL.animationTime = \'' . $carousel_vars[ 'animation_time' ] . '\';' : '' ?>
		AHMEDS_CAROUSEL.startCarousel();
	-->
	</script>
	<?php
}

function ahmeds_carousel_register()
{
	register_setting( '_ahmeds_carousel_setting', 'carousels' );
	register_setting( '_ahmeds_carousel_setting', 'carousel_slides' );
	register_setting( '_ahmeds_carousel_setting', 'carousel_vars' );
}

function _ahmeds_carousel_table($carousel_slide=false)
{
	$carousel_slide	= !$carousel_slide ? array() : $carousel_slide;
?>
<table class="form-table" style="width: 600px">
	<tr>
		<th>Image URL</th>
		<td><input type="text" name="carousel_slides[0][image_url][]" value="<?=$carousel_slide[ 'image_url' ];?>" /></td>
	</tr>
	<tr>
		<th>Image Click Through</th>
		<td><input type="text" name="carousel_slides[0][image_cta][]" value="<?=$carousel_slide[ 'image_cta' ];?>" /></td>
	</tr>
	<tr>
		<th colspan="2" style="text-align: center"><strong>Or</strong></th>
	</tr>
	<tr>
		<th>HTML</th>
		<td>
			<textarea name="carousel_slides[0][html][]" rows="8" cols="40"><?=$carousel_slide[ 'html' ];?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="hidden" name="carousel_slides[0][hidden][]" value="1" />
			<p><input type="button" name="" value="- Remove slide" class="removelink button-secondary" /></p>
		</td>
	</tr>
</table>
<?php
}

function _ahmeds_carousel_admin()
{
	wp_enqueue_script( 'jquery' ); 
?>
<div class="wrap">
	<h2>Carousel</h2>
	<p>So, here we are! To add a carousel slide, simply enter the slide's details (so an image's URL and click through link or some HTML, that'll allow you to add buttons and what not) and save. So you may be thinking what happens if you enter both an image and some HTML? Well, my good sir/madam, the HTML <strong>always</strong> overrides the image.</p>
	<p>You can insert your carousel simply by calling [ahmeds_carousel] in your post/page or ahmeds_carousel() in your template.</p>
	<form action="options.php" method="post">
		<?php settings_fields( '_ahmeds_carousel_setting' ); ?>
		<input type="hidden" name="page_options" value="carousel_slides,carousel_vars" />
		<?php $carousel_slides 	= get_option( 'carousel_slides' );
		for ( $x = 0; $x < count( $carousel_slides[ 0 ][ 'hidden' ] ); $x++ )
		{
			$d	= array(
				'image_url'	=> $carousel_slides[ 0 ][ 'image_url' ][ $x ],
				'image_cta'	=> $carousel_slides[ 0 ][ 'image_cta' ][ $x ],
				'html'		=> $carousel_slides[ 0 ][ 'html' ][ $x ]
			);
			
			if ( !$d[ 'image_url' ] && !$d[ 'html' ] )
			{
				continue;
			}
			
			_ahmeds_carousel_table( $d );
		}
		_ahmeds_carousel_table(); ?>
		<p><input type="button" name="" value="+ Add slide" class="addlink button-secondary" /></p>
		<p>Options</p>
		<?php $carousel_vars	= get_option( 'carousel_vars' ); ?>
		<table class="form-table" style="width: 600px">
			<tr>
				<th>jQuery UI Ease Function</th>
				<td><input type="text" name="carousel_vars[animation_ease]" value="<?=$carousel_vars[ 'animation_ease' ];?>" /></td>
			</tr>
			<tr>
				<th>Animation Interval Time (milliseconds)</th>
				<td><input type="text" name="carousel_vars[animation_interval]" value="<?=$carousel_vars[ 'animation_interval' ];?>" /></td>
			</tr>
			<tr>
				<th>Animation Time (milliseconds)</th>
				<td><input type="text" name="carousel_vars[animation_time]" value="<?=$carousel_vars[ 'animation_time' ];?>" /></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>
<script type="text/javascript">
<!--
	jQuery( document ).ready( function()
	{
		applyAddLink();
		
		applyRemoveLinks();
	});
	
	function applyAddLink()
	{
		jQuery( '.addlink' ).click( function()
		{
			jQuery( this ).parent().before( jQuery( this ).parent().prev().clone() );
			
			applyRemoveLinks();
		});
	}
	
	function applyRemoveLinks()
	{
		jQuery( '.removelink' ).click( function()
		{
			var table 	= jQuery( this ).parents( 'table' ).eq( 0 );
			
			if ( jQuery( 'table', table.parent() ).length > 1 )
			{
				var check = confirm('Woah, are you sure you want to remove this slide? **There is NO undo!!**');

				if ( check )
				{
					table.remove();
				}
			}
		});
	}
-->
</script>
<?php
}
?>
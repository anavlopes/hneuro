<?php global $post, $posts, $cls, $blog_layout, $imedica_opts;
$thumbnail_size = 'blog-medium-image';
$post_id        = $post->ID;
$no_img_class   = imedica_if_has_content( $post_id );
$new_cls        = '';
if ( is_sticky( $post_id ) ) {
	$new_cls .= 'sticky';
} else {
	$new_cls .= '';
}

$var = '';
if ( isset( $imedica_opts['blog-appear-animation'] ) && $imedica_opts['blog-appear-animation'] == 1 ) {
	$appear_animation   = isset( $imedica_opts['blog-appear-animation-select'] ) ? $imedica_opts['blog-appear-animation-select'] : '';
	$animation_delay    = isset ( $imedica_opts['imedica-blog-animation-delay'] ) ? $imedica_opts['imedica-blog-animation-delay'] : '';
	$animation_duration = isset( $imedica_opts['imedica-blog-animation-duration'] ) ? $imedica_opts['imedica-blog-animation-duration'] : '';
	$var                = 'data-animate=' . esc_attr( $appear_animation ) . ' data-animation-delay=' . esc_attr( $animation_delay ) . ' data-animation-duration=' . esc_attr( $animation_duration ) . '';
}
?>
<div class="blog-medium-image-wrapper <?php echo esc_attr( $no_img_class ); ?>" <?php echo esc_attr( $var ) ?>>
	<article id="post-<?php the_ID(); ?>" <?php post_class( array( $cls, $new_cls, 'post-item' ) ); ?>>
		<?php
		if ( has_post_format( 'image' ) ) {
			$img = imedica_post_img_grid( $thumbnail_size );
		} else {
			$img = '';
		}
		$img_class = '';

		if ( get_the_post_thumbnail( $post_id ) != '' || $img != '' ) {
			$img_class = 'imd_has_featured_image';
		}
		?>
		<?php if ( $no_img_class != 'no_img' ): ?>
			<div class="blog-medium-post-img col-sm-4 col-md-4 col-lg-4 col-xs-12">

				<?php
				if ( ! has_post_format( 'quote' ) && ! has_post_format( 'link' ) && ! has_post_format( 'aside' ) && ! has_post_format( 'gallery' ) ) {
					echo '<div class="post-thumb text-center ' . $img_class . '">';
					// echo '<div class="img-overlay"></div>';
				}
				?>
				<?php
				if ( ! post_password_required() && ! is_attachment() && ! has_post_format( 'aside' ) && ! has_post_format( 'image' ) && ! has_post_format( 'video' ) && ! has_post_format( 'gallery' ) && ! has_post_format( 'quote' ) && ! has_post_format( 'audio' ) ) :
					if ( get_the_post_thumbnail( $post_id ) != '' ) {
						echo "<a href='".get_permalink($post_id)."'>";
						the_post_thumbnail( $thumbnail_size );
						echo "</a>";
					}
				endif;
				if ( has_post_format( 'gallery' ) ) {
					$gallery = get_post_gallery( $post_id, false );			
				}
				if ( has_post_format( 'image' ) ) {
					echo "<a href='".get_permalink($post_id)."'>";
					echo '<img src="' . imedica_post_img_grid( $thumbnail_size ) . '">';
					echo "</a>";
				}
				if ( has_post_format( 'video' ) ) :
					echo imedica_post_video_grid();
				endif;
				if ( has_post_format( 'audio' ) ) :
					echo do_shortcode( grid_audio_files() );// grid_audio_files();
				endif;
				?>
				<?php
				if ( ! has_post_format( 'quote' ) && ! has_post_format( 'link' ) && ! has_post_format( 'aside' ) && ! has_post_format( 'gallery' ) ) {
					echo '</div>';
				} ?>
			</div>
		<?php endif ?>

		<div class="blog-med-img-content col-sm-8 col-md-8 col-lg-8 col-xs-12">
			<header class="entry-header">
				<h2 class="entry-title">
					<?php if ( is_single() ) : ?>
						<?php the_title(); ?>
					<?php else : ?>
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					<?php endif; ?>
				</h2>

				<div class="clearfix"></div>
				<p class="post-meta">
					<?php
					global $imedica_opts;
					$blog_meta = $imedica_opts['imedica-blog-meta']['enabled'];
					if ( $blog_meta ):
						foreach ( $blog_meta as $meta => $val ) {
							switch ( $meta ) {
								case 'author':
									echo __( 'By ', 'imedica' );
									esc_url( the_author_posts_link() );
									echo ' | ';
									break;
								case 'date':
									echo esc_attr( get_the_date() );
									echo ' | ';
									break;
								case 'like':
									echo getPostLikeLink( get_the_ID(), 'span' );
									echo ' | ';
									break;
								case 'comments':
									if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
										echo comments_popup_link( __( 'Leave a comment', 'imedica' ), __( '1 Comment ', 'imedica' ), __( '% Comments ', 'imedica' ) );
										echo ' | ';
									endif;
									break;
								case 'category':
									$cat = get_the_category_list();
									if ( $cat ) {
										echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'imedica' ) );
										echo ' | ';
									}
									break;
								case 'tag':
									$tags = get_the_tag_list();
									if ( $tags ) {
										echo get_the_tag_list( '', __( ', ', 'imedica' ) );
										echo ' | ';
									}
									break;
							}
						}
						if ( is_user_logged_in() ) {
							edit_post_link( __( 'Edit', 'imedica' ), '<span class="edit-link">', '</span>' );
							echo ' | ';
						}
					endif;
					?>
				</p>
				<!-- .post-meta -->
			</header>
			<!-- .entry-header -->
			<?php if (has_post_format( 'aside' )) { ?>
			<div class="aside-wrap"> <?php } ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div>
				<!-- .entry-summary -->
				<?php if (has_post_format( 'aside' )) { ?> </div>
			<!-- aside wrap --> <?php } ?>
			<?php if ( array_key_exists( 'link', $blog_meta ) ) { ?>
				<div class="read-more-link" style="display:inline-block;"><a href="<?php the_permalink() ?>" class="read-more-link" rel="bookmark"><?php _e("Read More >", "imedica"); ?></a></div>
				<?php } ?>
		</div>

		<!-- blog-med-img-content -->
	</article>
	<!-- #post -->
</div>
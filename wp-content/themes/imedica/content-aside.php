<?php
/**
 * The template for displaying posts in the Aside post format
 *
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="aside">
		<div class="aside-wrap">
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

			<div class="entry-content">
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'imedica' ) ); ?>
			</div>
			<!-- .entry-content -->
		</div>
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
			<time datetime="<?php the_time('Y-m-d') ?>" class="entry-date published"><?php the_time('Y-m-d'); ?></time>
            <time datetime="<?php the_modified_time('d F Y'); ?>" class="entry-date updated"><?php the_modified_time('d F Y'); ?></time>
		</p>
	</div>
	<!-- .aside -->

</article><!-- #post -->

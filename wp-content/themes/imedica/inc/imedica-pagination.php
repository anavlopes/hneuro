<?php
if ( ! function_exists( 'imedica_pagination' ) ) {

	function imedica_pagination( $pages = '', $range = 2 ) {
		global $imedica_opts;
		if ( isset( $imedica_opts["blog-infinite-scroll"] ) ) {
			$infinite_scroll = $imedica_opts["blog-infinite-scroll"];
		} else {
			$infinite_scroll = false;
		}
		if ( $infinite_scroll ) {
			return false;
		}
		ob_start();
		$showitems = ( $range * 2 ) + 1;
		global $paged;
		if ( empty( $paged ) ) {
			$paged = 1;
		}
		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}
		if ( 1 != $pages ) {
			echo "<div class='imedica-pagination'>";
			if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
				echo "<a class='paginate-first' href='" . get_pagenum_link( 1 ) . "'>&laquo;</a>";
			}
			if ( $paged > 1 && $showitems < $pages ) {
				echo "<a class='paginate-prev' href='" . get_pagenum_link( $paged - 1 ) . "'>&lsaquo;</a>";
			}
			for ( $i = 1; $i <= $pages; $i ++ ) {
				if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					echo ( $paged == $i ) ? "<span class='current'>" . $i . "</span>" : "<a href='" . get_pagenum_link( $i ) . "' class='inactive' >" . $i . "</a>";
				}
			}
			if ( $paged < $pages && $showitems < $pages ) {
				echo "<a class='paginate-next' href='" . get_pagenum_link( $paged + 1 ) . "'>&rsaquo;</a>";
			}
			if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
				echo "<a class='paginate-last' href='" . get_pagenum_link( $pages ) . "'>&raquo;</a>";
			}
			echo "</div>\n";
		}

		echo ob_get_clean();
	}

}


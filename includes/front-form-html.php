<form class="posts-filter" method="GET" id="post-filter-form">

	<div class="search_posts">
		
		<div class="search_keywords">
			<label for="search_keywords"><?php _e( 'Keywords', 'wpcq' ); ?></label>
			<input type="text" name="search_keywords" id="search_keywords" placeholder="Keywords" value="<?php echo ( isset( $_GET['search_keywords'] ) && !empty( $_GET['search_keywords'] ) ) ? esc_attr( $_GET['search_keywords'] ) : ''; ?>">
		</div>

		<div class="search_category">
			<label for="select_category"><?php _e( 'Select Category', 'wpcq' ); ?></label>
			<select name="select_category" id="select_category">
				<option value="">--</option>
				<?php 
					if ( !empty( $taxonomy_terms ) ) {
						foreach ( $taxonomy_terms as $term_value ) {
							$selected = '';

							if ( isset( $_GET['select_category'] ) && !empty( $_GET['select_category'] ) && $_GET['select_category'] == $term_value->term_id ) {
							 	$selected = 'selected="selected"';
							 }
							?>
							<option value="<?php echo $term_value->term_id; ?>" <?php echo $selected; ?> >
								<?php echo $term_value->name; ?>
							</option>
							<?php
						}
					}
				?>
			</select>
		</div>
	</div>
	
	<div class="clear"></div>

		<div class="search_sort_by">
			<label class="sort-label"><?php _e( 'Order By', 'wpcq' ); ?></label>
				<div class="sort-items">
					<label class="wpcq-label">
						<input class="field-input post-order-by" class="post-sort-by" type="radio" name="sort_by" value="title" <?php
							if ( isset( $_GET['sort_by'] ) && !empty( $_GET['sort_by'] ) && 'title' == $_GET['sort_by'] ) {
								echo 'checked="checked"';											
							}
						?> >
						<span class="field-text"><?php _e( 'Title', 'wpcq' ); ?></span>
					</label>
				</div>
				<div class="sort-items">
					<label class="wpcq-label">
						<input class="field-input post-order-by" class="post-sort-by" type="radio" name="sort_by" value="date" <?php
							if ( isset( $_GET['sort_by'] ) && !empty( $_GET['sort_by'] ) && 'date' == $_GET['sort_by'] ) {
								echo 'checked="checked"';
							}
						?> >
						<span class="field-text"><?php _e( 'Date', 'wpcq' ); ?></span>
					</label>
				</div>
				<div class="sort-items">
					<label class="wpcq-label">
						<input class="field-input post-order-by" class="post-sort-by" type="radio" name="sort_by" value="modified" <?php
							if ( isset( $_GET['sort_by'] ) && !empty( $_GET['sort_by'] ) && 'modified' == $_GET['sort_by'] ) {
								echo 'checked="checked"';
							}
						?> >
						<span class="field-text"><?php _e( 'Modified', 'wpcq' ); ?></span>
					</label>
				</div>
		</div>

		<div class="clear"></div>

		<div class="search_order_by">
			<label class="order-label"><?php _e( 'Order', 'wpcq' ); ?></label>
				<div class="order-items">
					<label class="wpcq-label">
						<input class="field-input post-order" class="post-order-by" type="radio" name="order" value="ASC" <?php
							if ( isset( $_GET['order'] ) && !empty( $_GET['order'] ) && 'ASC' == $_GET['order'] ) {
								echo 'checked="checked"';
							}
						?>>
						<span class="field-text"><?php _e( 'ASC', 'wpcq' ); ?></span>
					</label>
				</div>
				<div class="order-items">
					<label class="wpcq-label">
						<input class="field-input post-order" class="post-order-by" type="radio" name="order" value="DESC" <?php
							if ( isset( $_GET['order'] ) && !empty( $_GET['order'] ) && 'DESC' == $_GET['order'] ) {
								echo 'checked="checked"';
							}
						?>>
						<span class="field-text"><?php _e( 'DESC', 'wpcq' ); ?></span>
					</label>
				</div>
		</div>

		<div class="clear"></div>
		
		<div class="wpcq-submit">
			<input type="submit" name="submit_query" value="Submit">
		</div>
		<div class="clear"></div>
</form>
<!-- end of query form -->
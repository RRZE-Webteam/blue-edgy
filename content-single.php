<h2 class="ym-skip"><a name="contentmarke" id="contentmarke"><?php _e( 'Inhalt', 'blue-edgy'); ?></a></h2>
<?php while( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <header class="entry-header">
            <h1><?php the_title(); ?></h1>
            <?php if( 'post' == get_post_type() ) : ?>
                <div class="entry-meta">
                    <?php echo Theme_Tags::posted_on(); ?>
                </div>
            <?php endif; ?>   
        </header>

        <div class="entry-content">
            <?php the_content( __( 'Weiterlesen <span class="meta-nav">&rarr;</span>', 'blue-edgy' ) ); ?>
            <?php wp_link_pages( array( 'before' => '<nav id="nav-pages"><div class="ym-wbox"><span>' . __( 'Seiten:', 'blue-edgy' ) . '</span>', 'after' => '</div></nav>' ) ); ?>
        </div>

        <footer class="entry-footer">
            <div class="ym-wbox">
                <?php
                $categories_list = get_the_category_list(', ');

                $tag_list = get_the_tag_list('', ', ');
                if( '' != $tag_list ) {
                    $utility_text = __('Dieser Eintrag wurde veröffentlicht in %1$s und verschlagwortet mit %2$s von <a href="%4$s">%3$s</a>.', 'blue-edgy' );
                } elseif( '' != $categories_list ) {
                    $utility_text = __( 'Dieser Eintrag wurde veröffentlicht in %1$s von <a href="%4$s">%3$s</a>.', 'blue-edgy' );
                } else {
                    $utility_text = __( 'Dieser Eintrag wurde von <a href="%4$s">%3$s</a> veröffentlicht.', 'blue-edgy' );
                }

                printf($utility_text, $categories_list, $tag_list, get_the_author(), esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) );
                
                $permalink = sprintf(__('<a href="%1$s" title="Permalink zu %2$s" rel="bookmark">Permanenter Link zum Eintrag</a>', 'rrze'), esc_url( get_permalink()), the_title_attribute( 'echo=0' ));
                printf('<span class="permalink">%s</span>', $permalink);
                ?>
                <?php edit_post_link( __( '(Bearbeiten)', 'blue-edgy' ), '<span class="edit-link">', '</span>' ); ?>
            </div>
        </footer>

    </article>

    <nav id="nav-single">
        <div class="ym-wbox">
            <h3 class="ym-skip"><?php _e( 'Artikelnavigation', 'blue-edgy' ); ?></h3>
            <div class="nav-previous"><?php previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Vorherige', 'blue-edgy' ) ); ?></div>
            <div class="nav-next"><?php next_post_link( '%link', __( 'Nächste <span class="meta-nav">&rarr;</span>', 'blue-edgy' ) ); ?></div>
        </div>
    </nav>

    <?php comments_template( '', true ); ?>

<?php endwhile; ?>

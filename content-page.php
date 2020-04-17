<h2 class="ym-skip"><a name="contentmarke" id="contentmarke"><?php _e( 'Inhalt', 'blue-edgy' ); ?></a></h2>

<?php while( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if( ! get_post_meta( get_the_ID(), '_titel_ausblenden', true ) ) : ?>
        <header class="entry-header">
            <h1><?php the_title(); ?></h1>           
        </header>
        <?php endif; ?>

        <div class="entry-content">
            <?php the_content( __( 'Weiterlesen <span class="meta-nav">&rarr;</span>', 'blue-edgy' ) ); ?>
            <?php wp_link_pages( array( 'before' => '<nav id="nav-pages"><div class="ym-wbox"><span>' . __( 'Seiten:', 'blue-edgy' ) . '</span>', 'after' => '</div></nav>' ) ); ?>
        </div>

        <footer class="entry-meta">
            <?php edit_post_link( __( '(Bearbeiten)', 'blue-edgy' ), '<div class="ym-wbox"><span class="edit-link">', '</span></div>' ); ?>
        </footer>
    </article>

    <?php 
    $options = _rrze_theme_options();
    if ((isset($options['comments.pages'])) && ($options['comments.pages']==true)) {
	comments_template( '', true );
    }
    ?>

<?php endwhile; ?>

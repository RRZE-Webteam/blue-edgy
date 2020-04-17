<?php get_header(); ?>

<div class="ym-column linearize-level-1">

    <div class="ym-cbox">
        <h2 class="ym-skip"><a name="contentmarke" id="contentmarke"><?php _e( 'Inhalt', 'blue-edgy' ); ?></a></h2>
        <article id="post-0" class="post error404 not-found">
            <header class="entry-header">
                <h1 class="entry-title"><?php _e('Seite nicht gefunden', 'blue-edgy'); ?></h1>
            </header>

            <div class="entry-content">
                <p><?php _e( 'Ihre Suche war leider erfolglos. Vielleicht hilft die Suchfunktion oder einer der unten angegebenen Links das Gewünschte zu finden.', 'blue-edgy' ); ?></p>

                <?php the_widget( 'WP_Widget_Recent_Posts', array( 'number' => 10), array( 'before_title' => '<h4 class="widgettitle">', 'after_title' => '</h4>', 'widget_id' => '404' ) ); ?>

                <div class="widget">
                    <h4 class="widgettitle"><?php _e( 'Oft verwendete Kategorien', 'blue-edgy' ); ?></h4>
                    <ul>
                        <?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
                    </ul>
                </div>

                <?php the_widget( 'WP_Widget_Archives', array( 'count' => 0, 'dropdown' => 1 ), array( 'before_title' => '<h4 class="widgettitle">', 'after_title' => sprintf( '</h4><p>%s</p>', __( 'Versuchen Sie es mit einem Blick in die Monatsarchive.', 'blue-edgy' ) ) ) ); ?>

                <?php the_widget( 'WP_Widget_Tag_Cloud', array(), array( 'before_title' => '<h4 class="widgettitle">', 'after_title' => '</h4>' ) ); ?>

            </div>
        </article>
    </div>

</div>

<?php get_footer(); ?>
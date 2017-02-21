<?php while ( have_posts() ) : the_post(); ?>
    <article id="person-<?php the_ID(); ?>">
        <header class="entry-header">
            <h1><?php the_title(); ?></h1>           
        </header>
        <div class="entry-content">
        <?php 
            $id = $post->ID;
            echo FAU_Person_Shortcodes::fau_person_page($id, 1);
        ?>
        </div>
        <footer class="entry-meta">
            <?php edit_post_link( __( '(Bearbeiten)', RRZE_Theme::textdomain ), '<div class="ym-wbox"><span class="edit-link">', '</span></div>' ); ?>
        </footer>
    </article>
<?php endwhile; ?>


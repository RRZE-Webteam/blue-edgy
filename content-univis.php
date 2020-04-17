<?php 
global $univis_data; 

while ( have_posts() ) : the_post(); ?>
    <article id="person-<?php the_ID(); ?>">
        <header class="entry-header">
            <h1><?php the_title(); ?></h1>           
        </header>
        <div class="entry-content">
        <?php             
            echo $univis_data;
        ?>
        <p></p>
        <nav id="nav-pages">
            <div class="navmenu-previous"><a href="<?php echo get_permalink();?>"><?php _e('<span class="meta-nav">&laquo;</span> Zurück zur Übersicht', RRZE_UnivIS::textdomain); ?></a></div>
        </nav>
        </div>
        <footer class="entry-meta">
            <?php edit_post_link( __( '(Bearbeiten)', 'blue-edgy' ), '<div class="ym-wbox"><span class="edit-link">', '</span></div>' ); ?>
        </footer>
    </article>
<?php endwhile; ?>


<h2 class="ym-skip"><a name="contentmarke" id="contentmarke"><?php _e( 'Inhalt', 'blue-edgy' ); ?></a></h2>
<?php while( have_posts() ) : the_post(); ?>
    <?php get_template_part( 'content', get_post_format() ); ?>
<?php endwhile; ?>

<?php echo Theme_Tags::pages_nav(); ?>

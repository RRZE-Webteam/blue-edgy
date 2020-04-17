<div class="ym-cbox-right">
    <?php if( ! dynamic_sidebar( 'sidebar-right' ) ) : ?>
    <div class="widget-wrapper ym-vlist widget_meta">
        <h6 class="widget-title"><?php _e( 'Meta', 'blue-edgy' ); ?></h6>
        <ul>
            <?php wp_register(); ?>
            <li><?php wp_loginout(); ?></li>
            <?php wp_meta(); ?>
        </ul>
    </div>
    <?php endif; ?>
    <?php if( is_blogs_fau_de() && _rrze_theme_options( 'column.layout' ) != '1-2-3' ) : ?>
    <div class="fau-logo">
        <a href="http://www.fau.de">
            <img src="<?php printf( '%s/images/fau-logo.png', get_stylesheet_directory_uri() ); ?>" alt="Friedrich-Alexander-Universität Erlangen-Nürnberg"/>
        </a>
    </div>    
    <div class="network-logo">
        <a href="<?php echo esc_url( network_site_url( '/', 'http' ) ); ?>">
            <img src="<?php printf( '%s/images/network-logo.png', get_stylesheet_directory_uri() ); ?>" alt="FAU-Blogdienst"/>
        </a>
    </div>
    <?php endif; ?>
</div>


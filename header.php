<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <title><?php wp_title(''); ?></title>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />	
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0" />
        <meta name="generator" content="<?php echo sprintf('WordPress %s', $GLOBALS['wp_version']); ?>" />
        <link rel="canonical" href='http://<?php echo $_SERVER["HTTP_HOST"] ?><?php echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>' />
        <meta name="description" content="<?php global $pagename; echo $pagename ? $pagename : get_the_title(); ?>" />
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <!--[if lt IE 9]>
        <?php printf( '<script type="text/javascript" src="%s/js/html5shiv.js?v=3.6RC1"></script>', get_template_directory_uri() );?>
        <![endif]-->
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <div id="kopf">
            <?php global $current_user; ?>
            <ul class="ym-skiplinks<?php if( _get_admin_bar_pref( 'front', $current_user->ID ) ) echo ' sprungmarken'; ?>">
                <li><a class="ym-skip" href="#contentmarke"><?php _e( 'Zum Inhalt springen', 'blue-edgy' ); ?></a></li>
                <li><a class="ym-skip" href="#bereichsmenumarke"><?php _e( 'Zum Bereichsmenü springen', 'blue-edgy' ); ?></a></li>
                <li><a class="ym-skip" href="#hilfemarke"><?php _e( 'Zu den allgemeinen Informationen springen', 'blue-edgy' ); ?></a></li>
                <?php if ( is_active_sidebar( 'sidebar-footer-left' ) || is_active_sidebar( 'sidebar-footer-center' ) || is_active_sidebar( 'sidebar-footer-right' ) ) : ?>
                <li><a class="ym-skip" href="#zusatzinfomarke"><?php _e( 'Zu den Zusatzinformationen springen', 'blue-edgy' ); ?></a></li>
                <?php endif; ?>
            </ul>
            <div id="title">
                <?php if( display_header_text() ): ?>
                <div class="ym-wrapper">
                    <?php 
                    $header_class = '';
                    if (_rrze_theme_options('header.layout')) {
                        switch (_rrze_theme_options('header.layout')) {
                            case 'top-left': break;
                            case 'middle-left': $header_class = ' middle-left'; break;
                            case 'bottom-left': $header_class = ' bottom-left'; break;
                            case 'top-center': $header_class = ' top-center'; break;
                            case 'top-right': $header_class = ' float-right top-right'; break;
                            case 'middle-right': $header_class = ' middle-right'; break;
                            case 'bottom-right': $header_class = ' bottom-right'; break;
                        }
                    }
                    printf('<div class="ym-wbox header-block%s">', $header_class);                                               
                    ?>
                        <h1 id="site-title"><span>
                            <?php 
                            $blogname = esc_attr( get_bloginfo( 'name', 'display' ) );
                            $home = esc_url( home_url( '/' ) );
                            echo is_home() || is_front_page() ? $blogname : sprintf('<a href="%1$s" rel="home">%2$s</a>' , $home, $blogname);
                            ?>
                        </span></h1>
                        <?php if( get_bloginfo( 'description', 'display' ) ) : ?>
                        <h3 id="site-description"><span><?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?></span></h3>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <nav id="bereichsmenu">
                <div class="ym-wrapper">
                    <h2 class="ym-skip"><a name="bereichsmenumarke" id="bereichsmenumarke"><?php _e( 'Bereichsmenü', 'blue-edgy' ); ?></a></h2>
                    <?php echo Theme_Tags::bereichsmenu(); ?>
                </div>
            </nav>
            <?php if( ! is_404() ): ?>
            <nav id="breadcrumb">
                <div class="ym-wrapper">
                    <h3 class="ym-skip"><a name="breadcrumbmarke" id="breadcrumbmarke"><?php _e( 'Breadcrumb', 'blue-edgy' ); ?></a></h3>
                    <div class="ym-hlist">
                        <?php echo Theme_Tags::breadcrumb_nav(); ?>
                    </div>
                </div>
            </nav>
            <?php endif; ?>
        </div>
        <div id="main">
            <div class="ym-wrapper">
                <div class="ym-wbox">

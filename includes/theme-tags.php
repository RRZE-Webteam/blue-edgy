<?php

class Theme_Tags {

    public static function search_form( $formclass = 'ym-searchform', $fieldclass = 'ym-searchfield', $buttonclass = 'ym-searchbutton' ) {
        $form = sprintf(
            '<form class="%s" role="search" method="get" id="searchform" action="%s" >
                <input class="%s" type="search" placeholder="%s" value="%s" name="s" id="s" />
                <input class="%s" type="submit" value="%s" />
            </form><div class="ym-clearfix"></div>', $formclass, esc_url( home_url( '/' ) ), $fieldclass, esc_attr__( 'Suchen...', 'blue-edgy' ), get_search_query(), $buttonclass, esc_attr__( 'Suchen', 'blue-edgy' ) );
        return $form;
    }

    public static function breadcrumb_nav() {
        global $post;

        $list = sprintf( '<ul><li><span>%s</span></li>', __( 'Sie befinden sich hier:', 'blue-edgy' ) );

        if ( ! is_front_page() ) {
            $list .= sprintf( '<li><a href="%s">%s</a><span>»</span></li>', home_url(), __('Startseite', 'blue-edgy' ) );

            if ( is_category() ) {
                $list .= sprintf( '<li><span>%s %s</span></li>', __('Kategorie', 'blue-edgy' ), single_cat_title( '', false) );

            } elseif ( is_tag() ) {
                $list .= sprintf( '<li><span>%s %s</span></li>', __('Tag', 'blue-edgy' ), single_cat_title( '', false) );

            } elseif ( is_archive() ) {
                $list .= sprintf( '<li><span>%s %s</span></li>', __( 'Archive', 'blue-edgy' ), single_cat_title( '', false) );

            } elseif ( is_author() ) {
                $list .= sprintf( '<li><span>%s %s</span></li>', __( 'Autor', 'blue-edgy' ), single_cat_title( '', false) );

            } elseif ( is_single() ) {
                if ( get_option( 'page_for_posts') )
                    $list .= sprintf( '<li><a href="%s">%s</a><span>»</span></li>', get_permalink( get_option( 'page_for_posts' ) ), get_the_title( get_option( 'page_for_posts' ) ) );
                $list .= sprintf( '<li><span>%s</span></li>', get_the_title( $post->ID) );

            } elseif ( ( is_home() || is_date () ) && get_option( 'page_for_posts' ) ) {
                $list .= sprintf( '<li><span>%s</span></li>', get_the_title(get_option( 'page_for_posts') ) );

            } elseif ( is_page() ) {
                if ( $post->post_parent ) {
                    $home = get_page( $post->ID );
                    for ( $i = count( $post->ancestors ) - 1; $i >= 0; $i-- ) {
                        if ( $home->ID != $post->ancestors[$i] ) {
                            $list .= sprintf( '<li><a href="%s">%s</a><span>»</span></li>', get_permalink( $post->ancestors[$i] ), get_the_title( $post->ancestors[$i] ) );
                        }
                    }
                }
                $list .= sprintf( '<li><span>%s</span></li>', get_the_title( $post->ID ) );

            } elseif ( is_search() ) {
                $list .= sprintf( '<li><span>%s</span></li>', sprintf( __( 'Suchergebnisse für: %s', 'blue-edgy' ), '<span>' . get_search_query() . '</span>') );
            }
        } else {
            //$list .= '<li><span></span></li>';
            $list .= sprintf( '<li><span>%s</span></li>', __( 'Startseite', 'blue-edgy' ) );
        }
        $list .= '</ul>';

        return $list;
    }

    public static function pages_nav() {
        global $wp_query;

        if ( $wp_query->max_num_pages > 1 ) :
            ?>

            <nav id="nav-pages">
                <div class="ym-wbox">
                    <h3 class="ym-skip"><?php _e( 'Suchergebnissenavigation', 'blue-edgy' ); ?></h3>
                    <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Vorherige', 'blue-edgy' ) ); ?></div>
                    <div class="nav-next"><?php previous_posts_link( __( 'Nächste <span class="meta-nav">&rarr;</span>', 'blue-edgy' ) ); ?></div>
                </div>
            </nav>

        <?php
        endif;
    }

    public static function posted_on() {
        return sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>', 
                esc_url( get_permalink() ), 
                esc_attr( get_the_time() ), 
                esc_attr( get_the_date('c') ), 
                esc_html( get_the_date() )
        );
    }

    public static function comment_form( $args = array(), $post_id = null ) {
        global $id;

        if ( null === $post_id )
            $post_id = $id;
        else
            $id = $post_id;

        $commenter = wp_get_current_commenter();
        $user = wp_get_current_user();
        $user_identity = $user->exists() ? $user->display_name : '';

        $req = get_option( 'require_name_email' );
        $aria_req = ( $req ? " aria-required='true'" : '' );
        $fields =  array(
            'author' => '<div class="comment-form-author ym-fbox ym-fbox-text">' . '<label for="author">' . __( 'Name', 'blue-edgy' ) . ( $req ? '<span class="required-item">*</span>' : '' ) . '</label> ' .
                        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' /></div>',
            'email'  => '<div class="comment-form-email ym-fbox ym-fbox-text"><label for="email">' . __( 'E-Mail', 'blue-edgy' ) . ( $req ? '<span class="required-item">*</span>' : '' ) . '</label> ' . 
                        '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" ' . $aria_req . ' /></div>',
            'url'    => '<div class="comment-form-url ym-fbox ym-fbox-text"><label for="url">' . __( 'Webauftritt', 'blue-edgy' ) . '</label>' .
                        '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" /></div>',
        );

        $required_text = sprintf( ' ' . __( 'Erforderliche Felder sind %s markiert', 'blue-edgy' ), '<span class="required">*</span>' );
        $defaults = array(
            'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
            'comment_field'        => '<div class="comment-form-comment ym-fbox ym-fbox-text"><label for="comment">' . __( 'Kommentar', 'blue-edgy' ) . '</label><textarea id="comment" name="comment" rows="8" aria-required="true"></textarea></div>',
            'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'Sie müssen <a href="%s">angemeldet sein</a>, um einen Kommentar abzugeben.', 'blue-edgy' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
            'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Angemeldet als <a href="%1$s">%2$s</a>. <a href="%3$s" title="Aus diesem account abmelden">Abmelden?</a>', 'blue-edgy' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
            'comment_notes_before' => '<p class="comment-notes">' . __( 'Ihre Email-Adresse wird nicht veröffentlicht.', 'blue-edgy' ) . ( $req ? $required_text : '' ) . '</p>',
            'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'Sie können die folgenden <abbr title="HyperText-Markup-Language">HTML</abbr>-Tags benutzen: %s', 'blue-edgy' ), '<code>' . allowed_tags() . '</code>' ) . '</p>',
            'id_form'              => 'commentform',
            'id_submit'            => 'submit',
            'title_reply'          => __( 'Kommentar hinterlassen', 'blue-edgy' ),
            'title_reply_to'       => __( 'Kommentar an %s hinterlassen', 'blue-edgy' ),
            'cancel_reply_link'    => __( 'Abbrechen', 'blue-edgy' ),
            'label_submit'         => __( 'Kommentar verfassen', 'blue-edgy' ),
        );

        $args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

        if ( comments_open( $post_id ) ) : ?>
            <?php do_action( 'comment_form_before' ); ?>
            <div id="respond" class="ym-noprint">
                <h3 id="reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></h3>
                <?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
                    <?php echo $args['must_log_in']; ?>
                    <?php do_action( 'comment_form_must_log_in_after' ); ?>
                <?php else : ?>
                    <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>" class="ym-form ym-full">
                        <?php do_action( 'comment_form_top' ); ?>
                        <?php if ( is_user_logged_in() ) : ?>
                            <?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
                            <?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
                        <?php else : ?>
                            <?php echo $args['comment_notes_before']; ?>
                            <?php
                            do_action( 'comment_form_before_fields' );
                            foreach ( (array) $args['fields'] as $name => $field ) {
                                echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
                            }
                            do_action( 'comment_form_after_fields' );
                            ?>
                        <?php endif; ?>
                        <?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
                        <?php echo $args['comment_notes_after']; ?>
                        <div class="ym-fbox-footer ym-fbox-button">
                            <input name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
                            <?php comment_id_fields( $post_id ); ?>
                        </div>
                        <?php do_action( 'comment_form', $post_id ); ?>
                    </form>
                <?php endif; ?>
            </div>
            <?php do_action( 'comment_form_after' );
        else :
            do_action( 'comment_form_comments_closed' );
        endif;
    }

    public static function bereichsmenu() {
        $searchform = '';
        $options = RRZE_Theme::$theme_options;
        if( $options['search.form.position'] == 'bereichsmenu' ) {
            $searchform = sprintf('<div class="searchform">%s</div>', self::search_form());
        } else {
            $searchform = '<div class="ym-clearfix"></div>';
        }        
        wp_nav_menu( 
            array( 
                'theme_location' => 'bereichsmenu',
                'container_class' => 'navmenu bereichsmenu',
                'menu_id' => 'menu-menubereichsmenu', 
                'menu_class' => 'dropdown',
                'fallback_cb' => array(__CLASS__, 'bereichsmenu_fallback'),
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>' . $searchform
            ) 
        );        
    }
    
    public static function bereichsmenu_fallback( $args ) {
        // Siehe wp-includes/nav-menu-template.php
        extract( $args );

        $links = array(
            '<a href="' . home_url( '', 'http' ) . '">' . $before . __( 'Startseite', 'blue-edgy' ) . $after . '</a>',
            );

        $li = array();
        foreach( $links as $link ) {
            if ( false !== stripos( $items_wrap, '<ul' ) or false !== stripos( $items_wrap, '<ol' ) )
                $li[] = is_front_page() ? "<li class='current-menu-item'>$link</li>" : "<li>$link</li>";
        }

        $li = implode( PHP_EOL, $li );

        $output = sprintf( $items_wrap, $menu_id, $menu_class, $li );
        if ( ! empty ( $container ) )
            $output  = "<$container class='$container_class' id='$container_id'>$output</$container>";

        if ( $echo )
            echo $output;

        return $output;
    }

    public static function tecmenu() {
        wp_nav_menu( 
            array( 
                'theme_location' => 'tecmenu',
                'container_class' => 'navmenu tecmenu',
                'menu_id' => 'menu-techmenu', 
                'menu_class' => 'dropdown',
                'fallback_cb' => array(__CLASS__, 'tecmenu_fallback')
            ) 
        );        
    }
    
    public static function tecmenu_fallback( $args ) {
        if( ! is_blogs_fau_de() )
            return '';

        global $current_blog, $post;

        if( is_page() )
            $page = get_page( $post->ID );

        // Siehe wp-includes/nav-menu-template.php
        extract( $args );
        $contact_page = __( 'kontakt', 'blue-edgy' );
        $links = array(
            '<li><a href="' . network_site_url( '/', 'https' ) . '">' . $before . __( 'Blogs@FAU', 'blue-edgy' ) . $after . '</a></li>',
            sprintf( is_front_page() && $current_blog->path == '/hilfe/' ? '<li class="current-menu-item">%s</li>' : '<li>%s</li>', '<a href="' . network_site_url( '/hilfe/', 'https' ) . '">' . $before . __( 'Hilfe', 'blue-edgy' ) . '</a>' ),
            sprintf( ! empty( $page ) && $page->post_name == $contact_page ? '<li class="current-menu-item">%s</li>' : '<li>%s</li>', '<a href="' . home_url( '/' . $contact_page . '/', 'https' ) . '">' . $before . __( 'Kontakt', 'blue-edgy' ) . $after . '</a>' ),
            '<li><a href="' . network_site_url( '/impressum/', 'https' ) . '">' . $before . __( 'Impressum', 'blue-edgy' ) . $after . '</a></li>',
            '<li><a href="' . network_site_url( '/datenschutz/', 'https' ) . '">' . $before . __( 'Datenschutz', 'blue-edgy' ) . $after . '</a></li>',
            '<li><a href="' . network_site_url( '/nutzungsbedingungen/', 'https' ) . '">' . $before . __( 'Nutzungsbedingungen', 'blue-edgy' ) . $after . '</a></li>'
            );

        $li = array();
        foreach( $links as $link ) {
            if ( false !== stripos( $items_wrap, '<ul' ) or false !== stripos( $items_wrap, '<ol' ) )
                $li[] = $link;
        }

        $li = implode( PHP_EOL, $li );

        $output = sprintf( $items_wrap, $menu_id, $menu_class, $li );
        if ( ! empty ( $container ) )
            $output  = "<$container class='$container_class' id='$container_id'>$output</$container>";

        if ( $echo )
            echo $output;

        return $output;
    }
    
}
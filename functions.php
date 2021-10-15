<?php

add_action( 'after_switch_theme', array('RRZE_Check_Theme', 'check_theme_setup'));

class RRZE_Check_Theme {
    const theme_name = 'blue_edgy';
    const textdomain = 'blue-edgy';
    const php_version = '7.4'; // Minimal erforderliche PHP-Version
    const wp_version = '5.4'; // Minimal erforderliche WordPress-Version
    public static $check_error = NULL;

    public static function check_theme_setup() { 
        load_theme_textdomain('blue-edgy', get_template_directory() . '/languages');

        $old_theme = get_option('theme_switched');
        self::$check_error = self::version_compare();
        if (self::$check_error) {
            add_action( 'admin_notices', array(__CLASS__, 'admin_notices'));
            switch_theme($old_theme);
            unset( $_GET['activated'] );
            update_option('check_error_' . self::theme_name, 1);            
        } else {
            delete_option('check_error_' . self::theme_name);
        }
    }

    private static function version_compare() {
        $error = '';

        if (version_compare(PHP_VERSION, self::php_version, '<')) {
            $error = sprintf(__('Ihre PHP-Version %s ist veraltet. Bitte aktualisieren Sie mindestens auf die PHP-Version %s.', 'blue-edgy'), PHP_VERSION, self::php_version);
        }

        if (version_compare($GLOBALS['wp_version'], self::wp_version, '<')) {
            $error = sprintf(__('Ihre Wordpress-Version %s ist veraltet. Bitte aktualisieren Sie mindestens auf die Wordpress-Version %s.', 'blue-edgy'), $GLOBALS['wp_version'], self::wp_version);
        }

        if (!empty($error)) {
            return $error;
        }
    }

    public static function admin_notices() {
        if (self::$check_error) {
            echo '<div class="error">' . self::$check_error . '</div>';
        }
        echo '<div class="error">' . __('Das Theme konnte nicht aktiviert werden.', 'blue-edgy' ) . '</div>';
    }

}

add_action('after_setup_theme', array('RRZE_Theme', 'instance'));

class RRZE_Theme {

    const version = '2.2.0'; // Theme-Version
    const version_option_name = '_rrze_theme_version';
    const option_name = '_rrze_theme_options';
    const textdomain = 'blue-edgy';

    protected static $instance = NULL;
    
    public static $default_theme_options = array();
    public static $theme_options = array();
    public static $options_pages = array();
    
    public static $default_custom_fields = array();
    
    public static function instance() {

        if (null == self::$instance) {
            self::$instance = new self;
            self::$instance->after_setup_theme();
        }

        return self::$instance;
    }

    public function after_setup_theme() {
        load_theme_textdomain('blue-edgy', get_template_directory() . '/languages');

        if (get_option('check_error_' . RRZE_Check_Theme::theme_name)) {  
            return;
        }            
        $this->update_version();
        //remove_filter( 'the_content', 'wpautop' );
        //add_filter( 'the_content', 'wpautop' , 12);
        
        require_once( get_template_directory() . '/includes/template-parser.php' );
        require_once( get_template_directory() . '/includes/theme-tags.php' );
        require_once( get_template_directory() . '/includes/theme-options.php' );
        require_once( get_template_directory() . '/includes/shortcodes.php' );
        require_once( get_template_directory() . '/includes/widgets.php');
        require_once( get_template_directory() . '/includes/tinymce.php' );
        
        self::$default_theme_options = $this->default_theme_options();
        self::$theme_options = $this->theme_options();
        self::$options_pages = $this->options_pages();
        
        self::$default_custom_fields = $this->default_custom_fields();

        $this->add_theme_support();

        $this->register_default_header();
        
        $this->register_nav_menu();
        
        add_action('admin_init', array(&$this, 'add_editor_style'));
        
        add_action( 'widgets_init', array(&$this, 'widgets_init'));
        
        add_action( 'wp_head', array(&$this, 'wp_head'));
        
        add_action( 'wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts'));
        
        add_action( 'admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
        
        add_action('add_meta_boxes', array(&$this, 'add_meta_box'));
        add_action('save_post', array(&$this, 'save_postdata'));
        
        add_action('save_post', array(&$this, 'save_title'));
        add_action('admin_notices', array(&$this, 'admin_notice'), 99);
        
        add_action('admin_menu', array(&$this, 'remove_post_custom_fields'));
        
        add_action('admin_head', array(&$this, 'add_admin_backend_css'));
        
        add_filter( 'get_archives_link', array(&$this, 'get_archives_link'));
        
        add_filter('nav_menu_css_class', array(&$this, 'nav_menu_css_class'));
        
        add_filter('wp_list_categories', array(&$this, 'wp_list_categories'), 10, 2);
        
        add_filter( 'excerpt_length', array(&$this, 'excerpt_length'), 999 );     
        
        add_filter( 'wp_title', array(&$this, 'blue_edgy_wp_title') );
    }
            

    
    private function update_version() {
        if (get_option(self::version_option_name, null) != self::version)
            update_option(self::version_option_name, self::version);
    }

    private function default_theme_options() {
        $options = array(
            'color.schema' => 'blau',
            'color.style' => _rrze_default_color_style_data(),
            'column.layout' => '1-3',
            'footer.layout' => '33-33-33',
            'search.form.position' => 'bereichsmenu',
            'header.layout' => 'middle-left',
            'body.typography' => 'DroidSans',
            'heading.typography' => 'DroidSans',
            'menu.typography' => 'DroidSans',
            'widget.title.typography' => 'DroidSans',
            'widget.content.typography' => 'DroidSans',
            'blog.overview' => 'rrze_content',
            'words.overview' => '55',
            'comments.pages' => false,
            'teaser.image' => 0,
            	/*
                * 1 = Thumbnail (or: first picture, first video, fallback picture),
                * 2 = First picture (or: thumbnail, first video, fallback picture),
                * 3 = First video (or: thumbnail, first picture, fallback picture),
                * 4 = First video (or: first picture, thumbnail, fallback picture),
                * 5 = Nothing */  
            'teaser.image.default' => true,
            'default-teaserimage' => get_template_directory_uri() .'/images/default-teaserimage.png',
        );       
        
        return apply_filters( '_rrze_default_theme_options', $options );
    }

    private function theme_options() {
        $default_options = $this->default_theme_options();

        $options = (array) get_option(self::option_name);

        $options = wp_parse_args( $options, $default_options );
        $options = array_intersect_key( $options, $default_options );   

        return $options;
    }
    
    private function options_pages() {
        $pages = array( 
            'layout.options' => array( 
                'value' => 'layout.options', 
                'label' => __('Layout', 'blue-edgy' )
            ),

            'typography.options' => array( 
                'value' => 'typography.options', 
                'label' => __('Schriftart', 'blue-edgy' )
            ),

            'color.options' => array( 
                'value' => 'color.options', 
                'label' => __('Farben', 'blue-edgy' )
            ),

            'overview.options' => array(
                'value' => 'overview.options',
                'label' => __('Blogdarstellung', 'blue-edgy')
            )

        );

        return apply_filters( '_rrze_options_pages', $pages );
    }
    

    private function default_custom_fields() {
        /* möglich bei type: text, textarea, checkbox, select, image, title, headline (für Zwischenüberschriften) */
        $custom_fields = array(
            '_titel_ausblenden' => array(
                'name' => '_titel_ausblenden',
                'default' => '0',
                'title' => __( 'Titel ausblenden', 'blue-edgy' ),
                'description' => '',
                'type' => 'checkbox',
                'location' => 'page')
            
        );

        return apply_filters( '_rrze_default_custom_fields', $custom_fields );
    }
    
    
    public function add_theme_support() {
        $defaults = array(
            'default-image' => get_stylesheet_directory_uri() . '/images/headers/grau-header.jpg',
            'width' => apply_filters('_rrze_header_image_width', 1120),
            'height' => apply_filters('_rrze_header_image_height', 160),
            'default-text-color' => '00425F',
            'uploads' => true,
            'wp-head-callback' => array($this, 'wp_head_callback'),
            'admin-head-callback' => array($this, 'admin_head_callback'),
            'admin-preview-callback' => array($this, 'admin_preview_callback'),
        );

        add_theme_support('custom-header', $defaults);

        add_theme_support('automatic-feed-links');

        add_theme_support('post-formats', array('aside', 'gallery', 'image', 'link', 'quote', 'status'));

        add_theme_support('post-thumbnails');
    }

    public function wp_head_callback() {
        $text_color = get_header_textcolor();

        if ($text_color == HEADER_TEXTCOLOR)
            return;
        ?>
        <style type="text/css" media="all">
        <?php if ('blank' == $text_color) : ?>
                #site-title, #site-description {
                    position: absolute !important;
                    clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
                    clip: rect(1px, 1px, 1px, 1px);
                }
        <?php else : ?>
                #site-title, #site-description, #site-title a {
                    color: <?php printf('#%s', $text_color); ?> !important;
                }
        <?php endif; ?>
        </style>
        <?php
    }

    public function admin_head_callback() {
        ?>
        <style type="text/css" media="all">
            .appearance_page_custom-header #headimg {
                border: none;
            }
            #headimg h1, #headimg h3 {
                font-family: DroidSans,Arial,Helvetica,sans-serif;
                font-weight: 400;
                padding: 0;
                margin: 0;
            }
            #headimg h1 {
                background-color: rgba(255, 255, 255, 0.75);
                font-size: 342.85714%;
                line-height: 1.1em;
                margin-top: 36px;
                overflow: hidden;
                white-space:nowrap;
            }
            #headimg h3 {
                background-color: rgba(255, 255, 255, 0.55);
                font-size: 171.42857%;
                margin-top: 3px;
            }
        </style>
        <?php
    }

    public function admin_preview_callback() {
        $color = get_header_textcolor();
        $image = get_header_image();
        if ($color && $color != 'blank')
            $style = 'background: none; padding-left: 0; margin-bottom: 0; color:#' . $color . ';';
        else
            $style = 'display:none;';
        ?>
        <?php if ($image) : ?>
            <div id="headimg" style="background-image:url('<?php echo esc_url($image); ?>'); width:auto; max-width:1120px; height:160px;">
        <?php else: ?>
            <div id="headimg">
        <?php endif; ?>
                <h1 id="name" style="<?php echo $style; ?>"><span style="padding: 4px 4px 4px 20px; background-color: rgba(255, 255, 255, 0.75);"><?php bloginfo('name'); ?></span></h1>
                <h3 id="desc" style="<?php echo $style; ?>"><span style="padding: 4px 4px 4px 20px; background-color: rgba(255, 255, 255, 0.55);"><?php bloginfo('description'); ?></span></h3>
            </div>
        <?php
    }
    
    private function register_default_header() {
        register_default_headers(array(
            'grau' => array(
                'url' => '%2$s/images/headers/grau-header.jpg',
                'thumbnail_url' => '%2$s/images/headers/grau-thumbnail.jpg',
                'description' => __('Grau', 'blue-edgy')
            )
        ));        
    }
    
    private function register_nav_menu() {
        register_nav_menu('bereichsmenu', __('Bereichsmenü', 'blue-edgy'));

        if( ! is_blogs_fau_de() ) {
            register_nav_menu( 'tecmenu', __( 'Technisches Menü', 'blue-edgy' ) );
        }        
    }
    
    public function add_editor_style() {
        add_editor_style('editor-style.css');        
    }
    
    public function widgets_init() {
        unregister_widget('WP_Widget_Text');
        register_widget('RRZE_Widget_Text');
        
        $this->register_sidebar();
    }
    
    private function register_sidebar() {
        register_sidebar( array(
            'name' => __( 'Sidebar links', 'blue-edgy' ),
            'id' => 'sidebar-left',
            'description'   => __( 'Dieser Bereich ist für die Menüs und die Widgets vorgesehen.', 'blue-edgy' ),
            'before_widget' => '<div id="%1$s" class="widget-wrapper ym-vlist %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',               
        ) );

        register_sidebar( array(
            'name' => __( 'Sidebar rechts', 'blue-edgy' ),
            'id' => 'sidebar-right',
            'description'   => __( 'Dieser Bereich ist für die Menüs und die Widgets vorgesehen.', 'blue-edgy' ),
            'before_widget' => '<div class="widget-wrapper ym-vlist">',
            'after_widget' => '</div>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',      
        ));

        register_sidebar( array(
            'name' => __( 'Footer-Sidebar links', 'blue-edgy' ),
            'id' => 'sidebar-footer-left',
            'description'   => __( 'Dieser Bereich ist für die Zusatzinformationen (im Footer links) vorgesehen. Hier könnten hilfreiche Links oder sonstige Informationen stehen, welche auf jeder Seite eingeblendet werden sollen. Diese Angaben werden bei der Ausgabe auf dem Drucker nicht mit ausgegeben!', 'blue-edgy' ),
            'before_widget' => '<div id="%1$s" class="widget-wrapper ym-vlist %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',      
        ));

        register_sidebar( array(
            'name' => __( 'Footer-Sidebar mitte', 'blue-edgy' ),
            'id' => 'sidebar-footer-center',
            'description'   => __( 'Dieser Bereich ist für die Zusatzinformationen (im Footer mitte) vorgesehen. Hier könnten hilfreiche Links oder sonstige Informationen stehen, welche auf jeder Seite eingeblendet werden sollen. Diese Angaben werden bei der Ausgabe auf dem Drucker nicht mit ausgegeben!', 'blue-edgy' ),
            'before_widget' => '<div id="%1$s" class="widget-wrapper ym-vlist %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',      
        ));

        register_sidebar( array(
            'name' => __( 'Footer-Sidebar rechts', 'blue-edgy' ),
            'id' => 'sidebar-footer-right',
            'description'   => __( 'Dieser Bereich ist für die Zusatzinformationen (im Footer rechts) vorgesehen. Hier könnten hilfreiche Links oder sonstige Informationen stehen, welche auf jeder Seite eingeblendet werden sollen. Diese Angaben werden bei der Ausgabe auf dem Drucker nicht mit ausgegeben!', 'blue-edgy' ),
            'before_widget' => '<div id="%1$s" class="widget-wrapper ym-vlist %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h6 class="widget-title">',
            'after_title' => '</h6>',      
        ));        
    }
    
    public function wp_head() {
        $options = self::$theme_options;

        printf( '<style type="text/css" media="all">%1$sbody {font-family: %2$s}%1$s</style>%1$s', PHP_EOL, $options['body.typography'] );
        printf( '<style type="text/css" media="all">%1$sh1, h2, h3, h4, h5, h6 {font-family: %2$s}%1$s</style>%1$s', PHP_EOL, $options['heading.typography'] );
        printf( '<style type="text/css" media="all">%1$s.dropdown {font-family: %2$s}%1$s</style>%1$s', PHP_EOL, $options['menu.typography'] );
        printf( '<style type="text/css" media="all">%1$s.widget-title {font-family: %2$s}%1$s</style>%1$s', PHP_EOL, $options['widget.title.typography'] );
        printf( '<style type="text/css" media="all">%1$s.ym-vlist, .widget-wrapper input, .widget-wrapper select, .widget-wrapper option {font-family: %2$s}%1$s</style>%1$s', PHP_EOL, $options['widget.content.typography'] );

        $header_image = get_header_image();

        if ( $header_image )
            //printf( '<style type="text/css" media="all">%1$sbody div#kopf div#title {background: url("%2$s") no-repeat scroll center top transparent;}%1$s</style>%1$s', PHP_EOL, $header_image );
            printf( '<style type="text/css">%1$s@media all {%1$sbody div#kopf div#title {background: url("%2$s") no-repeat scroll center top transparent;}%1$s}%1$s@media screen and (max-width: 760px) {%1$sbody div#kopf div#title {background-size: 760px;}%1$s}%1$s</style>%1$s', PHP_EOL, $header_image );

        Template_Parser::print_template( $options, 'css/layout', '<style type="text/css">' . PHP_EOL, '</style>' . PHP_EOL );

        Template_Parser::print_template( $options['color.style'], 'css/color', '<style type="text/css">' . PHP_EOL, '</style>' . PHP_EOL );        
    }
    
    public function wp_enqueue_scripts() {
        $options = self::$theme_options;

        wp_enqueue_style( 'style', get_stylesheet_uri() );

        wp_register_style('iehacks', sprintf('%s/css/yaml/core/iehacks.css', get_template_directory_uri() ) );
        $GLOBALS['wp_styles']->add_data( 'iehacks', 'conditional', 'lte IE 7' );
        wp_enqueue_style( 'iehacks' );

        wp_register_style('accessible-tabs', sprintf('%s/css/yaml/add-ons/accessible-tabs/tabs.css', get_template_directory_uri() ) );
        wp_enqueue_style( 'accessible-tabs' );

        wp_enqueue_script( 'jquery' );

        wp_register_script( 'focusfix', sprintf( '%s/css/yaml/core/js/yaml-focusfix.js', get_template_directory_uri() ), array(), false, true );
        wp_enqueue_script( 'focusfix');

        wp_register_script( 'accessible-tabs', sprintf( '%s/css/yaml/add-ons/accessible-tabs/jquery.tabs.js', get_template_directory_uri() ), array(), false, true );
        wp_enqueue_script( 'accessible-tabs');

        wp_register_script( 'base', sprintf( '%s/js/base.js', get_template_directory_uri() ), array(), false);
        wp_enqueue_script( 'base' );   
        
        wp_register_script( 'accordions', sprintf( '%s/js/accordions.js', get_template_directory_uri() ), array(), false, true );
  
    }
    
    public function admin_enqueue_scripts() {
        wp_enqueue_media();
        wp_enqueue_style('blue-edgy-admin', sprintf( '%s/css/admin.css', get_template_directory_uri() ), array(), false);
        wp_enqueue_script('blue-edgy-admin', sprintf( '%s/js/admin.js', get_template_directory_uri() ), array(), false);
    }
    
    public function get_archives_link($links) {
        $links = str_replace( '</a>&nbsp;(', ' (', $links );
        $links = str_replace( ')', ')</a>', $links );
        return $links;    
    }
    
    public function nav_menu_css_class($classes) {
        if(in_array('menu-item-has-children', $classes))
            $classes[] = 'sub';

        return $classes;
    }
    
    public function wp_list_categories($output, $args) {
        $output = str_replace('</a> (', ' (', $output );
        $output = str_replace(')', ')</a>', $output);
        
        $current_term = get_queried_object();

        if(!isset($current_term->taxonomy))
            return $output;

        $ancestors = get_ancestors($current_term->term_id, $current_term->taxonomy);

        $cats = get_categories('hide_empty=0');

        foreach($cats as $cat) {
            // count == 0 current; count == 1 parent; count >= 2 all ancestors
            if(in_array($cat->term_id, $ancestors) && count($ancestors) >= 2) {
                $find = 'cat-item-' . $cat->term_id . '"';
                $replace = 'cat-item-' . $cat->term_id . ' current-cat-ancestor"';
                $output = str_replace( $find, $replace, $output );           
            }      
        }

        return $output;
    }    
    
    public function excerpt_length( $length ) {
        $options = self::$theme_options;
        return $options['words.overview'];
    }
    
    public function blue_edgy_wp_title( $title ) {
        $out = get_bloginfo('name') . ' | ';
        if(is_home() || is_front_page()) {
            $out .= get_bloginfo( 'description' );
        } else {
            $out .= $title;
        }
        return $out;
    } 

    public function add_meta_box( $post_type ) {
        $post_types = array('post', 'page');
        if ( in_array( $post_type, $post_types )) {
            if ($post_type=='post') {
                $new_meta_boxes = 'new_meta_boxes_post';
            } else {
                $new_meta_boxes = 'new_meta_boxes_page';
            }
            add_meta_box(
                    'more_meta'
                    ,__('Weitere Einstellungen', 'blue-edgy')
                    , array($this, $new_meta_boxes)
                    , $post_type
                    , 'normal'
                    , 'high'
            );
        }
    }
    
    public function new_meta_boxes_post() {
        $this->new_meta_boxes('post');
    }
    
    public function new_meta_boxes_page() {
        $this->new_meta_boxes('page');
    }

    // Ausgabe der Custom Fields
    public function new_meta_boxes( $type ) {
        global $post;
        
        wp_nonce_field('more_meta_box', 'more_meta_box_nonce');
        
        $new_meta_boxes = self::$default_custom_fields;
        
        if(empty($new_meta_boxes)) {
            return;
        }
        
        echo '<div class="form-wrap">';
        foreach($new_meta_boxes as $meta_box) {
            if( $meta_box['location'] == $type) {
          
                $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);

                if($meta_box_value == "")
                    $meta_box_value = $meta_box['default'];

                switch ( $meta_box['type'] ) {
                    case 'text':
                        echo '<div class="form-field form-required">';
                        echo    '<label for="' . $meta_box[ 'name' ] .'"><strong>' . $meta_box[ 'title' ] . '</strong></label>';
                        echo    '<input id="' . $meta_box[ 'name' ] . '" type="text" name="' . $meta_box[ 'name' ] . '" value="' . htmlspecialchars( $meta_box_value ) . '" style="border-color: #ccc;" />';
                        echo    '<p>' . $meta_box[ 'description' ] . '</p>';
                        echo '</div>';
                        break;

                    case 'textarea':
                        echo '<div class="form-field form-required">';
                        echo    '<label for="' . $meta_box[ 'name' ] .'"><strong>' . $meta_box[ 'title' ] . '</strong></label>';
                        echo    '<textarea name="' . $meta_box[ 'name' ] . '" style="border-color: #ccc;" rows="10">' . htmlspecialchars( $meta_box_value ) . '</textarea>';
                        echo    '<p>' . $meta_box[ 'description' ] . '</p>';
                        echo '</div>';
                        break;

                    case 'checkbox':
                        echo '<div class="form-field form-required">';
                        if( $meta_box_value == '1') { $checked = "checked=\"checked\""; }else{ $checked = "";}
                        echo    '<label for="' . $meta_box[ 'name' ] .'"><strong>' . $meta_box[ 'title' ] . '</strong>&nbsp;<input style="width: 20px;" type="checkbox" id="' . $meta_box[ 'name' ] . '" name="' . $meta_box[ 'name' ] . '" value="1" ' . $checked . ' /></label>';
                        echo    '<p>' . $meta_box[ 'description' ] . '</p>';
                        echo '</div>';
                        break;

                    case 'select':
                        echo '<div class="form-field form-required">';
                        echo    '<label for="' . $meta_box[ 'name' ] .'"><strong>' . $meta_box[ 'title' ] . '</strong></label>';

                        echo    '<select name="' . $meta_box[ 'name' ] . '">';

                        foreach ($meta_box[ 'options' ] as $option) {
                            if(is_array($option)) {
                                echo '<option ' . ( $meta_box_value == $option['value'] ? 'selected="selected"' : '' ) . ' value="' . $option['value'] . '">' . $option['text'] . '</option>';
                            } else {
                                echo '<option ' . ( $meta_box_value == $option ? 'selected="selected"' : '' ) . ' value="' . $option['value'] . '">' . $option['text'] . '</option>';
                            }
                        }

                        echo    '</select>';
                        echo    '<p>' . $meta_box[ 'description' ] . '</p>';
                        echo '</div>';
                        break;

                }
            } 
            
        }
        echo '</div>';
    }
    
    public function save_postdata( $post_id ) {
	if ( ! isset( $_POST['more_meta_box_nonce'] ) ) {
		return;
	}
        if ( !wp_verify_nonce( $_POST['more_meta_box_nonce'], 'more_meta_box') ) {
            return;
        }
     
        if ( wp_is_post_revision( $post_id ) or wp_is_post_autosave( $post_id ) )
            return;
        
        if ( ( 'page' == $_POST['post_type'] && !current_user_can('edit_page', $post_id) ) || !current_user_can('edit_post', $post_id) ) {
            return; 
        }
        
        global $post;
        $new_meta_boxes = self::$default_custom_fields;
 
        foreach($new_meta_boxes as $meta_box) {
            switch ( $meta_box['type'] ) {
                case 'checkbox':
                    $input = isset($_POST[$meta_box['name']]) ? '1' : '0';
                    break;
                default:
                    $input = null;
                    break;
            }
            if (!is_null($input)) {
                update_post_meta($post_id, $meta_box['name'], $input);
            }  
        }

    } 
    public function save_title($post_id) {
        
        // if this is a revision, get real post ID
        if ( $parent_id = wp_is_post_revision( $post_id ) )  {
            $post_id = $parent_id;
        }
        
        // bugfix für Menü - schlechte Lösung, evtl. Probleme mit anderen Plugins
        $post = get_post($post_id);
        if ($post->post_type == "nav_menu_item") return;

        if(empty($post->post_title)) {
            $error = __('Sie haben noch keinen Titel eingegeben.', 'blue-edgy');
            // set transient for admin notice
            set_transient($this->transient_hash(), $error, 15 * MINUTE_IN_SECONDS);

            // OPTIONAL

            // set placeholder for empty title
            $title = __('Dokument ohne Titel', 'blue-edgy');
            
            // unhook this function so it doesn't loop infinitely
            remove_action('save_post', array($this, 'save_title'));
            
            // update the post, which calls save_post again
            wp_update_post(array('ID' => $post_id, 'post_title' => $title));

            // re-hook this function
            add_action('save_post', array($this, 'save_title'));
        }

    }
        
    public function admin_notice() {
        // only valid for the post.php page
        if (empty($GLOBALS['pagenow']) OR empty($_GET['message']) OR $GLOBALS['pagenow'] !== 'post.php') {
            return;
        }

        // set a hash
        $hash = $this->transient_hash();

        // get the transient if exist
        if ((!$error = get_transient($hash))) {
            return;
        }

        // delete the transient
        delete_transient($hash);

        echo '<div class="error"><ul>';
        echo $error;
        echo '</ul></div>';
    }

    private function transient_hash() {
        return md5(sprintf('RRZE_Snippet_%s_%s', get_the_ID(), get_current_user_id()));
    }
    
    public function remove_post_custom_fields() {
	remove_meta_box( 'postcustom' , 'post' , 'normal' ); 
        	remove_meta_box( 'postcustom' , 'page' , 'normal' ); 
    }    
    
    /*Load additional stylesheet for WP Backend */
    public function add_admin_backend_css() {
        $url = get_stylesheet_directory_uri() . '/css/admin_backend.css';
        echo '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
    }
    
}

function _rrze_theme_options( $key = '' ) {
	$options = RRZE_Theme::$theme_options;
    if( !empty( $key ) ) {
        return isset($options[$key]) ? $options[$key] : NULL;
    }
    return $options;
}

function is_blogs_fau_de() {
    $http_host = filter_input(INPUT_SERVER, 'HTTP_HOST');
    if( $http_host == 'blogs.fau.de') {
        return true;
    } else {
        return false;
    }
}

	/**
	 * Get First Picture URL from content
	 */
	function _rrze_get_first_image_url() {
		global $post;
		$first_img = '';
		$matches = array();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		if ($output != 0) {
			$first_img = $matches[1][0];
		} elseif (($output == 0) && (get_header_image())) {
			$first_img = get_header_image();
		}
		return $first_img;
	}

	/*
	 * Thumbnail-Reihenfolge in Options wählbar
	 */

	function _rrze_get_thumbnailcode($show_teaser_image = 0) {
		global $post;
		$thumbnailcode = '';
		$show_teaser_image = _rrze_theme_options('teaser.image');
                $firstpic = _rrze_get_firstpicture();
		$firstvideo = _rrze_get_firstvideo();
                if (_rrze_theme_options('teaser.image.default')==true) {
                    $fallbackimg = '<img src="'._rrze_theme_options('default-teaserimage').'" alt="">';
                    $fallbacksrc = _rrze_theme_options('default-teaserimage');              
                } else {
                    $fallbackimg = '';
                }
                        

                
		$output = '';

		/*
		 * 1 = Thumbnail (or: first picture, first video, fallback picture, nothing),
		 * 2 = First picture (or: thumbnail, first video, fallback picture, nothing),
		 * 3 = First video (or: thumbnail, first picture, fallback picture, nothing),
		 * 4 = First video (or: first picture, thumbnail, fallback picture, nothing),
		 * 5 = Nothing
		 */
		if ($show_teaser_image == 0) {
			$show_teaser_image = 1;
		}

		if (has_post_thumbnail()) {
			$thumbnailcode = get_the_post_thumbnail($post->ID, 'teaser-thumb');
                }

		if ($show_teaser_image == 1) {
		    if ((isset($thumbnailcode)) && (strlen(trim($thumbnailcode))>10)) {
				$output = $thumbnailcode;
		    } elseif ((isset($firstpic)) && (strlen(trim($firstpic))>10)) {
				$output = $firstpic;
		    }  elseif ((isset($firstvideo)) && (strlen(trim($firstvideo))>10)) {
				$output = $firstvideo;
                    } elseif (isset($fallbackimg) && strlen(trim($fallbackimg))>10) {
				$output = $fallbackimg;
                    } else {
				$output = '';
                    }

		} elseif ($show_teaser_image == 2) {

		    if ((isset($firstpic)) && (strlen(trim($firstpic))>10)) {
				$output = $firstpic;
		    } elseif ((isset($thumbnailcode)) && (strlen(trim($thumbnailcode))>10)) {
				$output = $thumbnailcode;
		    }  elseif ((isset($firstvideo)) && (strlen(trim($firstvideo))>10)) {
				$output = $firstvideo;
		    } elseif (isset($fallbackimg) && strlen(trim($fallbackimg))>10) {
				$output = $fallbackimg;
                    } else {
				$output = '';
		    }

		} elseif ($show_teaser_image == 3) {
		    if ((isset($firstvideo)) && (strlen(trim($firstvideo))>10)) {
				$output = $firstvideo;
		    } elseif ((isset($thumbnailcode)) && (strlen(trim($thumbnailcode))>10)) {
				$output = $thumbnailcode;
		    } elseif ((isset($firstpic)) && (strlen(trim($firstpic))>10)) {
				$output = $firstpic;
		    } elseif (isset($fallbackimg) && strlen(trim($fallbackimg))>10) {
				$output = $fallbackimg;
                    } else {
				$output = '';
		    }


		} elseif ($show_teaser_image == 4) {
		    if ((isset($firstvideo)) && (strlen(trim($firstvideo))>10)) {
				$output = $firstvideo;
		    } elseif ((isset($firstpic)) && (strlen(trim($firstpic))>10)) {
				$output = $firstpic;
		    } elseif ((isset($thumbnailcode)) && (strlen(trim($thumbnailcode))>10)) {
				$output = $thumbnailcode;
		    } elseif (isset($fallbackimg) && strlen(trim($fallbackimg))>10 ) {
				$output = $fallbackimg;
                    } else {
				$output = '';
		    }

		} else {
		    $output = '';
		}   
		echo $output;
	}

	/*
	 * Erstes Bild aus einem Artikel auslesen, wenn dies vorhanden ist
	 */

	function _rrze_get_firstpicture() {
		global $post;
		$first_img = '';
		$matches = array();
		preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		if ((is_array($matches)) && (isset($matches[1]))) :
                        $first_img = $matches[1];
                        $imagehtml = '<img src="' . $first_img . '" alt="">';
			return $imagehtml;                
		endif;		
	}


	/*
	 * Erstes Video aus einem Artikel auslesen, wenn dies vorhanden ist
	 */

	function _rrze_get_firstvideo($width = 267, $height = 150, $nocookie = 1, $searchplain = 1) {
		global $post;
		$matches = array();
		preg_match('/src="([^\'"]*www\.youtube[^\'"]+)/i', $post->post_content, $matches);
		if ((is_array($matches)) && (isset($matches[1]))) {
			$entry = $matches[1];
			if (!empty($entry)) {
				if ($nocookie == 1) {
					$entry = preg_replace('/youtube.com\/watch\?v=/', 'youtube-nocookie.com/embed/', $entry);
				}
				$htmlout = '<iframe width="' . $width . '" height="' . $height . '" src="' . $entry . '" allowfullscreen></iframe>';
				return $htmlout;
			}
		}
		// Schau noch nach YouTube-URLs die Plain im text sind. Hilfreich fuer
		// Installationen auf Multisite ohne iFrame-Unterstützung
		if ($searchplain == 1) {
			preg_match('/\b(https?:\/\/www\.youtube[\/a-z0-9\.\-\?=]+)/i', $post->post_content, $matches);
			if ((is_array($matches)) && (isset($matches[1]))) {
				$entry = $matches[1];
				if (!empty($entry)) {
					if ($nocookie == 1) {
						$entry = preg_replace('/youtube.com\/watch\?v=/', 'youtube-nocookie.com/embed/', $entry);
					}
					$htmlout = '<iframe width="' . $width . '" height="' . $height . '" src="' . $entry . '" allowfullscreen></iframe>';
					return $htmlout;
				}
			}
		}
		return;
	}




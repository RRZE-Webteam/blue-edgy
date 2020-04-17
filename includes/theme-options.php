<?php

add_filter('twitter_return_the_content', function() {
    $options = RRZE_Theme::$theme_options;
    if ($options['blog.overview']=='rrze_content') {
        return false;
    }
    return true;
});

add_filter( 'option_page_capability__rrze_options', function( $capability ) {
	return 'edit_theme_options';
} );

add_action( 'admin_menu', function() {
	add_theme_page( __( 'Einstellungen', 'blue-edgy' ), __( 'Einstellungen', 'blue-edgy' ), 'edit_theme_options', 'theme_options', '_rrze_theme_options_menu_page' );
    
    $pages = RRZE_Theme::$options_pages;
    foreach( $pages as $page) {
        add_submenu_page( 'theme_options', $page['label'], $page['label'], 'edit_theme_options', $page['value'], '_rrze_theme_options_menu_page' );
    }
}, 100 );

add_action( 'admin_print_scripts-appearance_page_theme_options', function() { 
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-color-picker' );
    
    wp_enqueue_script( 'theme-options', sprintf( '%s/js/theme-options.js', get_template_directory_uri() ), array('jquery') );
    
}, 50);


add_action( 'admin_init', function() {
    
    /* Layout options */
    register_setting( 'layout.options', RRZE_Theme::option_name, '_rrze_theme_options_validate' );

    add_settings_section( 'layout.section', __( 'Layouteinstellungen', 'blue-edgy' ), '_rrze_section_layout_callback', 'layout.options' );

    add_settings_field( 'column.layout', __( 'Seitenlayout', 'blue-edgy' ), '_rrze_field_columnlayout_callback', 'layout.options', 'layout.section' );
    
    add_settings_field( 'header.layout', __( 'Header-Layout', 'blue-edgy' ), '_rrze_field_header_layout_callback', 'layout.options', 'layout.section' );    
    
    add_settings_field( 'footer.layout', __( 'Footer-Layout', 'blue-edgy' ), '_rrze_field_footer_layout_callback', 'layout.options', 'layout.section' );
    
    add_settings_field( 'search.form', __( 'Position des Suchformulars', 'blue-edgy' ), '_rrze_field_searchform_callback', 'layout.options', 'layout.section' );
    
    /* Typography options */
    register_setting( 'typography.options', RRZE_Theme::option_name, '_rrze_theme_options_validate' );

    add_settings_section( 'typography.section', __('Schriftart', 'blue-edgy' ), '_rrze_section_typography_callback', 'typography.options' );

    add_settings_field( 'body.typography', __('Allgemein', 'blue-edgy' ), '_rrze_field_body_typography_callback', 'typography.options', 'typography.section' );

    add_settings_field( 'heading.typography', __('Überschrift', 'blue-edgy' ), '_rrze_field_heading_typography_callback', 'typography.options', 'typography.section' );

    add_settings_field( 'menu.typography', __('Menü', 'blue-edgy' ), '_rrze_field_menu_typography_callback', 'typography.options', 'typography.section' );

    add_settings_field( 'widget.title.typography', __('Widget-Titel', 'blue-edgy' ), '_rrze_field_widget_title_typography_callback', 'typography.options', 'typography.section' );

    add_settings_field( 'widget.content.typography', __('Widget-Inhalt', 'blue-edgy' ), '_rrze_field_widget_content_typography_callback', 'typography.options', 'typography.section' );
    
    /* Color options */
    register_setting( 'color.options', RRZE_Theme::option_name, '_rrze_theme_options_validate' );

    add_settings_section( 'color.schema.section', __('Farbeinstellungen', 'blue-edgy' ), '_rrze_section_color_style_callback', 'color.options' );

    //add_settings_field( 'color.style', __('Farben', 'blue-edgy' ), '_rrze_field_color_style_callback', 'color.options', 'color.schema.section' );
    
    add_settings_field( 'color.schema', __('Farbschemas', 'blue-edgy' ), '_rrze_field_color_schema_callback', 'color.options', 'color.schema.section' );
    
    /* Overview options */
    register_setting( 'overview.options', RRZE_Theme::option_name, '_rrze_theme_options_validate' );

    add_settings_section( 'overview.section', __( 'Darstellung der Beitragsübersicht', 'blue-edgy' ), '_rrze_section_overview_callback', 'overview.options' );

    add_settings_field( 'blog.overview', __( 'Beiträge auf der Übersichtsseite anzeigen', 'blue-edgy' ), '_rrze_field_blogoverview_callback', 'overview.options', 'overview.section' );
    
    add_settings_field( 'words.overview', __( 'Anzahl der angezeigten Wörter in der Vorschau', 'blue-edgy' ), '_rrze_field_wordsoverview_callback', 'overview.options', 'overview.section' );

    add_settings_field( 'teaser.image', __( 'Bild im Textauszug', 'blue-edgy' ), '_rrze_field_teaserimage_callback', 'overview.options', 'overview.section' );
    
    add_settings_field( 'teaser.image.default', __('Platzhalter für Vorschau', 'blue-edgy' ), '_rrze_field_teaserimage_default_callback', 'overview.options', 'overview.section' );
    
} );

function _rrze_searchform_options() {
    $options = array(
        'none' => array(
            'value' => 'none',
            'label' => __( 'keiner', 'blue-edgy' )
        ),
        
        'bereichsmenu' => array(
            'value' => 'bereichsmenu',
            'label' => __( 'Bereichsmenü', 'blue-edgy' )
        )          
    );

    return apply_filters( '_rrze_searchform_options', $options );
}

function _rrze_columnlayout_options() {
    $options = array(
        '3' => array(
            'value' => '3',
            'label' => __( '1 Spalte - keine Sidebar', 'blue-edgy' )
        ),
        '1-3' => array(
            'value' => '1-3',
            'label' => __( '2 Spalten - linke Sidebar', 'blue-edgy' )
        ),
        
        '2-3' => array(
            'value' => '2-3',
            'label' => __( '2 Spalten - rechte Sidebar', 'blue-edgy' )
        ),
        
        '1-2-3' => array(
            'value' => '1-2-3',
            'label' => __( '3 Spalten - linke und rechte Sidebar', 'blue-edgy' )
        )
        
    );

    return apply_filters( '_rrze_columnlayout_options', $options );
}

function _rrze_footer_layout_options() {
    $options = array(
        '100' => array( 'group' => 1, 'value' => '100', 'label' => '100%' ),
        '25-75' => array( 'group' => 2, 'value' => '25-75', 'label' => '25% | 75%' ),
        '33-66' => array( 'group' => 2, 'value' => '33-66', 'label' => '33% | 66%' ),
        '38-62' => array( 'group' => 2, 'value' => '38-62', 'label' => '38% | 62%' ),
        '40-60' => array( 'group' => 2, 'value' => '40-60', 'label' => '40% | 60%' ),
        '50-50' => array( 'group' => 2, 'value' => '50-50', 'label' => '50% | 50%' ),
        '60-40' => array( 'group' => 2, 'value' => '60-40', 'label' => '60% | 40%' ),
        '62-38' => array( 'group' => 2, 'value' => '62-38', 'label' => '62% | 38%' ),
        '66-33' => array( 'group' => 2, 'value' => '66-33', 'label' => '66% | 33%' ),
        '75-25' => array( 'group' => 2, 'value' => '75-25', 'label' => '75% | 25%' ),
        '25-25-50' => array( 'group' => 3, 'value' => '25-25-50', 'label' => '25% | 25% | 50%' ),
        '25-50-25' => array( 'group' => 3, 'value' => '25-50-25', 'label' => '25% | 50% | 25%' ),
        '50-25-25' => array( 'group' => 3, 'value' => '50-25-25', 'label' => '50% | 25% | 25%' ),
        '33-33-33' => array( 'group' => 3, 'value' => '33-33-33', 'label' => '33% | 33% | 33%' )
    );

    return apply_filters( '_rrze_footer_layout_options', $options );
}

function _rrze_header_layout_options() {
    $options = array(
        'top-left' => array( 'value' => 'top-left', 'label' => __( 'Titel oben - links', 'blue-edgy' ) ),
        'top-center' => array( 'value' => 'top-center', 'label' => __( 'Titel oben - zentriert', 'blue-edgy' ) ), 
        'top-right' => array( 'value' => 'top-right', 'label' => __( 'Titel oben - rechts', 'blue-edgy' ) ),
        'middle-left' => array( 'value' => 'middle-left', 'label' => __( 'Titel mittig - links', 'blue-edgy' ) ), 
        'middle-right' => array( 'value' => 'middle-right', 'label' => __( 'Titel mittig - rechts', 'blue-edgy' ) ), 
        'bottom-left' => array( 'value' => 'bottom-left', 'label' => __( 'Titel unten - links', 'blue-edgy' ) ), 
        'bottom-right' => array( 'value' => 'bottom-right', 'label' => __( 'Titel unten - rechts', 'blue-edgy' ) ), 
    );
    
    return apply_filters( '_rrze_header_layout_options', $options );
}

function _rrze_typography_options() {
    $options = array(
        'DroidSans' => array('value' => 'DroidSans', 'label' => 'Droid Sans', 'class' => 'droidsans'),
        'LinLibertine' =>  array('value' => 'LinLibertine', 'label' => 'LinLibertine', 'class' => 'linlibertine'),
        'Arial, Helvetica, sans-serif' => array('value' => 'Arial, Helvetica, sans-serif', 'label' => 'Arial, Helvetica, sans-serif', 'class' => 'defaultfont'),
    );
    
    return apply_filters('_rrze_typography_options', $options);
}

function _rrze_default_color_style_data() {
    $color_schemas = _rrze_color_schema_options();
    return $color_schemas['blau']['colors'];
}

function _rrze_color_style_options() {
    $options = RRZE_Theme::$theme_options;
    $colors = $options['color.style'];
    // Farberweiterung von Design RRZE - Default-Werte für noch nicht vergebene Farben
    $additional_colors = array(
        'menu-text' => '#ffffff',
        'menu-hover' => $colors['hover'], 
        'menu-hover-text' => '#ffffff',
        'hover-text' => '#ffffff',
        'widget-hover-text' => '#ffffff',
        'footer-widget-text' => '#d0d0d0',
        'footer-hover-text' => '#ffffff'
        );

    $colors = wp_parse_args($additional_colors, $colors);

    return apply_filters('_rrze_color_style_options', $colors);
}

function _rrze_default_color_style() {
    $colors = _rrze_default_color_style_data();
    $color_style = array( 
        'menu' => array( 
            'label' => __( 'Menüfarbe', 'blue-edgy' ),
            'color' => $colors['menu'] 
         ),
        'menu-text' => array( 
            'label' => __( 'Menü-Textfarbe', 'blue-edgy' ),
            'color' => $colors['menu-text'] 
         ),
        'menu-hover' => array( 
            'label' => __( 'Menü-Hover-Farbe', 'blue-edgy' ),
            'color' => $colors['menu-hover'] 
         ),
        'menu-hover-text' => array( 
            'label' => __( 'Menü-Hover-Textfarbe', 'blue-edgy' ),
            'color' => $colors['menu-hover-text'] 
         ),
        'title' => array( 
            'label' => __( 'Titelfarbe', 'blue-edgy' ),
            'color' => $colors['title'] 
         ),
        'link' => array( 
            'label' => __( 'Linkfarbe', 'blue-edgy' ),
            'color' => $colors['link'] 
         ),        
        'hover' => array( 
            'label' => __( 'Hover-Farbe', 'blue-edgy' ),
            'color' => $colors['hover'] 
         ),
        'hover-text' => array( 
            'label' => __( 'Hover-Textfarbe', 'blue-edgy' ),
            'color' => $colors['hover-text'] 
         ),
        'widget-title' => array( 
            'label' => __( 'Widget-Titel in Spalten', 'blue-edgy' ),
            'color' => $colors['widget-title'] 
         ),
        'widget-linien' => array( 
            'label' => __( 'Widget-Linien in Spalten', 'blue-edgy' ),
            'color' => $colors['widget-linien'] 
         ),
        'widget-hover' => array( 
            'label' => __( 'Widget-Hover-Farbe in Spalten', 'blue-edgy' ),
            'color' => $colors['widget-hover'] 
         ),      
        'widget-hover-text' => array( 
            'label' => __( 'Widget-Hover-Textfarbe in Spalten', 'blue-edgy' ),
            'color' => $colors['widget-hover-text'] 
         ), 
        'footer-widget-title' => array( 
            'label' => __( 'Widget-Titel im Footer', 'blue-edgy' ),
            'color' => $colors['footer-widget-title'] 
         ),
         'footer-widget-text' => array( 
            'label' => __( 'Widget-Textfarbe im Footer', 'blue-edgy' ),
            'color' => $colors['footer-widget-text'] 
         ),
        'footer-widget-linien' => array( 
            'label' => __( 'Widget-Linien im Footer', 'blue-edgy' ),
            'color' => $colors['footer-widget-linien'] 
         ),
        'footer-hover' => array( 
            'label' => __( 'Hover-Farbe im Footer', 'blue-edgy' ),
            'color' => $colors['footer-hover'] 
         ),     
         'footer-hover-text' => array( 
            'label' => __( 'Hover-Textfarbe im Footer', 'blue-edgy' ),
            'color' => $colors['footer-hover-text'] 
         ),        
        'background' => array( 
            'label' => __( 'Hintergrundfarbe', 'blue-edgy' ),
            'color' => $colors['background'] 
         )        
    );

    return apply_filters( '_rrze_default_color_style', $color_style );
}

function _rrze_color_schema_options() {
    $options = array(
        'grau' => array(
            'value' => 'grau',
            'label' => __( 'Grau', 'blue-edgy' ),
            'colors' => array( 'menu' => '#222222', 'menu-text' => '#ffffff', 'menu-hover' => '#515151', 'menu-hover-text' => '#ffffff', 'title' => '#444444', 'link' => '#020202', 'hover' => '#515151', 'hover-text' => '#ffffff', 'widget-title' => '#444444', 'widget-linien' => '#DDDDDD', 'widget-hover' => '#888888', 'widget-hover-text' => '#ffffff', 'footer-widget-title' => '#9E9E9E', 'footer-widget-text' => '#d0d0d0', 'footer-widget-linien' => '#686868', 'footer-hover' => '#303030', 'footer-hover-text' => '#ffffff', 'background' => '#7A7A7A' ),
        ),
        
        'blau' => array(
            'value' => 'blau',
            'label' => __( 'Blau', 'blue-edgy' ),
            'colors' => array( 'menu' => '#00425F', 'menu-text' => '#ffffff', 'menu-hover' => '#005D85', 'menu-hover-text' => '#ffffff', 'title' => '#00425F', 'link' => '#00425F', 'hover' => '#005D85', 'hover-text' => '#ffffff', 'widget-title' => '#00425F', 'widget-linien' => '#B3C6CE', 'widget-hover' => '#6B8EAD', 'widget-hover-text' => '#ffffff', 'footer-widget-title' => '#D0D0D0', 'footer-widget-text' => '#d0d0d0', 'footer-widget-linien' => '#006F9F', 'footer-hover' => '#005D85', 'footer-hover-text' => '#ffffff', 'background' => '#D4D7D9' ),
        ),
            
        'gruen' => array(
            'value' => 'gruen',
            'label' => __( 'Grün', 'blue-edgy' ),
            'colors' => array( 'menu' => '#006600', 'menu-text' => '#ffffff', 'menu-hover' => '#0E510E', 'menu-hover-text' => '#ffffff', 'title' => '#006600', 'link' => '#006600', 'hover' => '#0E510E', 'hover-text' => '#ffffff', 'widget-title' => '#366636', 'widget-linien' => '#8BB797', 'widget-hover' => '#6F9977', 'widget-hover-text' => '#ffffff', 'footer-widget-title' => '#829985', 'footer-widget-text' => '#d0d0d0', 'footer-widget-linien' => '#829985', 'footer-hover' => '#55754D', 'footer-hover-text' => '#ffffff', 'background' => '#E9E7D7' ),
        ),
        
        'rot' => array(
            'value' => 'rot',
            'label' => __( 'Rot', 'blue-edgy' ),
            'colors' => array( 'menu' => '#AF290D', 'menu-text' => '#ffffff', 'menu-hover' => '#B35B22', 'menu-hover-text' => '#ffffff', 'title' => '#B35B22', 'link' => '#B35B22', 'hover' => '#B35B22', 'hover-text' => '#ffffff', 'widget-title' => '#B35B22', 'widget-linien' => '#B29C8E', 'widget-hover' => '#B2876B', 'widget-hover-text' => '#ffffff', 'footer-widget-title' => '#B29C8E', 'footer-widget-text' => '#d0d0d0', 'footer-widget-linien' => '#B29C8E', 'footer-hover' => '#B27349', 'footer-hover-text' => '#ffffff', 'background' => '#BCA279' ),
        ),  
        
        'nat_fak' => array(
            'value' => 'nat_fak',
            'label' => __( 'FAU - Nat. Fak.', 'blue-edgy' ),
            'colors' => array( 'menu' => '#048767', 'menu-text' => '#ffffff', 'menu-hover' => '#006e53', 'menu-hover-text' => '#ffffff', 'title' => '#048767', 'link' => '#006e53', 'hover' => '#006e53', 'hover-text' => '#ffffff', 'widget-title' => '#048767', 'widget-linien' => '#006e53', 'widget-hover' => '#048767', 'widget-hover-text' => '#ffffff', 'footer-widget-title' => '#f8f8f8', 'footer-widget-text' => '#f8f8f8', 'footer-widget-linien' => '#006e53', 'footer-hover' => '#00533f', 'footer-hover-text' => '#ffffff', 'background' => '#e5efea' ),
        ),
        
        'phil_fak' => array(
            'value' => 'phil_fak',
            'label' => __( 'FAU - Phil. Fak.', 'blue-edgy' ),
            'colors' => array( 'menu' => '#a36b0d', 'menu-text' => '#ffffff', 'menu-hover' => '#805000', 'menu-hover-text' => '#ffffff', 'title' => '#a36b0d', 'link' => '#805000', 'hover' => '#805000', 'hover-text' => '#ffffff', 'widget-title' => '#a36b0d', 'widget-linien' => '#805000', 'widget-hover' => '#a36b0d', 'widget-hover-text' => '#ffffff', 'footer-widget-title' => '#f8f8f8', 'footer-widget-text' => '#f8f8f8', 'footer-widget-linien' => '#805000', 'footer-hover' => '#553500', 'footer-hover-text' => '#ffffff', 'background' => '#f3eedf' ),
        ),
        
        'rewi_fak' => array(
            'value' => 'rewi_fak',
            'label' => __( 'FAU - ReWi. Fak.', 'blue-edgy' ),
            'colors' => array( 'menu' => '#8d1429', 'menu-text' => '#ffffff', 'menu-hover' => '#690013', 'menu-hover-text' => '#ffffff', 'title' => '#8d1429', 'link' => '#690013', 'hover' => '#690013', 'hover-text' => '#ffffff', 'widget-title' => '#8d1429', 'widget-linien' => '#690013', 'widget-hover' => '#8d1429', 'widget-hover-text' => '#ffffff', 'footer-widget-title' => '#f8f8f8', 'footer-widget-text' => '#f8f8f8', 'footer-widget-linien' => '#690013', 'footer-hover' => '#42000c', 'footer-hover-text' => '#ffffff', 'background' => '#ede7de' ),
        ),
        
        'med_fak' => array(
            'value' => 'med_fak',
            'label' => __( 'FAU - Med. Fak.', 'blue-edgy' ),
            'colors' => array( 'menu' => '#0381A2', 'menu-text' => '#ffffff', 'menu-hover' => '#026480', 'menu-hover-text' => '#ffffff', 'title' => '#0381A2', 'link' => '#026480', 'hover' => '#026480', 'hover-text' => '#ffffff', 'widget-title' => '#0381A2', 'widget-linien' => '#026480', 'widget-hover' => '#0381A2', 'widget-hover-text' => '#ffffff', 'footer-widget-title' => '#f8f8f8', 'footer-widget-text' => '#f8f8f8', 'footer-widget-linien' => '#026480', 'footer-hover' => '#014e63', 'footer-hover-text' => '#ffffff', 'background' => '#eaf3fc' ),
        ),
        
        'fau' => array(
            'value' => 'fau',
            'label' => __( 'FAU', 'blue-edgy' ),
            'colors' => array( 'menu' => '#003366', 'menu-text' => '#ffffff', 'menu-hover' => '#24598f', 'menu-hover-text' => '#ffffff', 'title' => '#003366', 'link' => '#24598f', 'hover' => '#24598f', 'hover-text' => '#ffffff', 'widget-title' => '#003366', 'widget-linien' => '#24598f', 'widget-hover' => '#003366', 'widget-hover-text' => '#ffffff', 'footer-widget-title' => '#f8f8f8', 'footer-widget-text' => '#f8f8f8', 'footer-widget-linien' => '#24598f', 'footer-hover' => '#1f4c7a', 'footer-hover-text' => '#ffffff', 'background' => '#dde5f0' ),
        ),
        
    );

    return apply_filters( '_rrze_color_schemas', $options );
}

function _rrze_blogoverview_options() {
    $options = array(
        'rrze_content' => array(
            'value' => 'rrze_content',
            'label' => __( 'als vollständigen Artikel', 'blue-edgy' )
        ),
        
        'rrze_excerpt' => array(
            'value' => 'rrze_excerpt',
            'label' => __( 'als kurze Vorschau', 'blue-edgy' )
        )
        
    );

    return apply_filters( '_rrze_blogoverview_options', $options );
}

function _rrze_teaserimage_options() {
    $options = array(
	1 => array ('value' => '1', 'label' => __('Ausgewähltes Bild > erstes Bild > erstes Video > Standardbild', 'blue-edgy')),
	2 => array ('value' => '2', 'label' => __('Erstes Bild > ausgewähltes Bild > erstes Video > Standardbild', 'blue-edgy')),
	3 => array ('value' => '3', 'label' => __('Erstes Video > ausgewähltes Bild > erstes Bild > Standardbild', 'blue-edgy')),
	4 => array ('value' => '4', 'label' => __('Erstes Video > erstes Bild > ausgewähltes Bild > Standardbild', 'blue-edgy')),
	5 => array ('value' => '5', 'label' => __('Kein Teaser-Bild', 'blue-edgy'))
    );
    return apply_filters('_rrze_teaserimage_options', $options);
}

function _rrze_teaserimage_default_options() {
    $options = array(
        true => array(
            'value' => 1,
            'label' => __( 'Platzhalter anzeigen', 'blue-edgy' )
        ),
        
        false => array(
            'value' => 0,
            'label' => __( 'kein Bild anzeigen', 'blue-edgy' )
        )
        
    );

    return apply_filters( '_rrze_teaserimage_default_options', $options );	       
}

function _rrze_section_layout_callback() {
    printf( '<p>%s</p>', __( 'Wählen Sie, welche Optionen Sie aktivieren möchten.', 'blue-edgy' ) );
}

function _rrze_section_typography_callback() {
    printf( '<p>%s</p>', __( 'Wählen Sie, welche Schriftart Sie aktivieren möchten.', 'blue-edgy' ) );
}

function _rrze_section_color_style_callback() {
    printf( '<p>%s</p>', __( 'Wählen Sie, welches Farbschema Sie aktivieren möchten.', 'blue-edgy' ) );
}

function _rrze_section_overview_callback() {
    printf( '<p>%s</p>', __( 'Wählen Sie, ob auf der Übersichtsseite die vollständigen Beiträge oder nur Auszüge angezeigt werden sollen.', 'blue-edgy' ) );
    printf( '<p>%s</p>', __( 'Wenn Sie eine Vorschau anzeigen lassen und der Beitrag keinen Auszug besitzt, wird der Artikel gekürzt dargestellt. Die Anzahl der angezeigten Wörter können Sie unten festlegen.', 'blue-edgy' ) );
}

function _rrze_field_searchform_callback() {
	$options = RRZE_Theme::$theme_options;
	?>
	<select name="_rrze_theme_options[search.form.position]" id="searchform-position">
		<?php
			$selected = $options['search.form.position'];
			$html = '';

			foreach ( _rrze_searchform_options() as $option ) {
				$html .= '<option value="'.esc_attr($option['value']).'"'.($selected == $option['value'] ? ' selected="selected"' : '').'>'.esc_attr($option['label']).'</option>';
			}
			echo $html;
		?>
	</select>
	<?php
}

function _rrze_field_columnlayout_callback() {
	$options = RRZE_Theme::$theme_options;

	foreach ( _rrze_columnlayout_options() as $button ):
	?>
	<div class="layout">
		<label class="description">
			<input type="radio" name="_rrze_theme_options[column.layout]" value="<?php echo esc_attr( $button['value'] ); ?>" <?php checked( $options['column.layout'], $button['value'] ); ?> />
			<?php echo $button['label']; ?>
		</label>
	</div>
	<?php
	endforeach;
}

function _rrze_field_footer_layout_callback() {
	$options = RRZE_Theme::$theme_options;
	?>
	<select name="_rrze_theme_options[footer.layout]" id="footer-layout">
		<?php
			$selected = $options['footer.layout'];
			$html = '';
            
            foreach( _rrze_footer_layout_options() as $option ) {
                $groups[] = $option['group'];
            }
            
            $groups = array_unique($groups);
            foreach($groups as $group) {
                $html .= '<optgroup label="'. esc_attr($group). ' ' .esc_attr( _n( 'Spalte', 'Spalten', $group, 'blue-edgy' ) ) . '" rel="' . esc_attr($group) . '">';
                foreach ( _rrze_footer_layout_options() as $option ) {
                    if($option['group'] == $group) {
                        $html .= '<option value="'.esc_attr($option['value']).'"'.($selected == $option['value'] ? ' selected="selected"' : '').'>'.esc_attr($option['label']).'</option>';
                    }
                }
                $html .= '</optgroup>';
            }
            echo $html;
		?>
	</select>
	<?php

}

function _rrze_field_header_layout_callback() {
	$options = RRZE_Theme::$theme_options;
	?>
	<select name="_rrze_theme_options[header.layout]" id="header-layout">
		<?php
			$selected = $options['header.layout'];
			$html = '';
            
            foreach( _rrze_header_layout_options() as $option ) {               
                $html .= '<option value="'.esc_attr($option['value']).'"'.($selected == $option['value'] ? ' selected="selected"' : '').'>'.esc_attr($option['label']).'</option>';
                    
            }            
            echo $html;
		?>
	</select>
	<?php

}

function _rrze_field_body_typography_callback() {
	$options = RRZE_Theme::$theme_options;    
        ?>
	<select name="_rrze_theme_options[body.typography]" id="body-typography">
		<?php
			$selected = $options['body.typography'];
			$html = '';
            
            foreach(_rrze_typography_options() as $option ) {
                        $html .= '<option value="'.esc_attr($option['value']).'"'.($selected == $option['value'] ? ' selected="selected"' : '').' class="'.$option['class'].'">'.esc_attr($option['label']).'</option>';
                    
            }
            echo $html;
		?>
	</select>
	
	<?php
}

function _rrze_field_heading_typography_callback() {
	$options = RRZE_Theme::$theme_options;
	?>
    	<select name="_rrze_theme_options[heading.typography]" id="heading-typography">
		<?php
			$selected = $options['heading.typography'];
			$html = '';
            
            foreach(_rrze_typography_options() as $option ) {
                        $html .= '<option value="'.esc_attr($option['value']).'"'.($selected == $option['value'] ? ' selected="selected"' : '').' class="'.$option['class'].'">'.esc_attr($option['label']).'</option>';
                    
            }
            echo $html;
		?>
	</select>
	<?php
}

function _rrze_field_menu_typography_callback() {
	$options = RRZE_Theme::$theme_options;
	?>
   	<select name="_rrze_theme_options[menu.typography]" id="menu-typography">
		<?php
			$selected = $options['menu.typography'];
			$html = '';
            
            foreach(_rrze_typography_options() as $option ) {
                        $html .= '<option value="'.esc_attr($option['value']).'"'.($selected == $option['value'] ? ' selected="selected"' : '').' class="'.$option['class'].'">'.esc_attr($option['label']).'</option>';
                    
            }
            echo $html;
		?>
	</select>
	<?php
}

function _rrze_field_widget_title_typography_callback() {
	$options = RRZE_Theme::$theme_options;
	?>    
    	<select name="_rrze_theme_options[widget.title.typography]" id="widget-title-typography">
		<?php
			$selected = $options['widget.title.typography'];
			$html = '';
            
            foreach(_rrze_typography_options() as $option ) {
                        $html .= '<option value="'.esc_attr($option['value']).'"'.($selected == $option['value'] ? ' selected="selected"' : '').' class="'.$option['class'].'">'.esc_attr($option['label']).'</option>';
                    
            }
            echo $html;
		?>
	</select>
	<?php
}

function _rrze_field_widget_content_typography_callback() {
	$options = RRZE_Theme::$theme_options;
	?>           
    	<select name="_rrze_theme_options[widget.content.typography]" id="widget-content-typography">
		<?php
			$selected = $options['widget.content.typography'];
			$html = '';
            
            foreach(_rrze_typography_options() as $option ) {
                        $html .= '<option value="'.esc_attr($option['value']).'"'.($selected == $option['value'] ? ' selected="selected"' : '').' class="'.$option['class'].'">'.esc_attr($option['label']).'</option>';
                    
            }
            echo $html;
		?>
	</select>
	<?php
}

function _rrze_field_color_style_callback() {
    $options = RRZE_Theme::$theme_options;
    $option = _rrze_color_style_options();
    ?>
    <ul class="rrze-section-content">
	<?php foreach ( _rrze_default_color_style() as $key => $style ): ?>
    <li id="rrze-control-header_textcolor" class="rrze-control rrze-control-color">
    <label>
    <span class="rrze-control-title"><?php echo $style['label']; ?></span>
    <input type="text" data-default-color="<?php echo $style['color']; ?>" value="<?php echo $option[$key]; ?>" class="color-picker-field" />
    </label>
    </li>
	<?php endforeach; ?>
    </ul>
    <?php
}

function _rrze_field_color_schema_callback() {
    $options = RRZE_Theme::$theme_options;
    $color_schema = $options['color.schema'];
    $custom_colors = array_map( 'strtoupper', _rrze_theme_options('color.style') );
    $color_schema_options = _rrze_color_schema_options();
    foreach( $color_schema_options as $option ) {
        $colors = array_map( 'strtoupper', $option['colors'] );
        if( $options['color.schema'] == $option['value'] && array_diff_assoc( $custom_colors, $colors ) ) {
            $color_schema = '_custom';
            
            $color_schema_options[$color_schema] = array(
                'value' => $color_schema,
                'label' => __( 'Benutzerdefiniertes', 'blue-edgy' ),
                'colors' => $custom_colors
            );
            
        }
    }
    
    ksort( $color_schema_options );
    
	foreach ( $color_schema_options as $option ):
        $label = '';
        $colors = $option['colors'];
	?>
	<div class="layout">
		<label class="description">
			<input type="radio" name="_rrze_theme_options[color.schema]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php checked( $color_schema, $option['value'] ); ?> />
            <?php
            sort( $colors );
            foreach ( $colors as $color ) {
                $label .= '<span style="width: 20px; height: 20px; min-width: 20px; padding: 0; margin: 0 1px 4px 0; line-height: 20px; display: inline-block; background-color: ' . $color . '" title="' . $option['label'] . ' ">&nbsp;</span>';
            }

            $label .= '<span style="width: 6px; display: inline-block;">&nbsp;</span>';
            $label .= $option['label'];
            echo $label;
            ?>
		</label>
	</div>
	<?php
	endforeach;
}

function _rrze_field_blogoverview_callback() {
	$options = RRZE_Theme::$theme_options;

	foreach ( _rrze_blogoverview_options() as $button ):
	?>
	<div class="layout">
		<label class="description">
			<input type="radio" name="_rrze_theme_options[blog.overview]" value="<?php echo esc_attr( $button['value'] ); ?>" <?php checked( $options['blog.overview'], $button['value'] ); ?> />
			<?php echo $button['label']; ?>
		</label>
	</div>
	<?php
	endforeach;
}

function _rrze_field_wordsoverview_callback() {
	$options = RRZE_Theme::$theme_options;
	?>
    <input type="text" class="regular-text" name="_rrze_theme_options[words.overview]" value="<?php echo esc_attr($options['words.overview']); ?>" />
    <?php printf ('<span class="description">%s</span>', __('Lassen Sie die Anzeige leer, um den Standardwert wieder herzustellen.', 'blue-edgy')); ?>
    
	<?php
}

function _rrze_field_teaserimage_callback() {
	$options = RRZE_Theme::$theme_options;
	?>
	<select name="_rrze_theme_options[teaser.image]" id="teaser-image">
		<?php
			$selected = $options['teaser.image'];
			$html = '';
            
            foreach( _rrze_teaserimage_options() as $option ) {               
                $html .= '<option value="'.esc_attr($option['value']).'"'.($selected == $option['value'] ? ' selected="selected"' : '').'>'.esc_attr($option['label']).'</option>';
                    
            }            
            echo $html;
		?>
	</select>
       <?php printf ('<span class="description">%s</span>', __('Links des Textauszugs wird das Beitragsbild, ein Bild des Artikels, ein verlinktes Video oder ein Standardbild angezeigt (wenn vorhanden).', 'blue-edgy')); ?>
	<?php
}

function _rrze_field_teaserimage_default_callback() {
 	$options = RRZE_Theme::$theme_options;
        $name = 'default-teaserimage';
        if ((isset($options[$name])) && esc_url( $options[$name])) { 
            echo '<div class="previewimage showimg">';
		echo '<img src="'.esc_url( $options[$name]).'"/>';
                printf ('<span class="description">%s</span>', __('Platzhalter-Bild für Vorschau', 'blue-edgy'));
                echo '</div>';
	} else {
		_e('Es ist kein Platzhalter-Bild vorhanden.', 'blue-edgy');
	}				   
	foreach (_rrze_teaserimage_default_options() as $button ):
	?>
	<div class="layout">
		<label class="description">
			<input type="radio" name="_rrze_theme_options[teaser.image.default]" value="<?php echo esc_attr( $button['value'] ); ?>" <?php checked( $options['teaser.image.default'], $button['value'] ); ?> />
			<?php echo $button['label']; ?>
		</label>
	</div>
	<?php
	endforeach;   
}

function _rrze_theme_options_menu_page() {
    $pages = RRZE_Theme::$options_pages;
    $tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $pages ) ? $_GET['tab'] : 'layout.options';
    ?>
    <div class="wrap">

        <h2><?php _e( 'Einstellungen', 'blue-edgy' );?></h2>
        <?php settings_errors(); ?>
        <h2 class="nav-tab-wrapper">
            <?php foreach( $pages as $page):
                $active = ( $page['value'] == $tab ) ? 'nav-tab-active' : '';
                printf( '<a href="?page=theme_options&tab=%s" class="nav-tab %s">%s</a>', $page['value'], $active, $page['label']  );
            endforeach; ?>
        </h2>

        <form method="post" action="options.php">
            <?php 
            settings_fields( $tab );
            do_settings_sections( $tab );
            submit_button();
            ?>
        </form>

    </div>
    <?php
}

function _rrze_theme_options_validate( $input ) {
    $default_options = RRZE_Theme::$default_theme_options;
    $options = RRZE_Theme::$theme_options;

    $custom_schema = '_custom';
    $custom_colors = array_map( 'strtoupper', _rrze_color_style_options() );
    $color_schema_options = _rrze_color_schema_options();
    
    foreach( $color_schema_options as $option ) {
        $colors = array_map( 'strtoupper', $option['colors'] );
        if( $options['color.schema'] == $option['value'] && array_diff_assoc( $custom_colors, $colors ) )
            $color_schema_options[$custom_schema] = array();
    }
    
	if ( isset( $input['search.form.position'] ) && array_key_exists( $input['search.form.position'], _rrze_searchform_options() ) )
		$options['search.form.position'] = $input['search.form.position'];
    
	if ( isset( $input['column.layout'] ) && array_key_exists( $input['column.layout'], _rrze_columnlayout_options() ) )
		$options['column.layout'] = $input['column.layout'];

	if ( isset( $input['footer.layout'] ) && array_key_exists( $input['footer.layout'], _rrze_footer_layout_options() ) )
		$options['footer.layout'] = $input['footer.layout'];
        
	if ( isset( $input['header.layout'] ) && array_key_exists( $input['header.layout'], _rrze_header_layout_options() ) )
		$options['header.layout'] = $input['header.layout'];
        
        if ( isset( $input['body.typography'] ) && array_key_exists( $input['body.typography'], _rrze_typography_options() ) )
            $options['body.typography'] = $input['body.typography'];

    
        if ( isset( $input['heading.typography'] ) && array_key_exists( $input['heading.typography'], _rrze_typography_options() ) )
            $options['heading.typography'] = $input['heading.typography'];
        
        if ( isset( $input['menu.typography'] ) && array_key_exists( $input['menu.typography'], _rrze_typography_options() ) )
            $options['menu.typography'] = $input['menu.typography'];
        
        if ( isset( $input['widget.title.typography'] ) && array_key_exists( $input['widget.title.typography'], _rrze_typography_options() ) )
            $options['widget.title.typography'] = $input['widget.title.typography'];

        if ( isset( $input['widget.content.typography'] ) && array_key_exists( $input['widget.content.typography'], _rrze_typography_options() ) )
            $options['widget.content.typography'] = $input['widget.content.typography'];
        

    
	if ( isset( $input['color.schema'] ) && array_key_exists( $input['color.schema'], $color_schema_options ) ) {
        if( $input['color.schema'] == $custom_schema ) {
            $options['color.style'] = $custom_colors;
        } else {
            $options['color.style'] = $color_schema_options[$input['color.schema']]['colors'];       
            $options['color.schema'] = $input['color.schema'];
        }
    }
    
	if ( isset( $input['color.style'] )  )
		$options['color.style'] = $input['color.style'];
        	
        if ( isset( $input['blog.overview'] ) && array_key_exists( $input['blog.overview'], _rrze_blogoverview_options() ) )
		$options['blog.overview'] = $input['blog.overview'];

        if ( !empty( $input['words.overview'] ) ) {

            if( _rrze_validate_words( $input['words.overview'] ) ) 
                $options['words.overview'] = (int)$input['words.overview'];
            else
                $options['words.overview'] = $default_options['words.overview'];
        } else {
            $options['words.overview'] = $default_options['words.overview'];
        }
        
	if ( isset( $input['teaser.image'] ) && array_key_exists( $input['teaser.image'], _rrze_teaserimage_options() ) )
		$options['teaser.image'] = $input['teaser.image'];
        
        if ( isset( $input['teaser.image.default'] ) && array_key_exists( $input['teaser.image.default'], _rrze_teaserimage_default_options() ) )
		$options['teaser.image.default'] = $input['teaser.image.default'];
        
    return apply_filters( '_rrze_theme_options_validate', $options, $input );
}

function _rrze_validate_font_family( $str ) {
    if( preg_match( '/^([a-z0-9,_-\s"])+$/i', $str ) )
        return true;
    
    return false;
}

function _rrze_replace_whitespaces( $str ) {
    $str = preg_replace( '/\s+/', ' ', $str );
    return implode( ', ', array_map( 'trim', explode( ',', $str ) ) );
}

function _rrze_validate_words( $str ) {
    $str = (int)$str;
    if($str>0)
        return true;
    
    return false;
}

function _rrze_validate_hex_color( $str ) {
    if( preg_match( '/^#[a-f0-9]{6}$/i', $str ) )
        return true;
    
    return false;
}

/*
 * Theme customizer
 */
add_action( 'customize_register', function( $wp_customize ) {
    $options = RRZE_Theme::$theme_options;
    
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
    
    // color.style
    $color_style = _rrze_color_style_options();
   
    $default_color_style = _rrze_default_color_style();

    $i = 20;
    foreach( $default_color_style as $key => $style ) {
        $wp_customize->add_setting( '_rrze_theme_options[color.style][' . $key . ']', array(
            'type' => 'option',
            'default' => $color_style[$key]
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( 
            $wp_customize, 
            '_rrze_theme_options_color_style-' . $key, 
            array(
                'label' => $style['label'],
                'section' => 'colors',
                'settings' => '_rrze_theme_options[color.style][' . $key . ']',
                'priority' => $i
            )
        ) );
        $i++;
    }
    
    // column.layout
	$wp_customize->add_section( '_rrze_theme_layout', array(
		'title'    => __( 'Layout', 'blue-edgy' ),
		'priority' => 100,
	) );
    
	$wp_customize->add_setting( '_rrze_theme_options[column.layout]', array(
		'type' => 'option',
		'default' => $options['column.layout'],
        'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_key',
	) );

	$choices = array();
	foreach ( _rrze_columnlayout_options() as $option ) {
		$choices[$option['value']] = $option['label'];
	}

	$wp_customize->add_control( '_rrze_theme_options_columns_layout', array(
        'label'      => __( 'Spalten', 'blue-edgy' ),        
		'section'    => '_rrze_theme_layout',
		'type'       => 'radio',
		'choices'    => $choices,
        'settings' => '_rrze_theme_options[column.layout]',
        'priority' => 10
	) );
    
    // footer.layout
	$wp_customize->add_setting( '_rrze_theme_options[footer.layout]', array(
		'type' => 'option',
		'default' => $options['footer.layout'],
        'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_key',
	) );

	$choices = array();
	foreach ( _rrze_footer_layout_options() as $option ) {
		$choices[$option['value']] = $option['label'];
	}

	$wp_customize->add_control( '_rrze_theme_options_footer_layout', array(
        'label'      => __( 'Footer (Widgets)', 'blue-edgy' ),        
		'section'    => '_rrze_theme_layout',
		'type'       => 'select',
		'choices'    => $choices,
        'settings' => '_rrze_theme_options[footer.layout]',
        'priority' => 20
	) );
  
} );

add_action( 'customize_preview_init', function() {
    wp_enqueue_script( 'theme-customizer', sprintf('%s/js/theme-customizer.js', get_template_directory_uri() ), array( 'jquery', 'customize-preview' ), false, true );
} );

<?php

/*
 * Tabelle-Shortcode
 * Verwendungsbeispiel:
 * [ym-table class="bordertable" delimiter=";"]
 * Artikelnummer;Artikelname Name;Preis inkl. MwSt
 * 1351100;Buttertoast 500g;1,50 €
 * 1351416;Blütenhonig 500g;3,50 €
 * 1351486;Markenzwieback 250g:br:im Moment nicht verfügbar;1,95 €
 * [/ym-table]
 */
add_shortcode('ym-table', function($atts, $content = '') {
    extract(shortcode_atts(array(
        'class' => '',
        'delimiter' => ';'
                    ), $atts));

    $content = str_replace(array('<br />', '<br/>', '<br>'), array('', '', ''), $content);
    $content = str_replace('<p>', PHP_EOL, $content);
    $content = str_replace('</p>', '', $content);

    $content = str_replace('&nbsp;', '', $content);

    $content = str_replace(':br:', '<br>', $content);

    $char_codes = array('&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8242;', '&#8243;');
    $replacements = array("'", "'", '"', '"', "'", '"');
    $content = trim(str_replace($char_codes, $replacements, $content));

    $class = !empty($class) ? sprintf(' class="%s"', $class) : '';

    $content = explode(PHP_EOL, $content);

    $output = '';
    if (empty($content[0])) {
        return $output;
    }

    $thead = explode($delimiter, $content[0]);

    array_shift($content);

    $output .= sprintf('<table%1$s>%2$s', $class, PHP_EOL);

    $output .= sprintf('<thead><tr>%s', PHP_EOL);
    foreach ($thead as $col) {
        $output .= sprintf('<th>%1$s</th>%2$s', $col, PHP_EOL);
    }
    $output .= sprintf('</tr></thead>%s', PHP_EOL);

    $output .= sprintf('<tbody>%s', PHP_EOL);
    foreach ($content as $row) {
        $output .= sprintf('<tr>%s', PHP_EOL);
        $cols = explode($delimiter, $row);
        if (!empty($row) && count($cols) == count($thead)) {
            foreach ($cols as $col) {
                $output .= sprintf('<td>%1$s</td>%2$s', $col, PHP_EOL);
            }
        }
        $output .= sprintf('</tr>%s', PHP_EOL);
    }
    $output .= sprintf('</tbody>%s', PHP_EOL);

    $output .= sprintf('</table>%s', PHP_EOL);

    return $output;
});

/*
 * Reiter-Shortcode
 * Verwendungsbeispiel:
 * [ym-tabs]
 *    [ym-tab title="Erste Reiter"]Inhalt für den ersten Reiter geht hier.[/ym-tab]
 *    [ym-tab title="Zweite Reiter"]Inhalt für den zweiten Reiter geht hier.[/ym-tab]
 *    [ym-tab title="Dritte Reiter"]Inhalt für den dritten Reiter geht hier.[/ym-tab]
 * [/ym-tabs]

 * [/ym-tabs]
 */
add_shortcode('ym-tabs', function($atts, $content = '') {
    extract(shortcode_atts(array(
        'class' => ''
                    ), $atts));

    $content = do_shortcode($content);

    $output = '';
    if (empty($content)) {
        return $output;
    }

    $output .= sprintf('<div class="jquery_tabs">%s', PHP_EOL);
    $output .= sprintf('%1$s%2$s', $content, PHP_EOL);
    $output .= sprintf('</div>%s', PHP_EOL);

    return $output;
});

add_shortcode('ym-tab', function($atts, $content = '') {
    extract(shortcode_atts(array(
        'title' => ''
                    ), $atts));

    $output = '';
    if (empty($title) || empty($content)) {
        return $output;
    }

    $output .= sprintf('<h4>%1$s</h4>%2$s', $title, PHP_EOL);
    $output .= sprintf('<div class="tabbody">%s', PHP_EOL);
    $output .= sprintf('%1$s%2$s', $content, PHP_EOL);
    $output .= sprintf('</div>%s', PHP_EOL);

    return $output;
});

/* 
 * Accordion analog zu Design Luna
 * Funktionsweise:
 * 
 * [collapsibles]
 * [collapse title="Erste Überschrift"]
 * Erster Text Text Text...
 * [/collapse]
 * [collapse title="Zweite Überschrift"]
 * Zweiter Text Text Text...    
 * [/collapse]  
 * [collapse title="Dritte Überschrift"]
 * Dritter Text Text Text...
 * [/collapse]
 * [/collapsibles]
 * 
 * [collapse] und [/collapse] jeweils um die einzelnen Textabschnitte, [collapsibles] und [/collapsibles] einmal um den gesamten Accordion-Block herum.
 * 
 */



add_shortcode('collapsibles', function($atts, $content = null) {
    add_action('wp_footer', function($atts, $content = null) {
        wp_enqueue_script( 'accordions' );
    });
    $content = shortcode_unautop(trim($content));
    return '<div class="accordion">' . PHP_EOL . apply_filters( 'the_content', $content ) . '</div>' . PHP_EOL;
    //return '<div class="accordion">' . do_shortcode($content) . '</div>';
});

add_shortcode('collapse', function($atts, $content = null) {
    extract(shortcode_atts(
        array(
            "title" => ''
        ), $atts));
    $output = '';
    $title = sanitize_text_field($title);
    $output .= '<h2>' . $title . '</h2>';
    $output .= '<div>' . PHP_EOL;
    $output .= apply_filters( 'the_content', $content );
    //$output .= do_shortcode($content);
    $output .= '</div>' . PHP_EOL;
    return $output;
});



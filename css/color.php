<?php
echo <<<EOL
@media screen {
EOL;
echo PHP_EOL;
echo <<<EOL
    div#kopf, div#main  { background-color: [background]; }
    a { color: [link]; }
    a:hover, a:focus { background-color: [hover]; color: [hover-text];}
    h1, h2, h3, h4, h5, h6 { color: [title]; }
    .header-block h1 a, .header-block h1 a:focus, .header-block h1 a:hover { color: [title]; }
    .entry-content a, .entry-summary a, .commentlist a { color: [link]; }
    .entry-content a:hover, .entry-content a:focus { background-color: [hover]; color: [hover-text]; }
    nav#breadcrumb div.ym-hlist ul li a:focus,
    nav#breadcrumb div.ym-hlist ul li a:hover,
    nav#breadcrumb div.ym-hlist ul li a:active { background-color: [hover]; color: [hover-text]; }
    .navmenu, 
    .navmenu ul.dropdown { background: [menu]; }
    .navmenu ul.dropdown ul { background: [menu]; }
    .navmenu ul.dropdown a,
    .navmenu ul.dropdown a:visited,	
    .navmenu ul.dropdown a:hover, 		
    .navmenu ul.dropdown a:active { color: [menu-text]; }
    .navmenu ul.dropdown li a:hover { background-color: [menu-hover]; color: [menu-hover-text]; }
    .navmenu ul.dropdown ul li { border-bottom:1px solid [background]; }
    .navmenu ul.dropdown ul li a:hover { background-color: [menu-hover]; color: [menu-hover-text]; }
    .navmenu ul.dropdown li.divider { background-color: [background]; }
    nav#tecmenu,
    nav#tecmenu ul.dropdown { background: none repeat scroll 0 0 [menu]; }
    nav#tecmenu ul.dropdown ul { background: [menu]; }
    nav#tecmenu ul.dropdown a,
    nav#tecmenu ul.dropdown a:visited,	
    nav#tecmenu ul.dropdown a:hover, 		
    nav#tecmenu ul.dropdown a:active { color: [footer-widget-title]; }
    nav#tecmenu ul.dropdown li:hover { background-color: [footer-hover]; color: [footer-hover-text]; }
    nav#tecmenu ul.dropdown ul li { border-bottom:1px solid [background]; }
    nav#tecmenu ul.dropdown li a:hover { background-color: [footer-hover]; color: [footer-hover-text]; }
    aside .widget-title { border-bottom-color: [widget-title]; color: [widget-title]; }
    aside .widget-title a { color: [widget-title] !important; }
    aside .widget-title a:hover, aside .widget-title a:focus, aside .widget-title a:active { background-color: [widget-hover]; color: [widget-hover-text] !important; }
    aside table#wp-calendar { border-top-color: [widget-title]; border-bottom-color: [widget-linien]; }
    aside table#wp-calendar a, 
    aside table#wp-calendar a:visited { background-color: [widget-linien]; }
    aside table#wp-calendar a:focus, 
    aside table#wp-calendar a:hover,
    aside table#wp-calendar a:active { background-color: [widget-linien]; }
    aside tbody td { border-top: 1px solid [widget-linien]; }
    aside thead th { border-bottom-color: [widget-linien]; }
    aside tbody tr:hover th, 
    aside tbody tr:hover td { background-color: [widget-hover]; color: [widget-hover-text]; }
    aside table#wp-calendar tfoot a, 
    aside table#wp-calendar tfoot a:visited { background-color: transparent; }
    aside table#wp-calendar tfoot a:focus,
    aside table#wp-calendar tfoot a:hover,
    aside table#wp-calendar tfoot a:active { background-color: [widget-hover]; color: [widget-hover-text]; }
    aside .widget_text a, 
    aside .widget_text a:visited { color: [widget-title] !important; }
    aside .widget_text a:hover, 
    aside .widget_text a:focus { background-color: [widget-hover]; color: [widget-hover-text] !important; }
    aside .tagcloud, 
    aside .textwidget { border-bottom-color: [widget-linien]; }
    aside .tagcloud a:hover, 
    aside .tagcloud a:focus { background-color: [widget-hover]; color: [widget-hover-text]; }
    .searchbutton { background-color: [widget-title]; }
    .ym-vlist ul { border-bottom-color: [widget-linien]; }
	.ym-vlist a { border-bottom-color: [widget-linien]; }
	.ym-vlist li a:focus,
	.ym-vlist li a:hover,
	.ym-vlist li a:active { background-color: [widget-hover]; color: [widget-hover-text]; }
	.ym-vlist li ul li a,
	.ym-vlist li ul li a:visited { background-color: transparent; color: #444; }
	.ym-vlist li ul li a:focus,
	.ym-vlist li ul li a:hover,
	.ym-vlist li ul li a:active { background-color: [widget-hover]; color: [widget-hover-text]; }
	.ym-vlist li ul li ul li a,
	.ym-vlist li ul li ul li a:visited{ background-color: transparent; color: #444; }
	.ym-vlist li ul li ul li a:focus,
	.ym-vlist li ul li ul li a:hover,
	.ym-vlist li ul li ul li a:active { background-color: [widget-hover]; color: [widget-hover-text]; }
	.ym-vlist li ul li ul li ul li a,
	.ym-vlist li ul li ul li ul li a:visited { background-color:transparent; color: #444; }
	.ym-vlist li ul li ul li ul li a:focus,
	.ym-vlist li ul li ul li ul li a:hover,
	.ym-vlist li ul li ul li ul li a:active { background-color: [widget-hover]; color: [widget-hover-text]; }
    .accordion h2 { border-top-color: [menu]; }
    body > footer { background-image: linear-gradient(270deg, [menu], [footer-hover]);
        background-image: -webkit-linear-gradient(270deg, [menu], [footer-hover]);
        background-image: -moz-linear-gradient(270deg, [menu], [footer-hover]);
        background-image: -o-linear-gradient(270deg, [menu], [footer-hover]);
        background-image: -ms-linear-gradient(270deg, [menu], [footer-hover]);
        color: [footer-widget-text];
    }
    div#main div.ym-wrapper { border-bottom-color: [footer-widget-title]; }
    footer .ym-vlist ul { border-color: [footer-widget-linien]; }
    footer .ym-vlist a { border-color: [footer-widget-linien]; }
    footer .widget-title { border-bottom-color: [footer-widget-title]; color: [footer-widget-title]; }
    footer .widget-title a { color: [footer-widget-title] !important; }
    footer .widget-title a:hover, footer .widget-title a:focus, footer .widget-title a:active { background-color: [footer-hover]; color: [footer-hover-text] !important; }
    footer .searchbutton { background-color: [footer-widget-linien]; }
    footer table#wp-calendar { border-top-color: [footer-widget-title]; border-bottom-color: [footer-widget-linien]; color: [footer-widget-text]; }
    footer table#wp-calendar a, 
    footer table#wp-calendar a:visited { background-color: [footer-widget-linien]; color: #fff; }
    footer table#wp-calendar a:focus, 
    footer table#wp-calendar a:hover,
    footer table#wp-calendar a:active { background-color: [footer-widget-linien]; }
    footer tbody td { border-top: 1px solid [footer-widget-linien]; color: [footer-widget-text]; }
    footer thead th { border-bottom-color: [footer-widget-linien]; color: [footer-widget-text]; }
    footer tbody tr:hover th, 
    footer tbody tr:hover td { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer table#wp-calendar tfoot a, 
    footer table#wp-calendar tfoot a:visited { background-color: transparent; color: [footer-widget-text]; }
    footer table#wp-calendar tfoot a:focus,
    footer table#wp-calendar tfoot a:hover,
    footer table#wp-calendar tfoot a:active { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer .widget_text a, 
    footer .widget_text a:visited { color: [footer-widget-title] !important; }
    footer .widget_text a:hover, 
    footer .widget_text a:focus { background-color: [footer-hover]; color: [footer-hover-text] !important; }
    footer .tagcloud, 
    footer .textwidget { border-bottom-color: [footer-widget-linien]; }
    footer .tagcloud a:hover, 
    footer .tagcloud a:focus { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer li.recentcomments { border-bottom-color: [footer-hover]; }
    footer .ym-vlist li a, 
    footer .ym-vlist li a:visited { color: [footer-widget-text]; }
    footer .ym-vlist li a:focus,
    footer .ym-vlist li a:hover,
    footer .ym-vlist li a:active { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer .ym-vlist li.current-menu-item > a,
    footer .ym-vlist li.current_page_item > a,
    footer .ym-vlist li.current-cat > a { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer .ym-vlist li ul li a,
    footer .ym-vlist li ul li a:visited { color: [footer-widget-text]; }
    footer .ym-vlist li ul li a:focus,
    footer .ym-vlist li ul li a:hover,
    footer .ym-vlist li ul li a:active { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer .ym-vlist li ul li.current-menu-item > a,
    footer .ym-vlist li ul li.current_page_item > a,
    footer .ym-vlist li ul li.current-cat > a { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer .ym-vlist li ul li ul li a,
    footer .ym-vlist li ul li ul li a:visited { color: [footer-widget-text]; }
    footer .ym-vlist li ul li ul li a:focus,
    footer .ym-vlist li ul li ul li a:hover,
    footer .ym-vlist li ul li ul li a:active { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer .ym-vlist li ul li ul li.current-menu-item  > a,
    footer .ym-vlist li ul li ul li.current_page_item  > a,
    footer .ym-vlist li ul li ul li.current-cat  > a { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer .ym-vlist li ul li ul li ul li a:focus,
    footer .ym-vlist li ul li ul li ul li a:hover,
    footer .ym-vlist li ul li ul li ul li a:active { background-color: [footer-hover]; color: [footer-hover-text]; }
    footer .ym-vlist li ul li ul li ul li.current-menu-item > a,
    footer .ym-vlist li ul li ul li ul li.current_page_item > a,
    footer .ym-vlist li ul li ul li ul li.current-cat > a { background-color: [footer-hover]; color: [footer-hover-text]; }
EOL;
echo PHP_EOL;
echo <<<EOL
}
EOL;
echo PHP_EOL;
echo <<<EOL
@media screen and (max-width: 760px) {
EOL;
echo PHP_EOL;
echo <<<EOL
    .menu-icon span { background-color: [menu-hover]; }
    ul.dropdown{ border-top:1px solid [background]; }
    ul.dropdown  li { border-bottom: 1px solid [background];}
    ul.dropdown  ul a:hover  { background-color: [menu-hover]; color: [menu-hover-text];}
    ul.dropdown li.sub > a.current{ background: [menu-hover]; color: [menu-hover-text]; }
    ul.dropdown li.sub > ul a.current{ background: [menu-hover]; color: [menu-hover-text]; }
    ul.dropdown li.sub > a:after { color: [background]; }	
    ul.dropdown li.sub > a.current:after { color: [background]; }
    .searchform { background-color: [hover]; 
EOL;
echo PHP_EOL;
echo <<<EOL
}
EOL;
echo PHP_EOL;
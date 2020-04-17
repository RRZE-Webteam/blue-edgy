                </div>
            </div>
        </div>
        
        <nav id="tecmenu">
            <div class="ym-wrapper">
                <h2 class="ym-skip"><a name="hilfemarke" id="hilfemarke"><?php _e( 'Technisches Menü', 'blue-edgy' ); ?></a></h2>
                <?php echo Theme_Tags::tecmenu(); ?>
            </div>
            <div class="ym-clearfix"></div>
        </nav>
        
        <footer>
            <div class="ym-wrapper">
                <?php if ( is_active_sidebar( 'sidebar-footer-left' ) || is_active_sidebar( 'sidebar-footer-center' ) || is_active_sidebar( 'sidebar-footer-right' ) ) : ?>
                <div class="ym-wbox">
                    <div id="zusatzinfo" class="ym-noprint">	
                        <h2 class="ym-skip"><a name="zusatzinfomarke" id="zusatzinfomarke"><?php _e( 'Zusatzinformationen', 'blue-edgy' ); ?></a></h2>

                        <div class="ym-column">
                            <div class="ym-column linearize-level-1">
                                <?php if( count( explode( '-', _rrze_theme_options( 'footer.layout' ) ) ) == 1 ) : ?>
                                <aside class="ym-col1">
                                    <?php get_sidebar( 'footer-center' ); ?>
                                </aside>
                                <?php endif;?>                                
                            
                                <?php if( count( explode( '-', _rrze_theme_options( 'footer.layout' ) ) ) == 2 ) : ?>
                                <aside class="ym-col1">
                                    <?php get_sidebar( 'footer-left' ); ?>
                                </aside>
                                <aside class="ym-col2">
                                    <?php get_sidebar( 'footer-right' ); ?>
                                </aside>
                                
                                <?php endif;?>
                                
                                <?php if( count( explode( '-', _rrze_theme_options( 'footer.layout' ) ) ) == 3 ) : ?> 
                                <aside class="ym-col1">
                                    <?php get_sidebar( 'footer-left' ); ?>
                                </aside>
                                <aside class="ym-col2">
                                    <div class="ym-cbox">
                                        <?php get_sidebar( 'footer-center' ); ?>
                                    </div>
                                </aside>
                                <aside class="ym-col3">
                                    <?php get_sidebar( 'footer-right' ); ?>
                                </aside>
                                
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </footer>

        <?php wp_footer(); ?>
    </body>
</html>

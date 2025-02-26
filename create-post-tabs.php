<?php
  /*
 * Plugin Name:       Create Post Tabs
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Nur Islam
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

 class create_post_tabs {

    function __construct() {
        add_shortcode( 'post_tabs', [$this, 'post_tabs_display_hook'] );
        add_action( 'wp_enqueue_scripts', [$this, 'additioal_src']);
        add_action('wp_ajax_my_post_tabs', [$this, 'post_ajax']);
        define('PLG_SRC_DRI', plugin_dir_url( __FILE__ ).'assets/');

    }

    function post_tabs_display_hook() {
        ob_start(); ?>
        
        <div class="poat-item-tabs-container">
            <ul class="cat-items">
            <?php
             $terms = get_terms( [
                'taxonomy'   => 'category',
                'hide_empty' => false,
            ] ); 

                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
                    foreach ( $terms as $term ) {?>
                        <li class="cat-item" data-target="<?php echo esc_attr($term->term_id) ?>" ><?php echo esc_html($term->name); ?></li>
                    <?php }
                } 
                ?>
            </ul>

            <div class="tab-content">
                <div class="content-item open">
                <?php
                  $args = [
                    'post_type' => 'post',
                  ];
                    $query = new WP_Query( $args );
                    while ( $query->have_posts() ) : $query->the_post() ; ?>
                        <h3><?php echo esc_html(get_the_title()); ?></h3>
                        <div><?php the_post_thumbnail(); ?></div>
                        <p><?php echo esc_html(the_content()); ?></p>
                        <?php
                    endwhile;
                    ?>
                </div>
            </div>
        </div>

        <?php return ob_get_clean();
    }

    function additioal_src(){
        wp_enqueue_style( 'style', PLG_SRC_DRI.'css/style.css' );
        wp_enqueue_script( 'custom-js', PLG_SRC_DRI.'js/custom.js', ['jquery'], true);
        wp_localize_script('my_ajax_handaler', 'my_post_tabs', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ]);
        
    }

    function post_ajax(){

    }
}

new create_post_tabs();

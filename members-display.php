<?php
/**
 * Plugin Name: Members Display
 * Description: Display all members on site.
 * Version: 1.0
 * Author: Denys Zaloha
 * Text Domain: members-display
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Members Display
 *
 * @class Members_Display
 * @version 1.0
 */
class Members_Display {
    /**
     * @var Members_Display - single instance of the class.
     */
    protected static $_instance = null;

    public $loaded_styles = false;

    /**
     * Members_Display instance.
     *
     * @static
     * @return Members_Display - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Constructor.
     */
    function __construct() {
        add_action( 'init', array( $this, 'register_news_post_type' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

        add_action( 'wp_ajax_members_display', array( $this, 'members_display_ajax' ) );
        add_action( 'wp_ajax_nopriv_members_display', array( $this, 'members_display_ajax' ) );

        add_filter( 'template_include', array( $this, 'include_template_function' ), 1 );



    }

    function members_display ( $term_id = false ) {
        $args = array(
            'post_type' => 'speakers_display',
            'numberposts' => -1
        );

        if ( $term_id ) {
            $args[ 'tax_query' ] = array(
                array(
                    'taxonomy' => 'speakers_position_country',
                    'field' => 'term_id',
                    'terms' => $term_id,
                    'include_children' => false
                )
            );
        }

        if ( $speakers = get_posts( $args ) ) {
            global $post;
            ?>

            <?php foreach ( $speakers as $speaker ): ?>
                <?php
                $post = $speaker; setup_postdata( $post );
                $post_id = get_the_ID();

                //$test_field = get_field('test_field');
                //$test_field = get_post_meta( $post_id, 'test_field', true );

                ?>

                <div class="speakers-list-item">

                    <?php the_post_thumbnail(); ?><br>
                    <a href="<?php echo get_permalink($post_id); ?>"><?php the_title(); ?></a> <br>
                </div>

            <?php endforeach; ?>

            <?php wp_reset_postdata(); ?>

            <?php
        } else {
            ?>
            No found.
            <?php
        }
    }

    function members_display_ajax() {
        $term_id = empty( $_POST['term_id'] ) ? false : intval( $_POST['term_id'] );

        $this->members_display( $term_id );

        exit;
    }

    /**
     * Register Members post type.
     */
    function register_news_post_type() {
        register_post_type( 'speakers_display', array(
            'labels' => array(
                'name' => _x( 'Speakers', 'post type general name', 'speakers' ),
                'singular_name' => _x( 'Speakers', 'post type singular name', 'speakers' ),
                'menu_name' => _x( 'Speakers', 'admin menu', 'speakers' ),
                'name_admin_bar' => _x( 'Speakers', 'add new on admin bar', 'speakers' ),
                'add_new' => _x( 'Add New', 'New Member', 'speakers' ),
                'add_new_item' => __( 'Add New Speakers', 'speakers' ),
                'new_item' => __( 'New Speakers', 'speakers' ),
                'edit_item' => __( 'Edit Speakers', 'speakers' ),
                'view_item' => __( 'View Speakers', 'speakers' ),
                'all_items' => __( 'All Speakers', 'speakers' ),
                'search_items' => __( 'Search Speakers', 'speakers' ),
                'not_found' => __( 'No Speakers found.', 'speakers' ),
                'not_found_in_trash' => __( 'No Speakers found in Trash.', 'speakers' )
            ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => array('title', 'editor', 'thumbnail','comments','author'),
            'rewrite' => array( 'slug' => 'speakers' ),
            'has_archive' => true,
            'hierarchical' => true,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'menu_icon' => 'dashicons-universal-access-alt',
        ) );

        register_post_type( 'speakers_sessions', array(
            'labels' => array(
                'name' => _x( 'Sessions', 'post type general name', 'speakers' ),
                'singular_name' => _x( 'Sessions', 'post type singular name', 'speakers' ),
                'menu_name' => _x( 'Sessions', 'admin menu', 'speakers' ),
                'name_admin_bar' => _x( 'Sessions', 'add new on admin bar', 'speakers' ),
                'add_new' => _x( 'Add New', 'New Member', 'speakers' ),
                'add_new_item' => __( 'Add New Sessions', 'speakers' ),
                'new_item' => __( 'New Sessions', 'speakers' ),
                'edit_item' => __( 'Edit Sessions', 'speakers' ),
                'view_item' => __( 'View Sessions', 'speakers' ),
                'all_items' => __( 'All Sessions', 'speakers' ),
                'search_items' => __( 'Search Sessions', 'speakers' ),
                'not_found' => __( 'No Sessions found.', 'speakers' ),
                'not_found_in_trash' => __( 'No Sessions found in Trash.', 'speakers' )
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'supports' => array('title'),
            'rewrite' => false,
            'has_archive' => false,
            'hierarchical' => false,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'menu_icon' => 'dashicons-groups',
        ) );

        register_taxonomy( 'speakers_category', array( 'speakers_display' ), array(
            'labels' => array(
                'name' => _x( 'Categories', 'taxonomy general name', 'members' ),
                'singular_name' => _x( 'Category', 'taxonomy singular name', 'members' ),
                'search_items' =>  __( 'Categories', 'members' ),
                'all_items' => __( 'All Categories', 'members' ),
                'parent_item' => __( 'Parent Category', 'members' ),
                'parent_item_colon' => __( 'Parent Category:', 'members' ),
                'edit_item' => __( 'Edit Category', 'members' ),
                'update_item' => __( 'Update Category', 'members' ),
                'add_new_item' => __( 'Add New Category', 'members' ),
                'new_item_name' => __( 'New Category Name', 'members' ),
                'menu_name' => __( 'Categories', 'members' ),
            ),
            'public' => false,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => false,
        ) );

        register_taxonomy( 'speakers_position_country', array( 'speakers_display' ), array(
            'labels' => array(
                'name' => _x( 'Country', 'taxonomy general name', 'members' ),
                'singular_name' => _x( 'Country', 'taxonomy singular name', 'members' ),
                'search_items' =>  __( 'Countries', 'members' ),
                'all_items' => __( 'All Countries', 'members' ),
                'parent_item' => __( 'Parent Country', 'members' ),
                'parent_item_colon' => __( 'Parent Country:', 'members' ),
                'edit_item' => __( 'Edit Country', 'members' ),
                'update_item' => __( 'Update Country', 'members' ),
                'add_new_item' => __( 'Add New Country', 'members' ),
                'new_item_name' => __( 'New Country Name', 'members' ),
                'menu_name' => __( 'Countries', 'members' ),
            ),
            'public' => false,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => false,
        ) );
    }

    function wp_enqueue_scripts() {
        wp_enqueue_script( 'font-awesome', $this->plugin_url() . './assets/js/members-display.js' );

        wp_enqueue_style( 'font-awesome', $this->plugin_url() . './assets/css/font-awesome.css' );
        wp_enqueue_style( 'members-display', $this->plugin_url() . './assets/css/members-display.css' );
    }

    /**
     * @param $template_path
     * @return mixed|string
     */
    function include_template_function( $template_path ) {
        if ( get_post_type() == 'speakers_display' ) {
            if ( is_single() ) {
                if ( $theme_file = locate_template( array ( 'single-speakers_display.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/templates/single-speakers_display.php';
                }
            }

            if ( is_archive() ) {
                if ( $theme_file = locate_template( array ( 'archive-speakers_display.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/templates/archive-speakers_display.php';
                }
            }
        }
        return $template_path;
    }

    /**
     * @return string
     */
    function plugin_url() {
        return plugin_dir_url( __FILE__ );
    }
}

if ( ! function_exists( 'members_display' ) ) {
    function members_display() {
        return Members_Display::instance();
    }
}

members_display();
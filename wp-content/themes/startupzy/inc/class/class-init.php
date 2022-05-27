<?php
/**
 * Init Configuration
 *
 * @author Jegstudio
 * @package startupzy
 * @since 1.0.0
 */

namespace Startupzy;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Startupzy\Block_Patterns;
use Startupzy\Block_Styles;

/**
 * Init Class
 *
 * @package startupzy
 */
class Init {

	/**
	 * Instance variable
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Class instance.
	 *
	 * @return Init
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Class constructor.
	 */
	private function __construct() {
		$this->load_hooks();
	}

	/**
	 * Load initial hooks.
	 */
	private function load_hooks() {
		// actions.
		add_action( 'init', array( $this, 'add_theme_templates' ) );
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
		add_action( 'after_theme_setup', array( $this, 'content_width' ), 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_notices', array( $this, 'notice_install_plugin' ) );
		add_action( 'wp_ajax_startupzy_set_admin_notice_viewed', array( $this, 'notice_closed' ) );
		add_action( 'admin_init', array( $this, 'load_editor_styles' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'init', array( $this, 'register_block_patterns' ), 9 );
		add_action( 'init', array( $this, 'register_block_styles' ), 9 );

		// filters.
		add_filter( 'the_category', array( $this, 'render_categories' ) );
		add_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );
		add_filter( 'excerpt_more', array( $this, 'excerpt_elipsis' ) );
	}

	/**
	 * Register Block Pattern.
	 */
	public function register_block_patterns() {
		new Block_Patterns();
	}

	/**
	 * Register Block Style.
	 */
	public function register_block_styles() {
		new Block_Styles();
	}

	/**
	 * Excerpt Length.
	 *
	 * @return int
	 */
	public function excerpt_elipsis() {
		return '';
	}

	/**
	 * Excerpt Length.
	 *
	 * @return int
	 */
	public function excerpt_length() {
		return 100;
	}

	/**
	 * Render Categories.
	 *
	 * @param String $thelist String rendered.
	 *
	 * @return string
	 */
	public function render_categories( $thelist ) {
		return "<div>{$thelist}</div>";
	}

	/**
	 * Notice Closed
	 */
	public function notice_closed() {
		update_user_meta( get_current_user_id(), 'gutenverse_install_notice', 'true' );
		die;
	}

	/**
	 * Show notification to install Gutenverse Plugin.
	 */
	public function notice_install_plugin() {
		// Skip if gutenverse block activated.
		if ( defined( 'GUTENVERSE' ) ) {
			return;
		}

		// Skip if gutenverse pro activated.
		if ( defined( 'GUTENVERSE_PRO' ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return;
		}

		if ( 'true' === get_user_meta( get_current_user_id(), 'gutenverse_install_notice', true ) ) {
			return;
		}

		$plugin = 'gutenverse/gutenverse.php';

		$installed_plugins = get_plugins();

		$is_gutenverse_installed = isset( $installed_plugins[ $plugin ] );

		if ( $is_gutenverse_installed ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$button_text = __( 'Activate Gutenverse Plugin', 'startupzy' );
			$button_link = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$button_text = __( 'Install Gutenverse Plugin', 'startupzy' );
			$button_link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=gutenverse' ), 'install-plugin_gutenverse' );
		}
		?>
		<style>
			.install-gutenverse-plugin-notice {
				border: 1px solid #E6E6EF;
				border-radius: 5px;
				padding: 35px 40px;
				position: relative;
				overflow: hidden;
				background-image: url(<?php echo esc_url( STARTUPZY_URI . '/assets/images/mockup-2x.webp' ); ?>);
				background-position: right top;
				background-repeat: no-repeat;
				border-left: 4px solid #5e81f4;
			}

			.install-gutenverse-plugin-notice .notice-dismiss {
				top: 20px;
				right: 20px;
				padding: 0;
			}

			.install-gutenverse-plugin-notice .notice-dismiss:before {
				content: "\f335";
				font-size: 17px;
				width: 25px;
				height: 25px;
				line-height: 25px;
				border: 1px solid #E6E6EF;
				border-radius: 3px;
			}

			.install-gutenverse-plugin-notice h3 {
				margin-top: 5px;
				font-weight: 700;
				font-size: 18px;
			}

			.install-gutenverse-plugin-notice p {
				font-size: 14px;
				font-weight: 300;
			}

			.install-gutenverse-plugin-notice .gutenverse-bottom {
				display: flex;
				align-items: center;
				margin-top: 20px;
			}

			.install-gutenverse-plugin-notice a {
				text-decoration: none;
				margin-right: 20px;
			}

			.install-gutenverse-plugin-notice a.gutenverse-button {
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
				text-decoration: none;
				cursor: pointer;
				font-size: 12px;
				line-height: 18px;
				border-radius: 17px;
				background: #5e81f4;
				color: #fff;
				padding: 8px 30px;
				font-weight: 300;
			}
		</style>
		<script>
		jQuery( function( $ ) {
			$( 'div.notice.install-gutenverse-plugin-notice' ).on( 'click', 'button.notice-dismiss', function( event ) {
				event.preventDefault();

				$.post( ajaxurl, {
					action: 'startupzy_set_admin_notice_viewed'
				} );
			} );
		} );
		</script>
		<div class="notice is-dismissible install-gutenverse-plugin-notice">
			<div class="gutenverse-notice-inner">
				<div class="gutenverse-notice-content">
					<h3><?php esc_html_e( 'Thank you for installing Startupzy!', 'startupzy' ); ?></h3>
					<p><?php esc_html_e( 'Startupzy theme work best with Gutenverse plugin. By installing Gutenverse plugin you may access Startupzy templates built with Gutenverse and get access to more than 40 free blocks.', 'startupzy' ); ?></p>
					<div class="gutenverse-bottom">
						<a class="gutenverse-button" href="<?php echo esc_url( $button_link ); ?>">
							<?php echo esc_html( $button_text ); ?>
						</a>
						<a target="__blank" href="https://gutenverse.com/">
							<?php esc_html_e( 'More Info', 'startupzy' ); ?>
							<span class="dashicons dashicons-arrow-right-alt"></span>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add Menu
	 */
	public function admin_menu() {
		add_theme_page(
			'Startupzy Template',
			'Startupzy Template',
			'read',
			'startupzy-dashboard',
			array( $this, 'load_startupzy_dashboard' ),
			1
		);
	}

	/**
	 * Startupzy Template page
	 */
	public function load_startupzy_dashboard() {
		$gutenverse_active = false;

		if ( defined( 'GUTENVERSE' ) ) {
			$gutenverse_active = true;
		}

		$plugin = 'gutenverse/gutenverse.php';

		$installed_plugins = get_plugins();

		$is_gutenverse_installed = isset( $installed_plugins[ $plugin ] );

		if ( $is_gutenverse_installed && $gutenverse_active ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$button_text = __( 'Install Gutenverse Version', 'startupzy' );
			$button_link = wp_nonce_url( self_admin_url( 'themes.php?page=startupzy-dashboard&install-template=gutenverse' ), 'install-template_gutenverse' );
		} elseif ( $is_gutenverse_installed ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			$button_text = __( 'Activate Gutenverse Plugin', 'startupzy' );
			$button_link = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$button_text = __( 'Install Gutenverse Plugin', 'startupzy' );
			$button_link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=gutenverse' ), 'install-plugin_gutenverse' );
		}

		?>
			<style>

				.notice.hide {
					display: none
				}
				.notice, .install-template {
					background: #FFFFFF;
					border-radius: 5px;
					padding: 30px!important;
					margin: 40px 20px 0 0;
					display: flex;
				}
				.notice{
					padding: 10px;
					border-left: 4px solid #5e81f4;
				}
				.install-template img {
					box-shadow: 0px 1px 20px 2px rgba(230, 230, 239, 0.6);
					border-radius: 5px;
				}
				.install-template .content {
					padding: 30px 30px 30px 60px;
					display: flex;
					flex-wrap: wrap;
					align-content: space-between;
				}
				.install-template h3 {
					margin-top: 5px;
					font-weight: 700;
					font-size: 18px;
				}

				.install-template span {
					font-size: 14px;
					font-weight: 400;
					line-height: 1.9em;
					padding-right: 15%;
				}

				.install-template .gutenverse-bottom {
					display: flex;
					align-items: center;
					margin-top: 20px;
				}

				.install-template a {
					text-decoration: none;
					margin-right: 20px;
				}

				.install-template a.gutenverse-button {
					font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
					text-decoration: none;
					cursor: pointer;
					font-size: 12px;
					line-height: 18px;
					border-radius: 50px;
					background: #5e81f4;
					color: #fff;
					padding: 17px 25px;
					font-weight: 300;
				}

				.install-template a.gutenverse-button:hover {
					background: #0058e6;
				}
			</style>
			<?php if ( defined( 'GUTENVERSE_VERSION' ) && version_compare( GUTENVERSE_VERSION, '1.1.1', '<=' ) ) { ?>
			<div class="notice is-dismissible">
				<span>
				<?php echo esc_html_e( 'Please install newer version of Gutenverse plugin! (v1.1.2 and above)', 'startupzy' ); ?>
				</span>
			</div>
			<?php } ?>
			<?php do_action( 'gutenverse_after_install_notice' ); ?>
			<div class="install-template">
				<div class="thumbnail">
					<img src="<?php echo esc_html( STARTUPZY_URI . '/screenshot.jpg' ); ?>" alt="Startupzy" width="400" height="300"/>
				</div>
				<div class="content">
					<h1><?php echo esc_html_e( 'Startupzy Template (Gutenverse version)', 'startupzy' ); ?></h1>
					<span><?php echo esc_html_e( 'To get the best experience using Startupzy theme, you need to install and activate Gutenverse Plugin. With Gutenverse plugin installed, you gain access to Startupzy advance template and block patterns for free which all built using Gutenverse blocks. Also please backup your current templates if you have any changes to it, installing new template might overwrite or conflict with the current changes.', 'startupzy' ); ?></span>
					<div>
						<div class="gutenverse-bottom">
							<?php if ( ! $gutenverse_active || ( defined( 'GUTENVERSE_VERSION' ) && version_compare( GUTENVERSE_VERSION, '1.1.1', '>' ) ) ) { ?>
							<a class="gutenverse-button" href="<?php echo esc_url( $button_link ); ?>">
								<?php echo esc_html( $button_text ); ?>
							</a>
							<?php } ?>
							<a class="gutenverse-link" href="<?php echo esc_url( 'https:/gutenverse.com/demo?name=startupzy' ); ?>" target='_blank'>
								<?php echo esc_html_e( 'View Live Demo →', 'startupzy' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Add theme template
	 */
	public function add_theme_templates() {
		add_editor_style( 'block-style.css' );
	}

	/**
	 * Theme setup.
	 */
	public function theme_setup() {
		load_theme_textdomain( 'startupzy', STARTUPZY_DIR . '/languages' );

		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'editor-styles' );

		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary', 'startupzy' ),
			)
		);

		add_editor_style(
			array(
				'./assets/css/core-add.css',
			)
		);

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		add_theme_support( 'customize-selective-refresh-widgets' );
	}

	/**
	 * Set the content width.
	 */
	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'gutenverse_content_width', 960 );
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'startupzy-style', get_stylesheet_uri(), array(), STARTUPZY_VERSION );
		wp_add_inline_style( 'startupzy-style', $this->load_font_styles() );

		// enqueue additional core css.
		wp_enqueue_style( 'startupzy-core-add', STARTUPZY_URI . '/assets/css/core-add.css', array(), STARTUPZY_VERSION );

		// enqueue core animation.
		wp_enqueue_script( 'startupzy-animate', STARTUPZY_URI . '/assets/js/index.js', array(), STARTUPZY_VERSION, true );
		wp_enqueue_style( 'startupzy-animate', STARTUPZY_URI . '/assets/css/animation.css', array(), STARTUPZY_VERSION );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Load Font Styles
	 */
	public function load_font_styles() {
		return "
			@import url('https://fonts.googleapis.com/css2?family=Helvetica:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;1,100;1,200;1,300;1,400;1,500;1,600&display=swap');		
			@import url('https://fonts.googleapis.com/css2?family=Heebo:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;1,100;1,200;1,300;1,400;1,500;1,600&display=swap');
		";
	}

	/**
	 * Load Editor Styles
	 */
	public function load_editor_styles() {
		wp_add_inline_style( 'wp-block-library', $this->load_font_styles() );
	}
}

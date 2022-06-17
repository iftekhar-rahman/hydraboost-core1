<?php
namespace Hydraboost_Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Hydraboost_Services
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Hydraboost_Services extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'hydraboost-services';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Services', 'hydraboost-core' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-code';
	}

	

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'basic', 'hydraboost-category' ];
	}

	// Load CSS
	public function get_style_depends() {

		wp_register_style( 'owl-carousel-css', plugins_url( '../assets/css/owl.carousel.min.css', __FILE__ ));

		return [
			'owl-carousel-css',
		];

	}

	// Load JS
	public function get_script_depends() {

		wp_register_script( 'owl-carousel-js', plugins_url( '../assets/js/owl.carousel.min.js', __FILE__ ), [ 'jquery' ] );

		return [
			'owl-carousel-js',
		];

	}


	/**
	 * Register oEmbed widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'hydraboost-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'service_image',
			[
				'label' => esc_html__( 'Choose Image', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'hydraboost-core' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_subtitle', [
				'label' => esc_html__( 'Sub Title', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Sub Title' , 'hydraboost-core' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_content', [
				'label' => esc_html__( 'Content', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'List Content' , 'hydraboost-core' ),
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'button_text', [
				'label' => esc_html__( 'Button Text', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_link', [
				'label' => esc_html__( 'Button URL', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::URL,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_color',
			[
				'label' => esc_html__( 'Color', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'services_list',
			[
				'label' => esc_html__( 'Services List', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Title #1', 'hydraboost-core' ),
					],
					[
						'list_title' => esc_html__( 'Title #2', 'hydraboost-core' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();

		// COLOR CSS
        $this->start_controls_section(
			'content_section_style',
			[
				'label' => __( 'Content', 'hydraboost-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Title CSS
		$this->add_control(
			'list_title_color',
			[
				'label' => __( 'Title Color', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				// 'scheme' => [
				// 	'type' => \Elementor\Scheme_Color::get_type(),
				// 	'value' => \Elementor\Scheme_Color::COLOR_1,
				// ],
				'selectors' => [
					'{{WRAPPER}} .single-item h2' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .single-item h2',
			]
		);

		$this->add_control(
			'margin',
			[
				'label' => esc_html__( 'Title Margin', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .single-item h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Sub Title CSS
		// $this->add_control(
		// 	'list_subtitle_color',
		// 	[
		// 		'label' => __( 'Sub Title Color', 'hydraboost-core' ),
		// 		'type' => \Elementor\Controls_Manager::COLOR,
		// 		'scheme' => [
		// 			'type' => \Elementor\Scheme_Color::get_type(),
		// 			'value' => \Elementor\Scheme_Color::COLOR_1,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .single-item h4' => 'color: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography1',
				'selector' => '{{WRAPPER}} .single-item h4',
			]
		);

		$this->add_control(
			'margin',
			[
				'label' => esc_html__( 'Sub Title Margin', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .single-item h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Default CSS
		$this->add_control(
			'alignment',
			[
				'label' => __( 'Text Alignment', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'left'  => __( 'Left', 'hydraboost-core' ),
					'right' => __( 'Right', 'hydraboost-core' ),
					'center' => __( 'Center', 'hydraboost-core' ),
					'justify' => __( 'Justify', 'hydraboost-core' ),
					'none' => __( 'None', 'hydraboost-core' ),
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .single-item h2, .single-item h4' => 'text-align:{{VALUE}};'
				]
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'slider_setting_section',
			[
				'label' => esc_html__( 'Slider Settings', 'hydraboost-core' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'True', 'hydraboost-core' ),
				'label_off' => esc_html__( 'False', 'hydraboost-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'nav',
			[
				'label' => esc_html__( 'Nav', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'True', 'hydraboost-core' ),
				'label_off' => esc_html__( 'False', 'hydraboost-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'True', 'hydraboost-core' ),
				'label_off' => esc_html__( 'False', 'hydraboost-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'dots',
			[
				'label' => esc_html__( 'Dots', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'True', 'hydraboost-core' ),
				'label_off' => esc_html__( 'False', 'hydraboost-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'item_desktop',
			[
				'label' => esc_html__( 'Item Desktop', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '3',
			]
		);

		$this->add_control(
			'item_tab',
			[
				'label' => esc_html__( 'Item Tab', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '2',
			]
		);

		$this->add_control(
			'item_mobile',
			[
				'label' => esc_html__( 'Item Mobile', 'hydraboost-core' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '1',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		if( 'yes' === $settings['loop'] ){
			$loop = 'true';
		} else{
			$loop = 'false';
		}

		if( 'yes' === $settings['nav'] ){
			$nav = 'true';
		} else{
			$nav = 'false';
		}

		if( 'yes' === $settings[ 'autoplay' ]){
			$autoplay = 'true';
		} else{
			$autoplay = 'false';
		}

		if( 'yes' === $settings[ 'dots' ]){
			$dots = 'true';
		} else{
			$dots = 'false';
		}


		$item_desktop = $settings['item_desktop'];
		$item_tab = $settings['item_tab'];
		$item_mobile = $settings['item_mobile'];

	?>
	<div class="services-slider-area">
		<?php
			$dynamic_id = rand(21323,453465);
			
				echo '
				<script>
					jQuery(document).ready(function($) {
						$("#service-'.$dynamic_id.'").owlCarousel({
							loop: '.$loop.',
							lazyLoad: true,
							nav: '.$nav.',
							dots: '.$dots.',
							autoplay: '.$autoplay.',
							margin: 30,
							navText: [
							 	"<i class=\'fa fa-angle-left\'></i>",
							 	"<i class=\'fa fa-angle-right\'></i>",
							],
							responsive: {
								0: {
									items: '.$item_mobile.',
									margin: 0,
									nav: false,
								},
								600: {
									items: '.$item_tab.',
									margin: 20,
									nav: false,
								},
								1000: {
									items: '.$item_desktop.',
								}
							},
						});

					});
				</script>
				';
			
		?>
		<div class="owl-carousel services-slider" id="service-<?php echo $dynamic_id; ?>">
			<?php foreach ($settings['services_list'] as $service_list) : ?>
			<div class="single-item">
				<div class="service-bg" style="background-image:url(<?php echo $service_list['service_image']['url']; ?>)"></div>
				<h2><?php echo esc_html($service_list['list_title']); ?></h2>
				<h4><?php echo $service_list['list_subtitle']; ?></h4>
				
				<a href="<?php echo $service_list['button_link']['url']; ?>" class="boxed-btn">
					<?php echo $service_list['button_text']; ?>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	}
}
<?php
class LPBCustomPost{
	public $post_type = 'bsb';

	public function __construct(){
		global $bsb_bs;
		if($bsb_bs->can_use_premium_feature()){
			add_action( 'init', [$this, 'onInit'], 20 );
			add_shortcode( 'bsb-slider', [$this, 'onAddShortcode'], 20 );
			add_filter( 'manage_bsb_posts_columns', [$this, 'manageLPBPostsColumns'], 10 );
			add_action( 'manage_bsb_posts_custom_column', [$this, 'manageBSBPostsCustomColumns'], 10, 2 );
			add_action( 'use_block_editor_for_post', [$this, 'useBlockEditorForPost'], 999, 2 );
		}
	}

	function onInit(){
		$menuIcon = "<svg width='24px' height='24px' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M8.5 9.5L6 12L8.5 14.5' stroke='#fff' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/><path d='M15.5 9.5L18 12L15.5 14.5' stroke='#fff' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/><path d='M2 15V9C2 6.79086 3.79086 5 6 5H18C20.2091 5 22 6.79086 22 9V15C22 17.2091 20.2091 19 18 19H6C3.79086 19 2 17.2091 2 15Z' stroke='#fff' stroke-width='1.5'/></svg>";

		register_post_type( $this->post_type, [
			'labels'				=> [
				'name'			=> __( 'B Slider', 'slider'),
				'singular_name'	=> __( 'B Slider', 'slider' ),
				'add_new'		=> __( 'Add New', 'slider' ),
				'add_new_item'	=> __( 'Add New', 'slider' ),
				'edit_item'		=> __( 'Edit', 'slider' ),
				'new_item'		=> __( 'New', 'slider' ),
				'view_item'		=> __( 'View', 'slider' ),
				'search_items'	=> __( 'Search', 'slider'),
				'not_found'		=> __( 'Sorry, we couldn\'t find the that you are looking for.', 'slider' )
			],
			'public'				=> false,
			'show_ui'				=> true, 		
			'show_in_rest'			=> true,							
			'publicly_queryable'	=> false,
			'exclude_from_search'	=> true,
			'menu_position'			=> 14,
			'menu_icon'				=> 'data:image/svg+xml;base64,' . base64_encode( $menuIcon ),		
			'has_archive'			=> false,
			'hierarchical'			=> false,
			'capability_type'		=> 'page',
			'rewrite'				=> [ 'slug' => 'bsb' ],
			'supports'				=> [ 'title', 'editor' ],
			'template'				=> [ ['bsb/slider'] ],
			'template_lock'			=> 'all',
		]); // Register Post Type
	}

	function onAddShortcode( $atts ) {
		$post_id = $atts['id'];
		$post = get_post( $post_id );

		$blocks = parse_blocks( $post->post_content );

		return render_block( $blocks[0] );
	}

	function manageLPBPostsColumns( $defaults ) {
		unset( $defaults['date'] );
		$defaults['shortcode'] = 'ShortCode';
		$defaults['date'] = 'Date';
		return $defaults;
	}

	function manageBSBPostsCustomColumns( $column_name, $post_ID ) {
		if ( $column_name == 'shortcode' ) {
			echo "<div class='bsbFrontShortcode' id='bsbFrontShortcode-$post_ID'>
				<input value='[bsb-slider id=$post_ID]' onclick='bsbHandleShortcode( $post_ID )'>
				<span class='tooltip'>Copy To Clipboard</span>
			</div>";
		}
	}

	function useBlockEditorForPost($use, $post){
		if ($this->post_type === $post->post_type) {
			return true;
		}
		return $use;
	}
}
new LPBCustomPost();
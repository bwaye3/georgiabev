<?php 
if(!class_exists('element_gva_listing_banner')):
	class element_gva_listing_banner{
		public function render_form(){
			return array(
			  'type'          => 'gsc_listing_banner',
				'title'        => t('Listing Banner'),
				'fields' => array(
					array(
						'id'        => 'sub_title',
						'type'      => 'text',
						'title'     => t('Sub Title'),
						'default'	=> 'Outstanding',
						'class'		=> 'width-1-3',
					),
					array(
						'id'        => 'title',
						'type'      => 'text',
						'title'     => t('Title'),
						'class'		=> 'width-1-3',
						'default'	=> 'Places in London',
						'admin'     => true
					),
					array(
						'id'        => 'highlight_text',
						'type'      => 'text',
						'title'     => t('Highlight Text'),
						'class'		=> 'width-1-3',
						'default'	=> '60+ Listings',
					),
					array(
						'id'        => 'image',
						'type'      => 'upload',
						'title'     => t('Image')
					),
					array(
						'id'        => 'link',
						'type'      => 'text',
						'class'		=> 'width-1-2',
						'title'     => t('Link'),
					),
					array(
						'id'        => 'target',
						'type'      => 'select',
						'title'     => t('Open in new window'),
						'desc'      => t('Adds a target="_blank" attribute to the link'),
						'options'   => array( 'off' => 'No', 'on' => 'Yes' ),
						'class'		=> 'width-1-2',
						'default'   => 'on'
					),
					array(
						'id'        => 'align',
						'type'      => 'select',
						'title'     => t('Align'),
						'class'		=> 'width-1-4',
						'options'   => array( 
							'text-center'     => t('Center'), 
							'text-left'       => t('Left'),
							'text-right'      => t('Right')
						),
					),
					array(
						'id'        => 'el_class',
						'type'      => 'text',
						'title'     => t('Extra class name'),
						'class'		=> 'width-1-4',
						'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
					),
					array(
						'id'        => 'animate',
						'type'      => 'select',
						'title'     => t('Animation'),
						'desc'      => t('Entrance animation for element'),
						'options'   => gavias_content_builder_animate(),
						'class'     => 'width-1-4'
					), 
					array(
						'id'        => 'animate_delay',
						'type'      => 'select',
						'title'     => t('Animation Delay'),
						'options'   => gavias_content_builder_delay_aos(),
						'desc'      => '0 = default',
						'class'     => 'width-1-4'
					), 
			
				),                                     
			);
		}

		public static function render_content( $attr = array(), $content = '' ){
			global $base_url;
			extract(gavias_merge_atts(array(
				'title'              => '',
				'sub_title'          => '',
				'highlight_text'		=> '',
				'image'              => '',
				'link'               => '',
				'target'             => '',
				'align'              => 'center',
				'el_class'           => '',
				'animate'            => '',
				'animate_delay'      => ''
			), $attr));

			// target
			if( $target =='on' ){
				$target = 'target="_blank"';
			} else {
				$target = false;
			}
			
			if($image) $image = $base_url . $image; 

			if($align) $el_class .= ' ' . $align;
			if($animate) $el_class .= ' wow ' . $animate;
			$title_html = $link ? "<a {$target} href=\"{$link}\">{$title}</a>" : $title;
			ob_start();
			?>

			<div class="gsc-listings-banner <?php print $el_class; ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
				<div class="listings-banner-content">
					<?php 
						if($highlight_text){
							print '<span class="number-listings">' . $highlight_text . '</span>';
						}
					?>
					<?php if(!empty($image)){ ?>
						<div class="banner-image">
							<img src="<?php print $image ?>" alt="<?php print strip_tags($title) ?>" />
						</div>
					<?php } ?>   

					<div class="banner-content">
						<?php 
							if($sub_title){
								print '<div class="subtitle">' . $sub_title . '</div>';
							}
							if($title){ 
								print '<h3 class="title">' . $title . '</h3>';
							} 
						?>
					</div> 

					<?php if($link){ ?>
						<a class="link-overlay" <?php print $target ?> href="<?php print $link ?>"></a>
					<?php } ?>
				</div>	
			</div>

			<?php return ob_get_clean() ?>
		  <?php            
		}
	}
endif;   

<?php 

if(!class_exists('element_gva_image_content')):
	class element_gva_image_content{
		public function render_form(){
			return array(
			  'type'          => 'gsc_image_content',
				'title'        => t('Image content'),
				'fields' => array(
					array(
						'id'        => 'title',
						'type'      => 'text',
						'title'     => t('Title'),
						'class'		=> 'width-1-2',
						'admin'     => true
					),
					array(
						'id'        => 'image',
						'type'      => 'upload',
						'class'		=> 'width-1-2',
						'title'     => t('Image')
					),
					array(
						'id'        => 'content',
						'type'      => 'textarea',
						'title'     => t('Content'),
						'desc'      => t('Some HTML tags allowed'),
					),
					array(
						'id'        => 'link',
						'type'      => 'text',
						'class'		=> 'width-1-3',
						'title'     => t('Link'),
					),

					array(
						'id'        => 'text_link',
						'type'      => 'text',
						'class'		=> 'width-1-3',
						'title'     => t('Text Link (Optional)'),
					),

					array(
						'id'        => 'target',
						'type'      => 'select',
						'title'     => t('Open in new window'),
						'desc'      => t('Adds a target="_blank" attribute to the link'),
						'options'   => array( 'off' => 'No', 'on' => 'Yes' ),
						'class'		=> 'width-1-3',
						'default'   => 'on'
					),

					array(
						'id'        => 'skin',
						'type'      => 'select',
						'title'     => t('Skin'),
						'class'		=> 'width-1-2',
						'options'   => array( 
							'skin-v1'               => t('Skin I'), 
							'skin-v2'               => t('Skin II'),
						),
					),

					array(
						'id'        => 'el_class',
						'type'      => 'text',
						'title'     => t('Extra class name'),
						'class'		=> 'width-1-2',
						'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
					),
					array(
						'id'        => 'animate',
						'type'      => 'select',
						'title'     => t('Animation'),
						'desc'      => t('Entrance animation for element'),
						'options'   => gavias_content_builder_animate(),
						'class'     => 'width-1-2'
					), 
					array(
						'id'        => 'animate_delay',
						'type'      => 'select',
						'title'     => t('Animation Delay'),
						'options'   => gavias_content_builder_delay_aos(),
						'desc'      => '0 = default',
						'class'     => 'width-1-2'
					), 
			
				),                                     
			);
		}

		public static function render_content( $attr = array(), $content = '' ){
			global $base_url;
			extract(gavias_merge_atts(array(
				'title'              => '',
				'content'            => '',
				'image'              => '',
				'link'               => '',
				'text_link'          => 'Read more',
				'target'             => '',
				'skin'               => 'skin-v1',
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

			if($skin) $el_class .= ' ' . $skin;
			if($animate) $el_class .= ' wow ' . $animate;
			$title_html = $link ? "<a {$target} href=\"{$link}\">{$title}</a>" : $title;
			ob_start();
			?>

			<?php if($skin == 'skin-v1'){ ?>
				<div class="gsc-image-content <?php print $el_class; ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
					<?php if(!empty($image)){ ?>
						<div class="image">
							<img src="<?php print $image ?>" alt="<?php print strip_tags($title) ?>" />
							<?php if($link){ ?>
								<a class="link-overlay" <?php print $target ?> href="<?php print $link ?>"></a>
							<?php } ?>
						</div>
					<?php } ?>   

					<div class="box-content">
						<div class="content-inner">
							<?php if($title){ ?>
								<h4 class="title"><?php print $title ?></h4>
							<?php } ?>
							<div class="desc"><?php print $content; ?></div>
						</div>   
					</div>  
				</div>
			<?php } ?>   

			<?php if($skin == 'skin-v2'){ ?>
				<div class="gsc-image-content <?php print $el_class; ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
					
					<div class="image">
						<img src="<?php print $image ?>" alt="<?php print strip_tags($title) ?>" />
						<?php if($link){ ?>
							<a class="link-overlay" <?php print $target ?> href="<?php print $link ?>"></a>
						<?php } ?>
					</div>
					
					<div class="box-content">
						<div class="content-inner">
							<?php if($title_html){ ?>
								<h4 class="title"><?php print $title_html ?></h4>
							<?php } ?>
							<div class="desc"><?php print $content; ?></div>
						</div>	
					</div>
				</div>
			<?php } ?> 

			<?php return ob_get_clean() ?>
		  <?php            
		}
	}
endif;   

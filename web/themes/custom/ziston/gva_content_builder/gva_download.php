<?php 

if(!class_exists('element_gva_download')):
	class element_gva_download{

		public function render_form(){
			$fields = array(
				'type' => 'gsc_download',
				'title' => t('Download box'),
				'size' => 3,
				'fields' => array(
					array(
						'id'        => 'title',
						'type'      => 'text',
						'title'     => t('Title'),
						'admin'     => true
					),
					array(
						'id'     => "info_1",
						'type'   => 'info',
						'desc'   => "Information for Button I"
					),
					array(
						'id'        => 'btn_title_1',
						'type'      => 'text',
						'title'     => t('Button Title I'),
						'default'	=> t('Get In Google Play'),
						'class'     => 'width-1-3'
					),
					array(
						'id'        => 'btn_icon_1',
						'type'      => 'text',
						'title'     => t('Button Icon I'),
						'class'     => 'width-1-3'
					),
					array(
						'id'        => 'btn_link_1',
						'type'      => 'text',
						'title'     => t('Button Link I'),
						'class'     => 'width-1-3'
					),
					array(
						'id'     => "info_1",
						'type'   => 'info',
						'desc'   => "Information for Button II"
					),
					array(
						'id'        => 'btn_title_2',
						'type'      => 'text',
						'title'     => t('Button Title II'),
						'default'	=> t('Get In Store Play'),
						'class'     => 'width-1-3'
					),
					array(
						'id'        => 'btn_icon_2',
						'type'      => 'text',
						'title'     => t('Button Icon II'),
						'class'     => 'width-1-3'
					),
					array(
						'id'        => 'btn_link_2',
						'type'      => 'text',
						'title'     => t('Button Link II'),
						'class'     => 'width-1-3'
					),

				  	array(
						'id'        => 'animate',
						'type'      => 'select',
						'title'     => t('Animation'),
						'desc'      => t('Entrance animation for element'),
						'options'   => gavias_content_builder_animate(),
						'class'     => 'width-1-3'
					), 
					array(
						'id'        => 'animate_delay',
						'type'      => 'select',
						'title'     => t('Animation Delay'),
						'options'   => gavias_content_builder_delay_aos(),
						'desc'      => '0 = default',
						'class'     => 'width-1-3'
					), 
					array(
						'id'        => 'el_class',
						'type'      => 'text',
						'title'     => t('Extra class name'),
						'desc'      => t('Add a class name and refer to it in custom CSS.'),
						'class'     => 'width-1-3'
					)
				)                                    
			);

			return $fields;
		}


		public static function render_content( $attr = array(), $content = '' ){
			global $base_url;
			$default = array(
				'title'           => '',
				'btn_title_1'     => 'Get In Google Play',
				'btn_icon_1'      => '',
				'btn_link_1'      => '',
				'btn_title_2'     => 'Get In Store Play',
				'btn_icon_2'      => '',
				'btn_link_2'      => '',
				'el_class'        => '',
				'animate'         => '',
				'animate_delay'   => ''
			);

			extract(gavias_merge_atts($default, $attr));

			$_id = gavias_content_builder_makeid();
			if($animate) $el_class .= ' wow ' . $animate; 
			ob_start();
			?>
			<div class="gsc-box-download <?php echo $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>> 
				<div class="box-content">
					<?php 
						if(!empty($btn_link_1)){ 
							echo '<a class="link-item link-1" href="' .$btn_link_1. '">';
								echo '<span class="button-content-wrapper">';
									echo (!empty($btn_icon_1) ? '<span class="button-icon"><i class="icon ' .$btn_icon_1. '"></i></span>' : '');
									echo '<span class="button-title">' . $btn_title_1 . '</span>';
								echo '</span>';
							echo '</a>';
						}
						if(!empty($btn_link_2)){ 
							echo '<a class="link-item link-2" href="' .$btn_link_2. '">';
								echo '<span class="button-content-wrapper">';
									echo (!empty($btn_icon_2) ? '<span class="button-icon"><i class="icon ' .$btn_icon_2. '"></i></span>' : '');
									echo '<span class="button-title">' . $btn_title_2 . '</span>';
								echo '</span>';
							echo '</a>';
						}
					?>
				</div> 
		  	</div>
			<?php return ob_get_clean();
		}
	}
 endif;  




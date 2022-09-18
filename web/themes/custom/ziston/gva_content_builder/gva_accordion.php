<?php 
if(!class_exists('element_gva_accordion')):
	class element_gva_accordion{
		public function render_form(){
			$fields = array(
				'type'      => 'element_gva_accordion',
				'title'  => t('Accordion'), 
				'fields' => array(
					array(
						'id'     => 'title',
						'type'      => 'text',
						'title'  => t('Title'),
						'admin'     => true
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
						'options'   => gavias_content_builder_delay_wow(),
						'desc'      => '0 = default',
						'class'     => 'width-1-2'
					), 
					array(
						'id'     => 'el_class',
						'type'      => 'text',
						'title'  => t('Extra class name'),
						'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
					),
				),                                           
			);
			for($i=1; $i<=10; $i++){
				$fields['fields'][] = array(
					'id'     => "info_${i}",
					'type'   => 'info',
					'desc'   => "Information for item {$i}"
				);
				$fields['fields'][] = array(
					'id'        => "title_{$i}",
					'type'      => 'text',
					'title'     => t("Title {$i}")
				);
				$fields['fields'][] = array(
					'id'           => "content_{$i}",
					'type'         => 'textarea',
					'title'        => t("Content {$i}")
				);
			}
		return $fields;
		}

		public static function render_content( $attr = array(), $content = '' ){
			$default = array(
				'title'           => '',
				'style'           => '',
				'animate'         => '',
				'animate_delay'   => '',
				'el_class'        => ''
			);
			for($i=1; $i<=10; $i++){
				$default["title_{$i}"] = '';
				$default["content_{$i}"] = '';
			}
			extract(gavias_merge_atts($default, $attr));
			
			$_id = 'accordion-' . gavias_content_builder_makeid();
			
			if($animate) $el_class .= ' wow ' . $animate; 
			ob_start();
			?>
	 
			<div class="gsc-accordion<?php print $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
				<div class="accordion" id="<?php print $_id; ?>">
				  <?php for($i=1; $i<=10; $i++){ ?>
						<?php 
							$title = "title_{$i}";
							$content = "content_{$i}";
						?>
						<?php if($$title){ ?>
							<div class="accordion-item">
								<div class="accordion-header">
								  	<button class="accordion-button <?php print ($i == 1) ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse"  data-bs-target="#<?php print ($_id . '-' . $i) ?>">
									 	<?php print $$title ?>
								  	</button>
								</div>
								<div id="<?php print ($_id . '-' . $i) ?>" class="accordion-collapse collapse <?php print ($i == 1) ? 'show' : '' ?>" data-bs-parent="#<?php print $_id; ?>">
									<div class="accordion-body clearfix">
										<?php print $$content ?>
									</div>
								</div>
							</div>
						<?php } ?>   
					<?php } ?>  
				</div>
			</div>   
			<?php  return ob_get_clean() ?>
		<?php    
		}
	}

endif;
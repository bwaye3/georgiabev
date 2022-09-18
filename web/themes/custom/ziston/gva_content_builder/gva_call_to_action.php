<?php 

if(!class_exists('element_gva_call_to_action')):
   class element_gva_call_to_action{
      public function render_form(){
         $fields = array(
            'type' => 'gsc_call_to_action',
            'title' => t('Call to Action'),
            'fields' => array(
               array(
                  'id'        => 'sub_title',
                  'type'      => 'text',
                  'title'     => t('Sub Title'),
                  'class'     => 'width-1-2'
               ),
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title'),
                  'class'     => 'width-1-2',
                  'admin'     => true
               ),
               array(
                  'id'        => 'content',
                  'type'      => 'textarea',
                  'title'     => t('Content'),
                  'desc'      => t('HTML tags allowed.'),
               ),
               array(
                  'id'           => 'button_align',
                  'type'         => 'select',
                  'title'        => 'Style',
                  'options'      => array(
                     'button-left'              => t('Button Left'),
                     'button-right'             => t('Button Right'),
                     'button-bottom'            => t('Button Bottom Left'),
                     'button-bottom-right'      => t('Button Bottom Right'),
                     'button-center'            => t('Button Bottom Center'),
                  ),
                  'class'     => 'width-1-3'
               ),
               array(
                  'id'        => 'font_size',
                  'type'      => 'select',
                  'title'     => t('Font Size'),
                  'options'   => array(
                     '00'   => '--Default--',
                     '18'   => '18',
                     '20'   => '20',
                     '22'   => '22',
                     '24'   => '24',
                     '26'   => '26',
                     '28'   => '28',
                     '30'   => '30',
                     '32'   => '32',
                     '34'   => '34',
                     '36'   => '36',
                     '38'   => '38',
                     '40'   => '40',
                     '42'   => '42',
                     '44'   => '44',
                     '46'   => '46',
                     '48'   => '48',
                     '50'   => '50',
                     '60'   => '60',
                     '70'   => '70',
                     '80'   => '80',
                     '90'   => '90',
                     '100'   => '100',
                  ),
                  'class'     => 'width-1-3',
                  'default'   => '00'
               ),
               array(
                  'id'        => 'font_weight',
                  'type'      => 'select',
                  'title'     => t('Font Weight'),
                  'options'   => array(
                     'fw-400'   => '400',
                     'fw-500'   => '500',
                     'fw-600'   => '600',
                     'fw-700'   => '700',
                     'fw-800'   => '800',
                     'fw-900'   => '900'
                  ),
                  'class'     => 'width-1-3',
                  'default' => 'fw-700'
               ),
               array(
                  'id'        => 'width',
                  'type'      => 'text',
                  'title'     => t('Max width for content'),
                  'desc'      => 'e.g 660px',
                  'class'     => 'width-1-3'
               ),
               array(
                  'id'        => 'style_text',
                  'type'      => 'select',
                  'title'     => 'Skin Text for box',
                  'options'   => array(
                     'text-dark'       => 'Text dark',
                     'text-light'      => 'Text light',
                     'text-light-2'    => 'Text light II',
                  ),
                  'class'     => 'width-1-3',
                  'default'       => 'text-dark'
               ),
                array(
                  'id'        => 'target',
                  'type'      => 'select',
                  'title'     => t('Open in new window'),
                  'desc'      => t('Adds a target="_blank" attribute to the link'),
                  'options'   => array( 
                     'off' => 'Off',
                     'on' => 'On'
                  ),
                  'class'     => 'width-1-3',
               ),
               array(
                 'id'        => 'info',
                 'type'      => 'info',
                 'title'      => 'Settings Button #1'
               ),
               array(
                  'id'        => 'link',
                  'type'      => 'text',
                  'title'     => t('Link 1'),
                  'class'     => 'width-1-3'
               ),
               array(
                  'id'        => 'button_title',
                  'type'      => 'text',
                  'title'     => t('Button Title 1'),
                  'class'     => 'width-1-3',
                  'desc'      => t('Leave this field blank if you want Call to Action with Big Icon'),
               ),
               array(
                  'id'        => 'style_button',
                  'type'      => 'select',
                  'title'     => 'Style button #1',
                  'options'   => array(
                     'btn-theme'    => 'Button Theme',
                     'btn-black'    => 'Button Black',
                     'btn-white'     => 'Button White'
                  ),
                  'class'     => 'width-1-3',
                  'default'    => 'text-dark'
               ),
               array(
                 'id'         => 'info',
                 'type'       => 'info',
                 'title'      => 'Settings Button #2'
               ),
               array(
                  'id'        => 'link_2',
                  'type'      => 'text',
                  'title'     => t('Link 2'),
                  'class'     => 'width-1-3'
               ),
               array(
                  'id'        => 'button_title_2',
                  'type'      => 'text',
                  'title'     => t('Button Title 2'),
                  'class'     => 'width-1-3',
                  'desc'      => t('Leave this field blank if you want Call to Action with Big Icon')
               ),
               array(
                  'id'        => 'style_button_2',
                  'type'      => 'select',
                  'title'     => 'Style button 2',
                  'options'   => array(
                     'btn-theme'    => 'Button Theme',
                     'btn-black'    => 'Button Black',
                     'btn-white'     => 'Button White'
                  ),
                  'class'     => 'width-1-3',
                  'default'    => 'text-dark'
               ),
               array(
                 'id'        => 'info',
                 'type'      => 'info',
                 'title'      => 'Settings Video Link'
               ),
               array(
                  'id'        => 'link_video',
                  'type'      => 'text',
                  'title'     => t('Link Video (youtube/vimeo)'),
               ),
              
               array(
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
                  'desc'      => t('Add a class name and refer to it in custom CSS.'),
                  'class'     => 'width-1-3',
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
                  'options'   => gavias_content_builder_delay_wow(),
                  'desc'      => '0 = default',
                  'class'     => 'width-1-3'
               ), 
            ),                                       
         );
      return $fields;
      }

      function render_content( $attr = array(), $content = ''  ){
         extract(gavias_merge_atts(array(
            'sub_title'       => '',
            'title'           => '',
            'content'         => '',
            'button_align'    => '',
            'font_size'       => '00',
            'font_weight'     => 'fw-700',
            'width'           => '',
            'link'            => '',
            'button_title'    => '',
            'style_button'    => 'btn-theme',
            'link_2'          => '',
            'button_title_2'  => '',
            'style_button_2'  => 'btn-theme',
            'link_video'      => '',
            'target'          => '',
            'el_class'        => '',
            'animate'         => '',
            'animate_delay'   => '',
            'style_text'      => 'text-dark',
            'video'           => ''
         ), $attr));
         
         // target
         if( $target =='on' ){
            $target = 'target="_blank"';
         } else {
            $target = false;
         }
         
         $class = array();
         $class[] = $el_class;
         $class[] = $button_align;
         $class[] = $style_text;
         if($animate) $class[] = 'wow ' . $animate; 

         $style = '';
         if($width) $style .= "max-width: {$width};";
         $style = !empty($style) ? "style=\"".$style ."\"" : '';
         ob_start();
         ?>
         <div class="widget gsc-call-to-action <?php print implode(' ', $class) ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="content-inner clearfix">
               <?php if($link_video){ ?>
                  <div class="video-inner"><a class="popup-video link-video" href="<?php print $link_video ?>"><i class="fa fa-play"></i></a></div>
               <?php } ?> 
               <div class="content" <?php print $style ?>>
                  <?php if($sub_title){ ?><div class="sub-title"><span><?php print $sub_title; ?></span></div><?php } ?>
                  <h2 class="title fsize-<?php print $font_size ?> <?php print $font_weight ?>"><span><?php print $title; ?></span></h2>
                   
                  <div class="desc"><?php print $content; ?></div>
               </div>
               <div class="button-action">
                  <?php if($link){?>
                     <a href="<?php print $link ?>" class="<?php print $style_button ?>" <?php print $target ?>><?php print $button_title ?></a>   
                  <?php } ?>
                  <?php if($link_2){?>
                     <a href="<?php print $link_2 ?>" class="<?php print $style_button_2 ?>" <?php print $target ?>><?php print $button_title_2 ?></a>   
                  <?php } ?>
               </div>
            </div>
         </div>
         <?php return ob_get_clean() ?>
      <?php
      }

   }
endif;   




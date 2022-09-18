<?php 

if(!class_exists('element_gva_counter')):
   class element_gva_counter{
      public function render_form(){
         $fields = array(
            'type' => 'element_gva_counter',
            'title' => ('Counter'),
            'fields' => array(
               array(
                  'id'        => 'title',
                  'title'     => t('Title'),
                  'type'      => 'text',
                  'admin'     => true
               ),
               array(
                  'id'        => 'icon',
                  'title'     => t('Icon'),
                  'type'      => 'text',
                  'default'       => '',
                  'desc'     => t('Use class icon font <a target="_blank" href="http://fontawesome.io/icons/">Icon Awesome</a> or <a target="_blank" href="http://gaviasthemes.com/icons/">Custom icon</a>'),
               ),
               array(
                  'id'        => 'number',
                  'title'     => t('Number'),
                  'type'      => 'text',
               ),
               array(
                  'id'        => 'symbol',
                  'title'     => t('Symbol'),
                  'type'      => 'text',
               ),
               array(
                  'id'        => 'type',
                  'title'     => t('Style'),
                  'type'      => 'select',
                  'options'   => array(
                     'icon-left'       => 'Style I: Icon Left',
                     'icon-top'        => 'Style II: Icon Top',
                     'no-icon'         => 'Style III: Without Icon',
                     'icon-box'        => 'Style IV: Box with Icon'
                  ),
                  'default'    => 'icon-left',
               ),
               array(
                  'id'        => 'color',
                  'type'      => 'text',
                  'title'     => t('Icon Color'),
                  'desc'      => t('Use color name ( blue ) or hex ( #2991D6 )'),
               ),
               array(
                  'id'        => 'style_text',
                  'type'      => 'select',
                  'title'     => t('Skin Text for box'),
                  'options'   => array(
                     'text-dark'   => 'Text dark',
                     'text-light'   => 'Text light'
                  ),
                  'default'       => 'text-dark'
               ),
               array(
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
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
                  'options'   => gavias_content_builder_delay_wow(),
                  'desc'      => '0 = default',
                  'class'     => 'width-1-2'
               ), 
         
            ),                                      
         );
         return $fields;
      }

      public function render_content( $attr = array(), $content = '' ){
         extract(gavias_merge_atts(array(
            'title'         => '',
            'icon'          => '',
            'number'        => '',
            'symbol'        => '',
            'type'          => 'icon-top',
            'el_class'      => '',
            'style_text'    => 'text-dark',
            'color'         => '',
            'animate'       => '',
            'animate_delay' => ''
         ), $attr));
         $class = array();
         $class[] = $el_class;
         $class[] = 'position-'.$type;
         $class[] = $style_text;
         $style = '';
         if($color) $style = "background: {$color};";
         if($style) $style = 'style="'.$style.'"';
         if($animate) $class[] = 'wow ' . $animate; 
         ob_start();
         ?>
            <div class="widget milestone-block <?php print gavias_implode_classes($class); ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <?php if($icon){ ?>
                  <div class="milestone-icon"><span <?php print $style ?> class="icon <?php print $icon; ?>"></span></div>
               <?php } ?>   
               <div class="milestone-right">
                  <div class="milestone-number-inner">
                     <span class="milestone-number"><?php print $number; ?></span>
                     <?php if($symbol){ ?>
                        <span class="symbol"><?php print $symbol; ?></span>
                     <?php } ?>
                  </div>
                  <h3 class="milestone-text"><?php print $title ?></h3>
               </div>
               <?php 
                  if($type == 'icon-box'){ 
                     print '<div class="icon-background"><i class="fi flaticon-directory"></i></div>';
                  }
               ?>
            </div>
         <?php return ob_get_clean() ?>
         <?php
      }

   }
endif;
   




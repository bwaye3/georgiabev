<?php 
if(!class_exists('element_gva_icon_box_style')):
   class element_gva_icon_box_style{
      public function render_form(){
         $fields = array(
            'type' => 'gsc_icon_box_style',
            'title' => ('Icon Box Style'), 
            'fields' => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title'),
                  'admin'     => true,
                  'default'   => 'Choose A Category'
               ),
               array(
                  'id'        => 'content',
                  'type'      => 'textarea',
                  'title'     => t('Content'),
               ),
               array(
                  'id'        => 'icon',
                  'type'      => 'text',
                  'title'     => t('Icon class'),
                  'default'   => 'fi flaticon-selection',
                  'class'     => 'width-1-2',
                  'desc'     => t('Use class icon font <a target="_blank" href="http://fontawesome.io/icons/">Icon Awesome</a> or <a target="_blank" href="http://gaviasthemes.com/icons/ionicon/">Custom icon</a>'),
               ),
               array(
                  'id'        => 'image',
                  'type'      => 'upload',
                  'title'     => t('Image Icon'),
                  'class'     => 'width-1-2',
                  'desc'      => t('Use image icon instead of icon class'),
               ),
               array(
                  'id'            => 'style',
                  'type'          => 'select',
                  'options'       => array(
                     'style-1'               => 'Style I', 
                     'style-2'               => 'Style II',
                     'style-3'               => 'Style III'
                  ),
                  'title'  => t('Icon Position'),
                  'default'    => 'style-1',
                  'class'     => 'width-1-3',
               ),
               array(
                  'id'            => 'hidden_content',
                  'type'          => 'select',
                  'options'       => array(
                     ''                      => t('Always Display'),
                     'hidden-xs hidden-sm'   => t('Hidden Small & Extra Small Screen (hidden-sm & hidden-xs)'), 
                     'hidden-sm'             => t('Hidden Small Screen (hidden-sm)'), 
                     'hidden-xs'             => t('hidden Extra Small Screen (hidden-xs)'), 
                  ),
                  'class'     => 'width-1-3',
                  'title'  => t('Hidden Content in Small Screen'),
               ),
               array(
                  'id'        => 'skin_text',
                  'type'      => 'select',
                  'title'     => 'Skin Text for box',
                  'options'   => array(
                     'text-dark'  => t('Text Dark'), 
                     'text-light' => t('Text Light')
                  ),
                  'class'     => 'width-1-3'
               ),
               array(
                  'id'        => 'link',
                  'type'      => 'text',
                  'title'     => t('Link'),
                  'desc'      => t('Link for text'),
                  'class'     => 'width-1-2'
               ),
               array(
                  'id'        => 'target',
                  'type'      => 'select',
                  'options'   => array( 'off' => 'Off', 'on' => 'On' ),
                  'title'     => t('Open in new window'),
                  'desc'      => t('Adds a target="_blank" attribute to the link.'),
                  'class'     => 'width-1-2'
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
                  'id'     => 'el_class',
                  'type'      => 'text',
                  'title'  => t('Extra class name'),
                  'desc'      => t('Add a class name and refer to it in custom CSS.'),
                  'class'     => 'width-1-3'
               ),

            ),                                       
         );
         return $fields;
      }

      public static function render_content( $attr = array(), $content = '' ){
         global $base_url;
         extract(gavias_merge_atts(array(
            'title'              => '',
            'content'            => '',
            'hidden_content'     => '',
            'icon'               => '',
            'image'              => '',
            'style'              => 'style-1',
            'link'               => '',
            'skin_text'          => '',
            'target'             => 'on',
            'animate'            => '',
            'animate_delay'      => '',
            'min_height'         => '',
            'el_class'           => ''
         ), $attr));
         
         if($image) $image = $base_url . $image; 

         // target
         if( $target =='on' ){
            $target = 'target="_blank"';
         } else {
            $target = false;
         }

         $class = array();
         $class[] = $style;
         $class[] = $skin_text;
         if($el_class) $class[] = $el_class;
         
         $css = array(); 
         if($min_height) $css[] = "min-height:{$min_height};";
         
         if($animate) $class[] = 'wow ' . $animate;

         ob_start();
         ?>
         
         <?php if($style == 'style-1'){ ?>
            <div class="widget gsc-icon-box-style <?php print gavias_implode_classes($class) ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <div class="icon-box-content">
                  <?php if($icon || $image){ ?>
                     <div class="icon-inner">
                        <span class="box-icon">
                           <?php 
                              if($link){ 
                                 print ('<a ' . $target . 'href="' . $link . '">'); 
                              }
                              if($icon){ 
                                 print ('<i class="' . $icon . '"></i>'); 
                              }
                              if($image){
                                 print ('<img src="' . $image . '" alt="' . strip_tags($title) . '"/>');
                              } 
                              if($link){ 
                                 print '</a>';
                              } 
                           ?>
                        </span>   
                     </div>
                  <?php } ?>
                  <div class="box-content">
                     <h3 class="title">
                        <?php 
                           if($link) print ('<a ' . $target . 'href="' . $link . '">'); 
                              print $title; 
                           if($link) print '</a>';
                        ?>
                     </h3>
                     <?php 
                        if($content){
                           print ('<div class="desc ' . $hidden_content .'">' . $content . '</div>');
                        } 
                     ?>   
                  </div>
               </div>   
            </div> 
         <?php } ?>

         <?php if($style == 'style-2'){ ?>
            <div class="widget gsc-icon-box-style <?php print gavias_implode_classes($class) ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <div class="icon-box-content">
                  <?php if($icon || $image){ ?>
                     <div class="icon-inner">
                        <span class="box-icon">
                           <?php 
                              if($link){ 
                                 print ('<a ' . $target . 'href="' . $link . '">'); 
                              }
                              if($icon){ 
                                 print ('<i class="' . $icon . '"></i>'); 
                              }
                              if($image){
                                 print ('<img src="' . $image . '" alt="' . strip_tags($title) . '"/>');
                              } 
                              if($link){ 
                                 print '</a>';
                              } 
                           ?>
                        </span>   
                     </div>
                  <?php } ?>
                  <div class="box-content">
                     <h3 class="title">
                        <?php 
                           if($link) print ('<a ' . $target . 'href="' . $link . '">'); 
                              print $title; 
                           if($link) print '</a>';
                        ?>
                     </h3>
                     <?php 
                        if($content){
                           print ('<div class="desc ' . $hidden_content .'">' . $content . '</div>');
                        }
                        if($icon){
                           print '<span class="box-icon-hover">';
                              print ('<i class="' . $icon . '"></i>'); 
                           print '</span>';
                        }
                     ?>
                  </div>
               </div>   
            </div> 
         <?php } ?>

         <?php if($style == 'style-3'){ ?>
            <div class="widget gsc-icon-box-style <?php print gavias_implode_classes($class) ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <div class="icon-box-content">
                  <?php if($icon || $image){ ?>
                     <div class="icon-inner">
                        <span class="box-icon">
                           <?php 
                              if($link){ 
                                 print ('<a ' . $target . 'href="' . $link . '">'); 
                              }
                              if($icon){ 
                                 print ('<i class="' . $icon . '"></i>'); 
                              }
                              if($image){
                                 print ('<img src="' . $image . '" alt="' . strip_tags($title) . '"/>');
                              } 
                              if($link){ 
                                 print '</a>';
                              } 
                           ?>
                        </span>   
                     </div>
                  <?php } ?>
                  <div class="box-content">
                     <h3 class="title">
                        <?php 
                           if($link) print ('<a ' . $target . 'href="' . $link . '">'); 
                              print $title; 
                           if($link) print '</a>';
                        ?>
                     </h3>
                     <?php 
                        if($content){
                           print ('<div class="desc ' . $hidden_content .'">' . $content . '</div>');
                        } 
                     ?>   
                  </div>
               </div>   
            </div> 
         <?php } ?>

         <?php return ob_get_clean();
       
      }

   } 
endif;   

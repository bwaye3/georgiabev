<?php 
if(!class_exists('element_gva_icon_box_carousel')):
   class element_gva_icon_box_carousel{
      public function render_form(){
         $fields = array(
            'type' => 'gsc_icon_box_carousel',
            'title' => t('Icon Box Carousel'),
            'fields' => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title'),
                  'default'   => t('Icon Box Carousel'),
                  'admin'     => true,
                  'class'     => 'display-admin width-1-2'
               ),
               array(
                  'id'        => 'style',
                  'type'      => 'select',
                  'title'     => t('Style'),
                  'options'   => array(
                     'style-1'   => t('Style I')
                  ),
                  'class'     => 'width-1-2'
               ),
               array(
                  'id'        => 'animate',
                  'type'      => 'select',
                  'title'     => t('Animation'),
                  'options'   => gavias_content_builder_animate(),
                  'class'     => 'width-1-3'
               ), 
               array(
                  'id'        => 'animate_delay',
                  'type'      => 'select',
                  'title'     => t('Animation Delay'),
                  'options'   => gavias_content_builder_delay_wow(),
                  'class'     => 'width-1-3'
               ), 
               array(
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
                  'desc'      => t('Add a class name and refer to it in custom CSS.'),
                  'class'     => 'width-1-3'
               ),   
            ),                                     
         );

         gavias_carousel_fields_settings($fields);
      
         for($i = 1; $i <= 6; $i++){
            $fields['fields'][] = array(
               'id'     => "info_${i}",
               'type'   => 'info',
               'desc'   => "Information for item {$i}"
            );
            $fields['fields'][] = array(
               'id'        => "title_{$i}",
               'type'      => 'text',
               'title'     => t("Title {$i}"),
               'default'   => 'Restuarant',
            );
            $fields['fields'][] = array(
               'id'           => "icon_{$i}",
               'type'         => 'text',
               'title'        => t("Icon {$i}"),
               'desc'     => t('Use class icon font <a target="_blank" href="http://fontawesome.io/icons/">Icon Awesome</a> or <a target="_blank" href="http://gaviasthemes.com/icons/cudau">Custom icon</a>'),
               'default'      => 'fi flaticon-cutlery',
               'class'       => 'width-1-2',
            );
            $fields['fields'][] = array(
               'id'        => "link_{$i}",
               'type'      => 'text',
               'title'     => t("Link {$i}"),
               'class'       => 'width-1-2',
            );
         }

         for($i = 7; $i <= 10; $i++){
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
               'id'           => "icon_{$i}",
               'type'         => 'text',
               'title'        => t("Icon {$i}"),
               'class'       => 'width-1-2',
               'desc'     => t('Use class icon font <a target="_blank" href="http://fontawesome.io/icons/">Icon Awesome</a> or <a target="_blank" href="http://gaviasthemes.com/icons/cudau">Custom icon</a>'),
            );
            $fields['fields'][] = array(
               'id'        => "link_{$i}",
               'type'      => 'text',
               'title'     => t("Link {$i}"),
               'class'       => 'width-1-2',
            );
         }
         return $fields;
      }

      public static function render_content( $attr = array(), $content = '' ){
         global $base_url;
         $default = array(
            'title'           => '',
            'style'           => 'style-1',
            'el_class'        => '',
            'animate'         => '',
            'animate_delay'   => '',
            'col_lg'          => '4',
            'col_md'          => '3',
            'col_sm'          => '2',
            'col_xs'          => '1',
            'auto_play'       => '0',
            'pagination'      => '0',
            'navigation'      => '0'
         );

         for($i=1; $i<=10; $i++){
            $default["title_{$i}"] = '';
            $default["icon_{$i}"] = '';
            $default["link_{$i}"] = '';
         }

         extract(gavias_merge_atts($default, $attr));

         $_id = gavias_content_builder_makeid();
         $classes[] = $style;
         if($el_class) $classes[] = $el_class;
         if($animate) $classes[] = 'wow ' . $animate;
         ob_start();
         ?>
         <div class="gsc-icon-box-carousel <?php print implode(' ', $classes) ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>> 
            <div class="owl-carousel init-carousel-owl owl-loaded owl-drag" data-items="<?php print $col_lg ?>" data-items_lg="<?php print $col_lg ?>" data-items_md="<?php print $col_md ?>" data-items_sm="<?php print $col_sm ?>" data-items_xs="<?php print $col_xs ?>" data-loop="1" data-speed="500" data-auto_play="<?php print $auto_play ?>" data-auto_play_speed="2000" data-auto_play_timeout="5000" data-auto_play_hover="1" data-navigation="<?php print $navigation ?>" data-rewind_nav="0" data-pagination="<?php print $pagination ?>" data-mouse_drag="1" data-touch_drag="1">
               <?php for($i=1; $i<=10; $i++){ 
                  $title = "title_{$i}";
                  $icon = "icon_{$i}";
                  $link = "link_{$i}";
                  if(!empty($$title)){ 
               ?>
                  <div class="item icon-box-item">
                     <div class="icon-box-item-content">
                        <div class="icon-box-item-inner">
                           <?php 
                              if($$icon){ 
                                 echo '<div class="box-icon"><i class="' . $$icon . '"></i></div>';
                              } 
                              if($$title){ 
                                 echo '<h3 class="title">'. $$title . '</h3>';
                              }        
                              if($$link){ 
                                 echo '<a class="link" href="'. $$link . '"></a>';
                              } 
                           ?>
                        </div>   
                     </div>
                  </div>
               <?php 
                  }
               }
               ?>
            </div> 
         </div>   

         <?php return ob_get_clean();
      }

   }
 endif;  




<?php 
if(!class_exists('element_gva_video_box')):
   class element_gva_video_box{
      public function render_form(){
         $fields = array(
            'type' => 'gsc_video_box',
            'title' => ('Video Box'), 
            'size' => 3,
            'fields' => array(
               array(
                  'id'        => 'title_left',
                  'type'      => 'text',
                  'title'     => t('Title Left'),
                  'admin'     => true
               ),
               array(
                  'id'        => 'title_right',
                  'type'      => 'text',
                  'title'     => t('Title Right'),
               ),
               array(
                  'id'        => 'content',
                  'type'      => 'text',
                  'title'     => t('Data Url'),
                  'desc'      => t('example: https://www.youtube.com/watch?v=4g7zRxRN1Xk'),
               ),
               array(
                  'id'        => 'image',
                  'type'      => 'upload',
                  'title'     => t('Image Preview'),
               ),
               array(
                  'id'        => 'style',
                  'type'      => 'select',
                  'options'   => array(
                     'style-1'         => 'Style I'
                  )
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
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
                  'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
               ),
            ),                                       
         );
         return $fields;
      }

      public static function render_content( $attr, $content = null ){
         global $base_url;
         extract(gavias_merge_atts(array(
            'title_left'      => '',
            'title_right'     => '',
            'content'         => '',
            'image'           => '',
            'style'           => 'style-1',
            'animate'         => '',
            'animate_delay'   => '',
            'el_class'        => '',
         ), $attr));

         $_id = gavias_content_builder_makeid();
         if($image) $image = $base_url . $image; 
         if($animate) $el_class .= ' wow ' . $animate; 
         ob_start(); 
         ?>
      
         <?php if($style == 'style-1'){ ?>
            <div class="widget gsc-video-box <?php print $el_class;?> clearfix <?php print $style ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
               <div class="video-inner">
                  <div class="video-image">
                     <a class="popup-video gsc-video-link" href="<?php print $content ?>">
                        <img src="<?php print $image ?>" alt="<?php print $title_left ?>"/>
                     </a>
                  </div>
                  <div class="video-content">
                     <?php
                        if($title_left){
                           echo ('<div class="title-left title">' . $title_left . '</div>'); 
                        }
                        echo '<div class="video-action">';
                           echo '<a class="popup-video" href="' . $content . '"><i class="fa fa-play"></i></a>';
                        echo '</div>';
                        if($title_right){
                           echo ('<div class="title-right title">' . $title_right . '</div>'); 
                        }
                     ?>
                  </div>
               </div>   
            </div>  
         <?php 
         } 
         return ob_get_clean(); 
      }
   }
endif;   





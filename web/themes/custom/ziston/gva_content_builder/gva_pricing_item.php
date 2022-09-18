<?php
if(!class_exists('element_gva_pricing_item')):
   class element_gva_pricing_item{
      public function render_form(){
         $fields = array(
            'type' => 'gsc_pricing_item',
            'title' => ('Pricing Item'), 
            'fields' => array(
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title'),
                  'desc'      => t('Pricing item title'),
                  'class'     => 'width-1-3',
                  'admin'     => true
               ),
               array(
                  'id'        => 'price',
                  'type'      => 'text',
                  'class'     => 'width-1-3',
                  'title'     => t('Price')
               ),
               array(
                  'id'        => 'price_sale',
                  'type'      => 'text',
                  'class'     => 'width-1-3',
                  'title'     => t('Price Sale')
               ),
               array(
                  'id'        => 'content',
                  'type'      => 'textarea',
                  'title'     => t('Content'),
                  'desc'      => t('HTML tags allowed.'),
                  'default'       => '<ul><li><strong>List</strong> item</li></ul>',
               ),
               array(
                  'id'        => 'link_title',
                  'type'      => 'text',
                  'title'     => t('Link title'),
                  'desc'      => t('Link will appear only if this field will be filled.'),
                  'class'     => 'width-1-2'
               ),
               
               array(
                  'id'        => 'link',
                  'type'      => 'text',
                  'title'     => t('Link'),
                  'desc'      => t('Link will appear only if this field will be filled.'),
                  'class'     => 'width-1-2'
               ),
               array(
                  'id'        => 'featured',
                  'type'      => 'select',
                  'title'     => t('Featured'),
                  'options'   => array( 'off' => 'No', 'on' => 'Yes' ),
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
                  'desc'      => t('Add a class name and refer to it in custom CSS.'),
                  'class'     => 'width-1-4'
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
                  'options'   => gavias_content_builder_delay_wow(),
                  'desc'      => '0 = default',
                  'class'     => 'width-1-4'
               )
            )                                          
         );
         return $fields;
      }

      public static function render_content( $attr = array(), $content = '' ){
         extract(gavias_merge_atts(array(
            'title'        => '',
            'price'        => '',
            'price_sale'   => '',
            'content'      => '',
            'link_title'   => ' Get Started',
            'link'         => '',
            'featured'     => 'off',
            'el_class'     => '',
            'animate'      => '',
            'animate_delay'   => ''
         ), $attr));

         if($featured == 'on') $el_class .= ' highlight-plan'; 
         if($animate) $el_class .= ' wow ' . $animate; 
         ob_start();
         ?>

         <div class="pricing-block <?php print $el_class ?>" <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            <div class="pricing-block-inner clearfix">
               <div class="pricing-block-top">
                  <h3 class="title"><?php print $title; ?></h3>
                  <div class="plan-price">
                     <?php 
                        $price_display = !empty($price_sale) ? $price_sale : $price;
                        if($price_display == '0') $price_display = t('Free');
                        print '<span class="price">' . $price_display . '</span>'; 
                        print !empty($price_sale) ? '<del>' . $price . '</del>': '';
                     ?>
                  </div>
               </div>
               <div class="pricing-block-content">   
                  <div class="content-inner">
                     <?php if($content){ ?>
                        <div class="plan-list">
                           <?php print $content ?>
                        </div>
                     <?php } ?>   
                     <?php if($link){ ?>
                        <div class="plan-signup">
                           <a href="<?php print $link; ?>"><?php print $link_title ?></a>
                        </div>
                     <?php } ?>  
                  </div>   
               </div>   
            </div>      
         </div>
   	<?php return ob_get_clean();
      }
   }   
endif;   




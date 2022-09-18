<?php 
if(!class_exists('element_gva_heading')):
   class element_gva_heading{
      public function render_form(){
         $fields = array(
            'type'      => 'gsc_heading',
            'title'     => t('Heading'), 
            'fields'    => array(
               array(
                  'id'        => 'info_1',
                  'type'      => 'info',
                  'title'     => t('Content'),
               ),
               array(
                  'id'        => 'sub_title',
                  'type'      => 'text',
                  'title'     => t('Sub Title'),
               ),
               array(
                  'id'        => 'title',
                  'type'      => 'text',
                  'title'     => t('Title'),
                  'admin'     => true
               ),
               array(
                  'id'        => 'desc',
                  'type'      => 'textarea',
                  'title'     => t('Description'),
               ),
               array(
                  'id'        => 'info_2',
                  'type'      => 'info',
                  'title'     => t('Styling'),
               ),
               array(
                  'id'        => 'align',
                  'type'      => 'select',
                  'title'     => t('Align text for heading'),
                  'options'   => array(
                        'align-center'    => 'Align Center',
                        'align-left'      => 'Align Left',
                        'align-right'     => 'Align Right'
                  ),
                  'default'       => 'align-center',
                  'class'     => 'width-1-3',
               ),
               array(
                  'id'        => 'style',
                  'type'      => 'select',
                  'title'     => t('Style display'),
                  'options'   => array(
                        'style-1'   => 'Style I',
                        'style-2'   => 'Style II',
                        'style-3'   => 'Style III',
                  ),
                  'class'     => 'width-1-3',
               ),
               array(
                  'id'        => 'max_width',
                  'type'      => 'text',
                  'title'     => t('Max Width'),
                  'default'   => '800px',
                  'desc'      => t('e.g: 800px'),
                  'class'     => 'width-1-3',
               ),
               array(
                  'id'        => 'info_21',
                  'type'      => 'info',
                  'title'     => t('Title Styling'),
               ),

               //Font-size
               array(
                  'id'        => 'title_font_size',
                  'type'      => 'number',
                  'title'     => t('Title Font Size'),
                  'desc'      => 'example: 30, empty = default',
                  'class'     => 'width-1-3',
                  'default'   => ''
               ),
               array(
                  'id'        => 'title_font_size_lg',
                  'type'      => 'number',
                  'title'     => t('Tablet | Title Font Size'),
                  'desc'      => 'example: 30, empty = default',
                  'class'     => 'width-1-3',
                  'default'   => ''
               ),
               array(
                  'id'        => 'title_font_size_md',
                  'type'      => 'number',
                  'title'     => t('Mobile | Title Font Size'),
                  'desc'      => 'example: 30, empty = default',
                  'class'     => 'width-1-3',
                  'default'   => ''
               ),

               //Line height
               array(
                  'id'        => 'title_line_height',
                  'type'      => 'number',
                  'title'     => t('Title Line Height'),
                  'desc'      => 'example: 36',
                  'class'     => 'width-1-3',
                  'default'   => ''
               ),
               array(
                  'id'        => 'title_line_height_lg',
                  'type'      => 'number',
                  'title'     => t('Tablet | Title Line Height'),
                  'desc'      => 'example: 36',
                  'class'     => 'width-1-3',
                  'default'   => ''
               ),
               array(
                  'id'        => 'title_line_height_md',
                  'type'      => 'number',
                  'title'     => t('Mobile | Title Line Height'),
                  'desc'      => 'example: 36',
                  'class'     => 'width-1-3',
                  'default'   => ''
               ),

               //===
               array( 
                  'id'        => 'title_font_weight',
                  'type'      => 'select',
                  'title'     => t('Title Font Weight'),
                  'options'   => array(
                     ''      => '-- Default --',
                     '300'   => '300',
                     '400'   => '400',
                     '500'   => '500',
                     '600'   => '600',
                     '700'   => '700',
                     '800'   => '800',
                     '900'   => '900',
                  ),
                  'class'     => 'width-1-4',
                  'default'   => ''
               ),
               array(
                  'id'        => 'title_color',
                  'type'      => 'select',
                  'title'     => t('Title Color'),
                  'options'   => array(
                     'text-black'   => 'Black Color',
                     'text-white'   => 'White Color',
                     'text-theme'   => 'Theme Color'
                  ),
                  'class'     => 'width-1-4',
                  'default'   => 'text-black'
               ),
               array(
                  'id'        => 'html_tags',
                  'type'      => 'select',
                  'title'     => t('Html Title Tags'),
                  'options'   => array(
                        'h1' => 'H1',
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                        'h5' => 'H5',
                        'h6' => 'H6',
                  ),
                  'class'     => 'width-1-4',
                  'default'       => 'h2'
               ),
               array(
                  'id'        => 'title_space',
                  'type'      => 'text',
                  'title'     => t('Title Space'),
                  'class'     => 'width-1-4',
                  'desc'      => 'example: 30'
               ),
               array(
                  'id'        => 'info_22',
                  'type'      => 'info',
                  'title'     => t('SubTitle Styling'),
               ),
               array(
                  'id'        => 'subtitle_font_size',
                  'type'      => 'number',
                  'title'     => t('Sub Title Font Size'),
                  'default'   => '',
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'subtitle_line_height',
                  'type'      => 'number',
                  'title'     => t('Sub Title Line Height'),
                  'default'   => '',
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'subtitle_font_weight',
                  'type'      => 'select',
                  'title'     => t('Sub Title Font Weight'),
                  'options'   => array(
                     ''      => '-- Default --',
                     '300'   => '300',
                     '400'   => '400',
                     '500'   => '500',
                     '600'   => '600',
                     '700'   => '700',
                     '800'   => '800',
                     '900'   => '900',
                  ),
                  'default'   => '',
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'subtitle_color',
                  'type'      => 'text',
                  'title'     => t('Sub Title Color'),
                  'desc'      => t('example: #ccc, empty = default'),
                  'default'   => '',
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'info_23',
                  'type'      => 'info',
                  'title'     => t('Description Styling'),
               ),
               array(
                  'id'        => 'desc_font_size',
                  'type'      => 'number',
                  'title'     => t('Description Font Size'),
                  'default'   => '',
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'desc_line_height',
                  'type'      => 'number',
                  'title'     => t('Description Line Height'),
                  'default'   => '',
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'desc_font_weight',
                  'type'      => 'select',
                  'title'     => t('Description Font Weight'),
                  'options'   => array(
                     ''      => '-- Default --',
                     '300'   => '300',
                     '400'   => '400',
                     '500'   => '500',
                     '600'   => '600',
                     '700'   => '700',
                     '800'   => '800',
                     '900'   => '900',
                  ),
                  'default'   => '',
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'desc_color',
                  'type'      => 'select',
                  'title'     => t('Description Color'),
                  'options'   => array(
                      ''             => '-- Default --',
                     'text-gray'    => 'Gray Color',
                     'text-light'   => 'Gray Light Color',
                     'text-black'   => 'Black Color',
                     'text-white'   => 'White Color',
                     'text-theme'   => 'Theme Color'
                  ),
                  'default'   => 'text-gray',
                  'class'     => 'width-1-4'
               ),
                array(
                  'id'        => 'info_3',
                  'type'      => 'info',
                  'title'     => t('Button'),
               ),
               array(
                  'id'        => 'button_link',
                  'type'      => 'text',
                  'title'     => t('Button Link'),
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'button_text',
                  'type'      => 'text',
                  'title'     => t('Button Text'),
                  'default'       => 'Read More',
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'button_style',
                  'type'      => 'select',
                  'title'     => t('Button Style'),
                  'options'   => array(
                     'btn-theme'    => 'Button Theme',
                     'btn-white'    => 'Button White',
                     'btn-black'    => 'Button Black',
                     'btn-inline'   => 'Button Inline'
                  ),
                  'default'   => 'text-gray',
                  'class'     => 'width-1-4'
               ),
               array(
                  'id'        => 'button_space',
                  'type'      => 'text',
                  'title'     => t('Button Space'),
                  'class'     => 'width-1-4',
                  'desc'      => 'example: 30'
               ),
                array(
                  'id'        => 'info_4',
                  'type'      => 'info',
                  'title'     => t('Other'),
               ),
               array(
                  'id'        => 'remove_padding',
                  'type'      => 'select',
                  'title'     => t('Remove Padding'),
                  'options'   => array(
                        ''                      => 'Default',
                        'padding-bottom-20'     => 'Small padding',
                        'padding-top-0'         => 'Remove padding top',
                        'padding-bottom-0'      => 'Remove padding bottom',
                        'padding-bottom-0 padding-top-0'   => 'Remove padding top & bottom'
                  ),
                  'default'       => '',
                  'desc'      => 'Default heading padding top & bottom: 30px',
                  'class'     => 'width-1-2'
               ),
               array(
                  'id'        => 'el_class',
                  'type'      => 'text',
                  'title'     => t('Extra class name'),
                  'desc'      => t('Style particular content element differently - add a class name and refer to it in custom CSS.'),
                  'class'     => 'width-1-2'
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
               )
            ),                                       
         );
         return $fields;
      } 
      public static function render_content( $attr = array(), $content = '' ){
          global $base_url;
         extract(gavias_merge_atts(array(
            'title'                 => '',
            'desc'                  => '',
            'sub_title'             => '',
            'align'                 => '',
            'style'                 => 'style-1',
            'title_font_size'       => '0',
            'title_line_height'     => '0',
            'title_font_weight'     => '',
            'title_color'           => 'text-black',
            'title_space'           => '',
            'subtitle_font_size'    => '0',
            'subtitle_line_height'  => '0',
            'subtitle_font_weight'  => '',
            'subtitle_color'        => '', 
            'desc_font_size'        => '0',
            'desc_line_height'      => '',
            'desc_font_weight'      => '',
            'desc_color'            => 'text-gray',
            'button_link'           => '',
            'button_text'           => 'Read More',
            'button_style'          => 'btn-theme',
            'button_space'          => '',
            'html_tags'             => 'h2',
            'max_width'             => '',
            'el_class'              => '',
            'remove_padding'        => '',
            'animate'               => '',
            'animate_delay'         => '',
            'class_css'             => ''
         ), $attr));

         $class = array();
         $class[] = $el_class;
         $class[] = $align;
         $class[] = $style;
         $class[] = $remove_padding;
         $class[] = $class_css;
         if($animate) $class[] = 'wow ' . $animate;

         $style = '';
         if($max_width) $style = " style=\"max-width: {$max_width};\"";
         
         $classes_title_text = '';
         $classes_title = array();
         $title_line_height ? $classes_title[] = "lheight-{$title_line_height}" : false;
         $title_font_size ? $classes_title[] = "fsize-{$title_font_size}" : false;
         $title_font_weight ? $classes_title[] = "fweight-{$title_font_weight}" : false;
         $classes_title[] = $title_color;
         $classes_title_text = implode(' ', $classes_title);

         $classes_subtitle_text = '';
         $classes_subtitle = array();
         $subtitle_line_height ? $classes_subtitle[] = "lheight-{$subtitle_line_height}" : false;
         $subtitle_font_size ? $classes_subtitle[] = "fsize-{$subtitle_font_size}" : false;
         $subtitle_font_weight ? $classes_subtitle[] = "fweight-{$subtitle_font_weight}" : false;
         $classes_subtitle_text = implode(' ', $classes_subtitle);

         $classes_desc_text = '';
         $classes_desc = array();
         $desc_line_height ? $classes_desc[] = "lheight-{$desc_line_height}" : false;
         $desc_font_size ? $classes_desc[] = "fsize-{$desc_font_size}" : false;
         $desc_font_weight ? $classes_desc[] = "fweight-{$desc_font_weight}" : false;
         $classes_desc[] = $desc_color;
         $classes_desc_text = implode(' ', $classes_desc);
         ob_start();
         ?>

         <div class="widget gsc-heading <?php print implode(' ', $class) ?>"<?php print $style; ?> <?php print gavias_content_builder_print_animate_wow('', $animate_delay) ?>>
            
            <div class="heading-content clearfix">
               <?php if($sub_title){ ?><div class="sub-title <?php print $classes_subtitle_text ?>"><span><?php print $sub_title; ?></span></div><?php } ?>
               <?php if($title){ ?><<?php echo $html_tags ?> class="title <?php print $classes_title_text; ?>">
                  <span><?php print $title; ?></span>
               </<?php echo $html_tags ?>><?php } ?>
               <?php if($desc){ ?>
                  <div class="title-desc <?php print $classes_desc_text ?>"><?php print $desc; ?></div>
               <?php } ?>
            </div>
            
            <?php if($button_link){ ?>
               <div class="heading-action clearfix">
                  <a href="<?php echo $button_link ?>" class="<?php print $button_style; ?>"><span><?php echo $button_text ?></span></a>
               </div>
            <?php } ?>

         </div>
         <div class="clearfix"></div>
         <?php return ob_get_clean() ?>
         <?php
      }

      public function render_style( $settings = array() ) {
         extract(gavias_merge_atts(array(
            'title_font_size'          => '',
            'title_font_size_lg'       => '',
            'title_font_size_md'       => '',
            'title_line_height'        => '',
            'title_line_height_lg'     => '',
            'title_line_height_md'     => '',
            'title_space'              => '',
            'subtitle_color'           => '',
            'button_space'             => '',
            'class_css'                => ''
         ), $settings));

         $css = '';
         $tmp_css = array();

         if($title_font_size) $tmp_css[]      = "font-size:{$title_font_size}px";
         if($title_line_height) $tmp_css[]    = "line-height:{$title_line_height}px";
         if($title_space) $tmp_css[]          = "margin-bottom:{$title_space}px";
         $css .= gavias_render_css("xl", ".{$class_css} .title", $tmp_css);

         $tmp_css = array();
         if($subtitle_color)  $css .= gavias_render_css("xl", ".{$class_css} .sub-title", "color:{$subtitle_color}");

         $tmp_css = array();
         if($title_font_size_lg) $tmp_css[]      = "font-size:{$title_font_size_lg}px";
         if($title_line_height_lg) $tmp_css[]    = "line-height:{$title_line_height_lg}px";
         $css .= gavias_render_css('lg', ".{$class_css} .title", $tmp_css);

         $tmp_css = array();
         if($title_font_size_md) $tmp_css[]      = "font-size:{$title_font_size_md}px";
         if($title_line_height_md) $tmp_css[]    = "line-height:{$title_line_height_md}px";
         $css .= gavias_render_css('md', ".{$class_css} .title", $tmp_css);

         if($button_space) $css .= gavias_render_css_inline(".{$class_css} .heading-action", "margin-top:{$button_space}px");
         return $css;
      }

   }
endif;


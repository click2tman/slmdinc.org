<?php

function ocms_education_form_system_theme_settings_alter(&$form, &$form_state) {

  $form['#attached']['library'][] = 'ocms_education/theme-settings';

  $form['mtt_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('MtT Theme Settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );

  $form['mtt_settings']['tabs'] = array(
    '#type' => 'vertical_tabs',
    '#default_tab' => 'basic_tab',
  );

  $form['mtt_settings']['basic_tab']['basic_settings'] = array(
    '#type' => 'details',
    '#title' => t('Basic Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['breadcrumb_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Breadcrumb separator'),
    '#default_value' => theme_get_setting('breadcrumb_separator','ocms_education'),
    '#size'          => 5,
    '#maxlength'     => 10,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['header'] = array(
    '#type' => 'item',
    '#markup' => t('<div class="theme-settings-title">Header positioning</div>'),
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['fixed_header'] = array(
    '#type' => 'checkbox',
    '#title' => t('Fixed position'),
    '#description'   => t('Use the checkbox to apply fixed position to the header.'),
    '#default_value' => theme_get_setting('fixed_header', 'ocms_education'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['scrolltop'] = array(
    '#type' => 'fieldset',
    '#title' => t('Scroll to top'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['scrolltop']['scrolltop_display'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show scroll-to-top button'),
    '#description'   => t('Use the checkbox to enable or disable scroll-to-top button.'),
    '#default_value' => theme_get_setting('scrolltop_display', 'ocms_education'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['scrolltop']['scrolltop_icon'] = array(
    '#type' => 'textfield',
    '#title' => t('Scroll To Top icon'),
    '#description'   => t('Enter the class of the icon you want from the Font Awesome library e.g.: fa-angle-up. A list of the available classes is provided here: <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet" target="_blank">http://fortawesome.github.io/Font-Awesome/cheatsheet</a>.'),
    '#default_value' => theme_get_setting('scrolltop_icon','ocms_education'),
    '#size'          => 20,
    '#maxlength'     => 100,
  );

  $form['mtt_settings']['layout_tab']['layout'] = array(
    '#type' => 'details',
    '#title' => t('Layout'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['layout_tab']['layout']['three_columns_grid_layout'] = array(
    '#type' => 'select',
    '#title' => t('Adjustments to the three-column, Bootstrap layout grid'),
    '#description'   => t('From the drop-down menu, select the grid of the three-column layout you would like to use. This way, you can set the width of each of your columns, when choosing a three-column layout.
    <br><br>Note: All options refer to Bootstrap columns.'),
    '#default_value' => theme_get_setting('three_columns_grid_layout', 'ocms_education'),
    '#options' => array(
      'grid_3_6_3' => t('3-6-3/Default'),
      'grid_2_6_4' => t('2-6-4'),
      'grid_4_6_2' => t('4-6-2'),
    ),
  );

  $form['mtt_settings']['looknfeel_tab']['looknfeel'] = array(
    '#type' => 'details',
    '#title' => t('Look\'n\'Feel'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['looknfeel_tab']['looknfeel']['color_scheme'] = array(
    '#type' => 'select',
    '#title' => t('Color Schemes'),
    '#description'   => t('From the drop-down menu, select the color scheme you prefer.'),
    '#default_value' => theme_get_setting('color_scheme', 'ocms_education'),
    '#options' => array(
      'gray' => t('Gray'),
      'gray-green' => t('Gray Green'),
      'gray-orange' => t('Gray Orange'),
      'gray-red' => t('Gray Red'),
      'gray-pink' => t('Gray Pink'),
      'gray-purple' => t('Gray Purple'),
      'blue' => t('Blue'),
      'green' => t('Green'),
      'orange' => t('Orange'),
      'red' => t('Red'),
      'pink' => t('Pink'),
      'purple' => t('Purple'),
    ),
  );

  $form['mtt_settings']['looknfeel_tab']['looknfeel']['form_style'] = array(
    '#type' => 'select',
    '#title' => t('Form styles of contact page'),
    '#description'   => t('From the drop-down menu, select the form style that you prefer.'),
    '#default_value' => theme_get_setting('form_style', 'ocms_education'),
    '#options' => array(
      'form-style-1' => t('Style-1 (default)'),
      'form-style-2' => t('Style-2'),
      ),
  );

  $form['mtt_settings']['font_tab']['font'] = array(
    '#type' => 'details',
    '#title' => t('Font Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['font_tab']['font']['font_title'] = array(
    '#type' => 'item',
    '#markup' => 'For every region pick the <strong>font-family</strong> that corresponds to your needs.',
  );

  $form['mtt_settings']['font_tab']['font']['sitename_font_family'] = array(
    '#type' => 'select',
    '#title' => t('Site name'),
    '#default_value' => theme_get_setting('sitename_font_family', 'ocms_education'),
    '#options' => array(
      'sff-01' => t('Merriweather, Georgia, Times, Serif'),
      'sff-02' => t('Source Sans Pro, Helvetica Neuee, Arial, Sans-serif'),
      'sff-03' => t('Ubuntu, Helvetica Neue, Arial, Sans-serif'),
      'sff-04' => t('PT Sans, Helvetica Neue, Arial, Sans-serif'),
      'sff-05' => t('Roboto, Helvetica Neue, Arial, Sans-serif'),
      'sff-06' => t('Open Sans, Helvetica Neue, Arial, Sans-serif'),
      'sff-07' => t('Lato, Helvetica Neue, Arial, Sans-serif'),
      'sff-08' => t('Roboto Condensed, Arial Narrow, Arial, Sans-serif'),
      'sff-09' => t('Exo, Arial, Helvetica Neue, Sans-serif'),
      'sff-10' => t('Roboto Slab, Trebuchet MS, Sans-serif'),
      'sff-11' => t('Raleway, Helvetica Neue, Arial, Sans-serif'),
      'sff-12' => t('Josefin Sans, Georgia, Times, Serif'),
      'sff-13' => t('Georgia, Times, Serif'),
      'sff-14' => t('Playfair Display, Times, Serif'),
      'sff-15' => t('Philosopher, Georgia, Times, Serif'),
      'sff-16' => t('Cinzel, Georgia, Times, Serif'),
      'sff-17' => t('Oswald, Helvetica Neue, Arial, Sans-serif'),
      'sff-18' => t('Playfair Display SC, Georgia, Times, Serif'),
      'sff-19' => t('Cabin, Helvetica Neue, Arial, Sans-serif'),
      'sff-20' => t('Noto Sans, Arial, Helvetica Neue, Sans-serif;'),
      'sff-21' => t('Helvetica Neue, Arial, Sans-serif'),
      'sff-22' => t('Droid Serif, Georgia, Times, Times New Roman, Serif'),
      'sff-23' => t('PT Serif, Georgia, Times, Times New Roman, Serif'),
      'sff-24' => t('Vollkorn, Georgia, Times, Times New Roman, Serif'),
      'sff-25' => t('Alegreya, Georgia, Times, Times New Roman, Serif'),
      'sff-26' => t('Noto Serif, Georgia, Times, Times New Roman, Serif'),
      'sff-27' => t('Crimson Text, Georgia, Times, Times New Roman, Serif'),
      'sff-28' => t('Gentium Book Basic, Georgia, Times, Times New Roman, Serif'),
      'sff-29' => t('Volkhov, Georgia, Times, Times New Roman, Serif'),
      'sff-30' => t('Times, Times New Roman, Serif'),
      'sff-31' => t('Alegreya SC, Georgia, Times, Times New Roman, Serif'),
      'sff-32' => t('Montserrat SC, Arial, Helvetica Neue, Sans-serif'),
      'sff-33' => t('Fira Sans, Arial, Helvetica Neue, Sans-serif'),
      'sff-34' => t('Lora, Georgia, Times, Times New Roman, Serif'),
      'sff-35' => t('Quattrocento Sans, Arial, Helvetica Neue, Sans-serif'),
      'sff-36' => t('Julius Sans One, Arial, Helvetica Neue, Sans-serif'),
    ),
  );

  $form['mtt_settings']['font_tab']['font']['slogan_font_family'] = array(
    '#type' => 'select',
    '#title' => t('Slogan'),
    '#default_value' => theme_get_setting('slogan_font_family', 'ocms_education'),
    '#options' => array(
      'slff-01' => t('Merriweather, Georgia, Times, Serif'),
      'slff-02' => t('Source Sans Pro, Helvetica Neuee, Arial, Sans-serif'),
      'slff-03' => t('Ubuntu, Helvetica Neue, Arial, Sans-serif'),
      'slff-04' => t('PT Sans, Helvetica Neue, Arial, Sans-serif'),
      'slff-05' => t('Roboto, Helvetica Neue, Arial, Sans-serif'),
      'slff-06' => t('Open Sans, Helvetica Neue, Arial, Sans-serif'),
      'slff-07' => t('Lato, Helvetica Neue, Arial, Sans-serif'),
      'slff-08' => t('Roboto Condensed, Arial Narrow, Arial, Sans-serif'),
      'slff-09' => t('Exo, Arial, Helvetica Neue, Sans-serif'),
      'slff-10' => t('Roboto Slab, Trebuchet MS, Sans-serif'),
      'slff-11' => t('Raleway, Helvetica Neue, Arial, Sans-serif'),
      'slff-12' => t('Josefin Sans, Georgia, Times, Serif'),
      'slff-13' => t('Georgia, Times, Serif'),
      'slff-14' => t('Playfair Display, Times, Serif'),
      'slff-15' => t('Philosopher, Georgia, Times, Serif'),
      'slff-16' => t('Cinzel, Georgia, Times, Serif'),
      'slff-17' => t('Oswald, Helvetica Neue, Arial, Sans-serif'),
      'slff-18' => t('Playfair Display SC, Georgia, Times, Serif'),
      'slff-19' => t('Cabin, Helvetica Neue, Arial, Sans-serif'),
      'slff-20' => t('Noto Sans, Arial, Helvetica Neue, Sans-serif;'),
      'slff-21' => t('Helvetica Neue, Arial, Sans-serif'),
      'slff-22' => t('Droid Serif, Georgia, Times, Times New Roman, Serif'),
      'slff-23' => t('PT Serif, Georgia, Times, Times New Roman, Serif'),
      'slff-24' => t('Vollkorn, Georgia, Times, Times New Roman, Serif'),
      'slff-25' => t('Alegreya, Georgia, Times, Times New Roman, Serif'),
      'slff-26' => t('Noto Serif, Georgia, Times, Times New Roman, Serif'),
      'slff-27' => t('Crimson Text, Georgia, Times, Times New Roman, Serif'),
      'slff-28' => t('Gentium Book Basic, Georgia, Times, Times New Roman, Serif'),
      'slff-29' => t('Volkhov, Georgia, Times, Times New Roman, Serif'),
      'slff-30' => t('Times, Times New Roman, Serif'),
      'slff-31' => t('Alegreya SC, Georgia, Times, Times New Roman, Serif'),
      'slff-32' => t('Montserrat SC, Arial, Helvetica Neue, Sans-serif'),
      'slff-33' => t('Fira Sans, Arial, Helvetica Neue, Sans-serif'),
      'slff-34' => t('Lora, Georgia, Times, Times New Roman, Serif'),
      'slff-35' => t('Quattrocento Sans, Arial, Helvetica Neue, Sans-serif'),
      'slff-36' => t('Julius Sans One, Arial, Helvetica Neue, Sans-serif'),
    ),
  );

  $form['mtt_settings']['font_tab']['font']['headings_font_family'] = array(
    '#type' => 'select',
    '#title' => t('Headings'),
    '#default_value' => theme_get_setting('headings_font_family', 'ocms_education'),
    '#options' => array(
      'hff-01' => t('Merriweather, Georgia, Times, Serif'),
      'hff-02' => t('Source Sans Pro, Helvetica Neuee, Arial, Sans-serif'),
      'hff-03' => t('Ubuntu, Helvetica Neue, Arial, Sans-serif'),
      'hff-04' => t('PT Sans, Helvetica Neue, Arial, Sans-serif'),
      'hff-05' => t('Roboto, Helvetica Neue, Arial, Sans-serif'),
      'hff-06' => t('Open Sans, Helvetica Neue, Arial, Sans-serif'),
      'hff-07' => t('Lato, Helvetica Neue, Arial, Sans-serif'),
      'hff-08' => t('Roboto Condensed, Arial Narrow, Arial, Sans-serif'),
      'hff-09' => t('Exo, Arial, Helvetica Neue, Sans-serif'),
      'hff-10' => t('Roboto Slab, Trebuchet MS, Sans-serif'),
      'hff-11' => t('Raleway, Helvetica Neue, Arial, Sans-serif'),
      'hff-12' => t('Josefin Sans, Georgia, Times, Serif'),
      'hff-13' => t('Georgia, Times, Serif'),
      'hff-14' => t('Playfair Display, Times, Serif'),
      'hff-15' => t('Philosopher, Georgia, Times, Serif'),
      'hff-16' => t('Cinzel, Georgia, Times, Serif'),
      'hff-17' => t('Oswald, Helvetica Neue, Arial, Sans-serif'),
      'hff-18' => t('Playfair Display SC, Georgia, Times, Serif'),
      'hff-19' => t('Cabin, Helvetica Neue, Arial, Sans-serif'),
      'hff-20' => t('Noto Sans, Arial, Helvetica Neue, Sans-serif;'),
      'hff-21' => t('Helvetica Neue, Arial, Sans-serif'),
      'hff-22' => t('Droid Serif, Georgia, Times, Times New Roman, Serif'),
      'hff-23' => t('PT Serif, Georgia, Times, Times New Roman, Serif'),
      'hff-24' => t('Vollkorn, Georgia, Times, Times New Roman, Serif'),
      'hff-25' => t('Alegreya, Georgia, Times, Times New Roman, Serif'),
      'hff-26' => t('Noto Serif, Georgia, Times, Times New Roman, Serif'),
      'hff-27' => t('Crimson Text, Georgia, Times, Times New Roman, Serif'),
      'hff-28' => t('Gentium Book Basic, Georgia, Times, Times New Roman, Serif'),
      'hff-29' => t('Volkhov, Georgia, Times, Times New Roman, Serif'),
      'hff-30' => t('Times, Times New Roman, Serif'),
      'hff-31' => t('Alegreya SC, Georgia, Times, Times New Roman, Serif'),
      'hff-32' => t('Montserrat SC, Arial, Helvetica Neue, Sans-serif'),
      'hff-33' => t('Fira Sans, Arial, Helvetica Neue, Sans-serif'),
      'hff-34' => t('Lora, Georgia, Times, Times New Roman, Serif'),
      'hff-35' => t('Quattrocento Sans, Arial, Helvetica Neue, Sans-serif'),
      'hff-36' => t('Julius Sans One, Arial, Helvetica Neue, Sans-serif'),
    ),
  );

  $form['mtt_settings']['font_tab']['font']['paragraph_font_family'] = array(
  '#type' => 'select',
  '#title' => t('Paragraph'),
  '#default_value' => theme_get_setting('paragraph_font_family', 'ocms_education'),
    '#options' => array(
      'pff-01' => t('Merriweather, Georgia, Times, Serif'),
      'pff-02' => t('Source Sans Pro, Helvetica Neuee, Arial, Sans-serif'),
      'pff-03' => t('Ubuntu, Helvetica Neue, Arial, Sans-serif'),
      'pff-04' => t('PT Sans, Helvetica Neue, Arial, Sans-serif'),
      'pff-05' => t('Roboto, Helvetica Neue, Arial, Sans-serif'),
      'pff-06' => t('Open Sans, Helvetica Neue, Arial, Sans-serif'),
      'pff-07' => t('Lato, Helvetica Neue, Arial, Sans-serif'),
      'pff-08' => t('Roboto Condensed, Arial Narrow, Arial, Sans-serif'),
      'pff-09' => t('Exo, Arial, Helvetica Neue, Sans-serif'),
      'pff-10' => t('Roboto Slab, Trebuchet MS, Sans-serif'),
      'pff-11' => t('Raleway, Helvetica Neue, Arial, Sans-serif'),
      'pff-12' => t('Josefin Sans, Georgia, Times, Serif'),
      'pff-13' => t('Georgia, Times, Serif'),
      'pff-14' => t('Playfair Display, Times, Serif'),
      'pff-15' => t('Philosopher, Georgia, Times, Serif'),
      'pff-16' => t('Oswald, Helvetica Neue, Arial, Sans-serif'),
      'pff-17' => t('Playfair Display SC, Georgia, Times, Serif'),
      'pff-18' => t('Cabin, Helvetica Neue, Arial, Sans-serif'),
      'pff-19' => t('Noto Sans, Arial, Helvetica Neue, Sans-serif;'),
      'pff-20' => t('Helvetica Neue, Arial, Sans-serif'),
      'pff-21' => t('Droid Serif, Georgia, Times, Times New Roman, Serif'),
      'pff-22' => t('PT Serif, Georgia, Times, Times New Roman, Serif'),
      'pff-23' => t('Vollkorn, Georgia, Times, Times New Roman, Serif'),
      'pff-24' => t('Alegreya, Georgia, Times, Times New Roman, Serif'),
      'pff-25' => t('Noto Serif, Georgia, Times, Times New Roman, Serif'),
      'pff-26' => t('Crimson Text, Georgia, Times, Times New Roman, Serif'),
      'pff-27' => t('Gentium Book Basic, Georgia, Times, Times New Roman, Serif'),
      'pff-28' => t('Volkhov, Georgia, Times, Times New Roman, Serif'),
      'pff-29' => t('Times, Times New Roman, Serif'),
      'pff-30' => t('Fira Sans, Arial, Helvetica Neue, Sans-serif'),
      'pff-31' => t('Lora, Georgia, Times, Times New Roman, Serif'),
      'pff-32' => t('Quattrocento Sans, Arial, Helvetica Neue, Sans-serif'),
    ),
  );

  $form['mtt_settings']['slideshows_tab']['slideshow'] = array(
    '#type' => 'details',
    '#title' => t('Sliders'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['revolution_slider_boxedwidth'] = array(
    '#type' => 'fieldset',
    '#title' => t('Boxed Width (Slider Revolution)'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['revolution_slider_boxedwidth']['rs_slideshow_boxedwidth_effect'] = array(
    '#type' => 'select',
    '#title' => t('Effects'),
    '#description'   => t('From the drop-down menu, select the slideshow effect you prefer.'),
    '#default_value' => theme_get_setting('rs_slideshow_boxedwidth_effect', 'ocms_education'),
    '#options' => array(
      'fade' => t('Fade'),
      'slideup' => t('Slide To Top'),
      'slidedown' => t('Slide To Bottom'),
      'slideright' => t('Slide To Right'),
      'slideleft' => t('Slide To Left'),
      'slidehorizontal' => t('Slide Horizontal'),
      'slidevertical' => t('Slide Vertical'),
      'boxslide' => t('Slide Boxes'),
      'slotslide-horizontal' => t('Slide Slots Horizontal'),
      'slotslide-vertical' => t('Slide Slots Vertical'),
      'boxfade' => t('Fade Boxes'),
      'slotfade-horizontal' => t('Fade Slots Horizontal'),
      'slotfade-vertical' => t('Fade Slots Vertical'),
      'fadefromright' => t('Fade and Slide from Right'),
      'fadefromleft' => t('Fade and Slide from Left'),
      'fadefromtop' => t('Fade and Slide from Top'),
      'fadefrombottom' => t('Fade and Slide from Bottom'),
      'fadetoleftfadefromright' => t('Fade To Left and Fade From Right'),
      'fadetorightfadefromleft' => t('Fade To Right and Fade From Left'),
      'fadetotopfadefrombottom' => t('Fade To Top and Fade From Bottom'),
      'fadetobottomfadefromtop' => t('Fade To Bottom and Fade From Top'),
      'parallaxtoright' => t('Parallax to Right'),
      'parallaxtoleft' => t('Parallax to Left'),
      'parallaxtotop' => t('Parallax to Top'),
      'parallaxtobottom' => t('Parallax to Bottom'),
      'scaledownfromright' => t('Zoom Out and Fade From Right'),
      'scaledownfromleft' => t('Zoom Out and Fade From Left'),
      'scaledownfromtop' => t('Zoom Out and Fade From Top'),
      'scaledownfrombottom' => t('Zoom Out and Fade From Bottom'),
      'zoomout' => t('ZoomOut'),
      'zoomin' => t('ZoomIn'),
      'slotzoom-horizontal' => t('Zoom Slots Horizontal'),
      'slotzoom-vertical' => t('Zoom Slots Vertical'),
      'curtain-1' => t('Curtain from Left'),
      'curtain-2' => t('Curtain from Right'),
      'curtain-3' => t('Curtain from Middle'),
      '3dcurtain-horizontal' => t('3D Curtain Horizontal'),
      '3dcurtain-vertical' => t('3D Curtain Vertical'),
      'cube' => t('Cube Vertical'),
      'cube-horizontal' => t('Cube Horizontal'),
      'incube' => t('In Cube Vertical'),
      'incube-horizontal' => t('In Cube Horizontal'),
      'turnoff' => t('TurnOff Horizontal'),
      'turnoff-vertical' => t('TurnOff Vertical'),
      'papercut' => t('Paper Cut'),
      'flyin' => t('Fly In'),
      'random-static' => t('Random Flat'),
      'random-premium' => t('Random Premium'),
      'random' => t('Random Flat and Premium/Default'),
    ),
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['revolution_slider_boxedwidth']['rs_slideshow_boxedwidth_effect_time'] = array(
    '#type' => 'textfield',
    '#title' => t('Effect duration (sec)'),
    '#default_value' => theme_get_setting('rs_slideshow_boxedwidth_effect_time', 'ocms_education'),
    '#description'   => t('Set the speed of animations, in seconds.'),
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['revolution_slider_boxedwidth']['rs_slideshow_boxedwidth_initial_height'] = array(
    '#type' => 'textfield',
    '#title' => t('Initial Height (px)'),
    '#default_value' => theme_get_setting('rs_slideshow_boxedwidth_initial_height', 'ocms_education'),
    '#description'   => t('Set the initial height, in pixels.'),
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['revolution_slider_internal'] = array(
    '#type' => 'fieldset',
    '#title' => t('Internal Banner (Slider Revolution)'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['revolution_slider_internal']['rs_slideshow_internal_effect'] = array(
    '#type' => 'select',
    '#title' => t('Effects'),
    '#description'   => t('From the drop-down menu, select the slideshow effect you prefer.'),
    '#default_value' => theme_get_setting('rs_slideshow_internal_effect', 'ocms_education'),
    '#options' => array(
      'fade' => t('Fade'),
      'slideup' => t('Slide To Top'),
      'slidedown' => t('Slide To Bottom'),
      'slideright' => t('Slide To Right'),
      'slideleft' => t('Slide To Left'),
      'slidehorizontal' => t('Slide Horizontal'),
      'slidevertical' => t('Slide Vertical'),
      'boxslide' => t('Slide Boxes'),
      'slotslide-horizontal' => t('Slide Slots Horizontal'),
      'slotslide-vertical' => t('Slide Slots Vertical'),
      'boxfade' => t('Fade Boxes'),
      'slotfade-horizontal' => t('Fade Slots Horizontal'),
      'slotfade-vertical' => t('Fade Slots Vertical'),
      'fadefromright' => t('Fade and Slide from Right'),
      'fadefromleft' => t('Fade and Slide from Left'),
      'fadefromtop' => t('Fade and Slide from Top'),
      'fadefrombottom' => t('Fade and Slide from Bottom'),
      'fadetoleftfadefromright' => t('Fade To Left and Fade From Right'),
      'fadetorightfadefromleft' => t('Fade To Right and Fade From Left'),
      'fadetotopfadefrombottom' => t('Fade To Top and Fade From Bottom'),
      'fadetobottomfadefromtop' => t('Fade To Bottom and Fade From Top'),
      'parallaxtoright' => t('Parallax to Right'),
      'parallaxtoleft' => t('Parallax to Left'),
      'parallaxtotop' => t('Parallax to Top'),
      'parallaxtobottom' => t('Parallax to Bottom'),
      'scaledownfromright' => t('Zoom Out and Fade From Right'),
      'scaledownfromleft' => t('Zoom Out and Fade From Left'),
      'scaledownfromtop' => t('Zoom Out and Fade From Top'),
      'scaledownfrombottom' => t('Zoom Out and Fade From Bottom'),
      'zoomout' => t('ZoomOut'),
      'zoomin' => t('ZoomIn'),
      'slotzoom-horizontal' => t('Zoom Slots Horizontal'),
      'slotzoom-vertical' => t('Zoom Slots Vertical'),
      'curtain-1' => t('Curtain from Left'),
      'curtain-2' => t('Curtain from Right'),
      'curtain-3' => t('Curtain from Middle'),
      '3dcurtain-horizontal' => t('3D Curtain Horizontal'),
      '3dcurtain-vertical' => t('3D Curtain Vertical'),
      'cube' => t('Cube Vertical'),
      'cube-horizontal' => t('Cube Horizontal'),
      'incube' => t('In Cube Vertical'),
      'incube-horizontal' => t('In Cube Horizontal'),
      'turnoff' => t('TurnOff Horizontal'),
      'turnoff-vertical' => t('TurnOff Vertical'),
      'papercut' => t('Paper Cut'),
      'flyin' => t('Fly In'),
      'random-static' => t('Random Flat'),
      'random-premium' => t('Random Premium'),
      'random' => t('Random Flat and Premium/Default'),
    ),
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['revolution_slider_internal']['rs_slideshow_internal_effect_time'] = array(
    '#type' => 'textfield',
    '#title' => t('Effect duration (sec)'),
    '#default_value' => theme_get_setting('rs_slideshow_internal_effect_time', 'ocms_education'),
    '#description'   => t('Set the speed of animations, in seconds.'),
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['revolution_slider_internal']['rs_slideshow_internal_initial_height'] = array(
    '#type' => 'textfield',
    '#title' => t('Initial Height (px)'),
    '#default_value' => theme_get_setting('rs_slideshow_internal_initial_height', 'ocms_education'),
    '#description'   => t('Set the initial height, in pixels.'),
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['revolution_slider_internal']['rs_slideshow_internal_bullets_position'] = array(
    '#type' => 'select',
    '#title' => t('Navigation bullets position'),
    '#description'   => t('From the drop-down menu, select the position you prefer.'),
    '#default_value' => theme_get_setting('rs_slideshow_internal_bullets_position', 'ocms_education'),
    '#options' => array(
      'left' => t('Left'),
      'center' => t('Center'),
      'right' => t('Right'),
    ),
  );

  $form['mtt_settings']['google_maps_tab']['google_maps_settings'] = array(
    '#type' => 'details',
    '#title' => t('Google Maps Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['google_maps_tab']['google_maps_settings']['google_maps_key'] = array(
    '#type' => 'textfield',
    '#title' => t('Google Maps API Key'),
    '#description'   => t('Google requires an API key to be included to all calls to Google Maps API. Please create an API key and populate the above field.'),
    '#default_value' => theme_get_setting('google_maps_key','ocms_education'),
    '#size'          => 50,
    '#maxlength'     => 50,
  );

}

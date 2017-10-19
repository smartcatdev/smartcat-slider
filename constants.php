<?php

namespace scslider;

const VERSION = '1.0.0';

interface Options {

    /**
     * @since 1.0.0
     */
    const MIN_PHP_VERSION = 'min-php-version';
        
    /**
    * @since 1.0.0
    */
    const ACTIVE_POST_TYPES = 'scslider_active_post_types';
  
    /**
    * @since 1.0.0
    */
    const AUTO_ADVANCE = 'scslider_auto_advance';
    
    /**
    * @since 1.0.0
    */
    const NAVIGATION = 'scslider_navigation';
    
    /**
    * @since 1.0.0
    */
    const NAVIGATION_HOVER = 'scslider_navigation_hover';
    
    /**
    * @since 1.0.0
    */
    const OVERLAYER = 'scslider_overlayer';
    
    /**
    * @since 1.0.0
    */
    const PLAYPAUSE = 'scslider_playpause';
    
    /**
    * @since 1.0.0
    */
    const CLICKPAUSE = 'scslider_clickpause';
    
    /**
    * @since 1.0.0
    */
    const SLIDE_TRANS = 'scslider_slide_trans';
    
    /**
    * @since 1.0.0
    */
    const SLIDE_MOBILE_TRANS = 'scslider_slide_mobile_trans';
    
    /**
    * @since 1.0.0
    */
    const SLIDE_HEIGHT = 'scslider_slide_height';
    
    /**
    * @since 1.0.0
    */
    const SLIDE_MOBILE_HEIGHT = 'scslider_slide_mobile_height';
    
    /**
    * @since 1.0.0
    */
    const SLIDE_TIMER = 'scslider_slide_timer';
    
    /**
    * @since 1.0.0
    */
    const TRANS_TIMER = 'scslider_trans_timer';
      
    /**
    * @since 1.0.0
    */
    const PAGINATION = 'scslider_pagination';
      
    /**
    * @since 1.0.0
    */
    const LOADER = 'scslider_loader';
      
    /**
    * @since 1.0.0
    */
    const PIE_POSITION = 'scslider_pie_position';
      
    /**
    * @since 1.0.0
    */
    const BAR_POSITION = 'scslider_bar_position';
      
}
interface Defaults {
    
    /**
     * @since 1.0.0
     */
    const MIN_PHP_VERSION = 5.4;
      
    /**
    * @since 1.0.0
    */
    const AUTO_ADVANCE = 'true';
    
    /**
    * @since 1.0.0
    */
    const NAVIGATION = 'true';    
    
    /**
    * @since 1.0.0
    */
    const NAVIGATION_HOVER = 'true';    
    
    /**
    * @since 1.0.0
    */
    const OVERLAYER = 'true';
    
    /**
    * @since 1.0.0
    */
    const PLAYPAUSE = 'true';
    
    /**
    * @since 1.0.0
    */
    const CLICKPAUSE = 'true';
        
    /**
    * @since 1.0.0
    */
    const SLIDE_TRANS = 'random';
        
    /**
    * @since 1.0.0
    */
    const SLIDE_MOBILE_TRANS = 'simpleFade';
        
    /**
    * @since 1.0.0
    */
    const SLIDE_HEIGHT = '450';
            
    /**
    * @since 1.0.0
    */
    const SLIDE_MOBILE_HEIGHT = '450';
     
    /**
    * @since 1.0.0
    */
    const SLIDE_TIMER = '7000';
    
    /**
    * @since 1.0.0
    */
    const TRANS_TIMER = '1500';
    
    /**
    * @since 1.0.0
    */
    const PAGINATION = 'true';
    
    /**
    * @since 1.0.0
    */
    const LOADER = 'pie';
    
    /**
    * @since 1.0.0
    */
    const PIE_POSITION = 'rightTop';
    
    /**
    * @since 1.0.0
    */
    const BAR_POSITION = 'bottom';
    
}

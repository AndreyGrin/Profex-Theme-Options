<?php
function profex_AdminLiveInit() {
// Live Settings Script for admin

wp_register_script( 'admin-live-script', get_template_directory_uri() . '/includes/Profex-Theme-Option/js/admin-js.js');
wp_enqueue_script(  'admin-live-script' );

wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script('profex-tabs', get_template_directory_uri().'/includes/Profex-Theme-Option/js/tabs.js');

wp_enqueue_style( 'st-admin-style', get_template_directory_uri() . '/includes/Profex-Theme-Option/css/style.css' );
wp_enqueue_style( 'st-admin-tabs', get_template_directory_uri() . '/includes/Profex-Theme-Option/css/jquery-tabs.css' );
}
if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'theme_settings'){
    add_action('admin_footer', 'profex_AdminLiveInit');
}

add_action('admin_menu', 'profex_create_menu');

function profex_create_menu() {
    add_theme_page(__('Profex settings', 'profex'), __('Profex settings', 'profex'), 'edit_theme_options', 'theme_settings', 'profex_settings_page');

    add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
    register_setting( 'profex-settings-group', 'profex_options' );
}

function profex_OnlyNumbers($string) { echo preg_replace('/[^0-9]/', '', $string); }

function profex_text_check($array, $key) { 
    if(!isset($array[$key])){
        return '';
    } else {
        return $array[$key];
    }    
}

function profex_settings_page() {
$profex_options = get_option('profex_options');

function display_elements($elements, $profex_settings_array){
    foreach($elements as $element){
        switch($element['type']){
            case 'text':
            ?>
                <div class="rt_input rt_text">
                    <div class="rt_description">
                        <label for="profex_<?php echo $element['name']; ?>"><?php _e($element['title'], 'profex'); ?></label>
                        <div class="rt_clearfix"></div>
                    </div>
                    <input name="profex_options[<?php echo $element['name']; ?>]" id="profex_<?php echo $element['name']; ?>" type="text" value="<?php echo esc_attr(profex_text_check($profex_settings_array, $element['name'])); ?>" />
                    <div class="rt_clearfix"></div>
                </div>
            <?php
            break;
            
            case 'spliter':
            ?>
                <h3><?php _e($element['title'], 'profex'); ?></h3>
            <?php
            break;
            
            case 'textarea':
            ?>
                <div class="rt_input rt_text">
                    <div class="rt_description">
                        <label for="profex_<?php echo $element['name']; ?>"><?php _e($element['title'], 'profex'); ?></label>
                        <div class="rt_clearfix"></div>
                    </div>
                    <textarea name="profex_options[<?php echo $element['name']; ?>]" id="profex_<?php echo $element['name']; ?>" cols="30" rows="10">
                        <?php echo esc_attr(profex_text_check($profex_settings_array, $element['name'])); ?>
                    </textarea>
                    <div class="rt_clearfix"></div>
                </div>
            <?php
            break;
            
            case 'pages':
            ?>
                <div class="rt_input rt_text">
                    <div class="rt_description">
                        <label for="profex_<?php echo $element['name']; ?>"><?php _e($element['title'], 'profex'); ?></label>
                        <div class="rt_clearfix"></div>
                    </div>
                    <select name="profex_options[<?php echo $element['name']; ?>]" id="profex_page_id">
                        <option value="none" default="default"><?php _e('Выберите страницу', 'profex'); ?></option>
                        <?php    
                            $opt = $profex_settings_array[$element['name']];
                            $pages = get_pages('orderby=name');
                            foreach ($pages as $page ) {
                                echo '<option value="'.$page->ID.'" '.selected( $opt, $page->ID ).' >'.$page->post_title.'</option>';
                            }
                        ?>
                    </select>
                    <div class="rt_clearfix"></div>
                </div> 
            <?php
            break;
        }
    }
}
?>
<div class="rt_wrap">
    <h2 id="rt_title"><?php _e('Theme settings', 'profex'); ?></h2>

    <form method="post" action="options.php" class="rt_opts" id="toeThemeEditOptionForm">

    <?php settings_fields('profex-settings-group'); ?>
    <div id="rt_tabs">
        <ul>
            <li class="rt_general"><a href="#rt_general"><?php _e('Home page', 'profex'); ?></a></li>
            <li class="rt_social"><a href="#rt_social"><?php _e('Social profiles', 'profex'); ?></a></li>
            <li class="rt_footer"><a href="#rt_footer"><?php _e('Footer', 'profex'); ?></a></li>
        </ul>
                
        <div id="rt_general">
            <?php 
                $elements_general = Array(
                    '0' => Array(
                        'title'  => 'Header',
                        'type'   => 'spliter',
                    ),
                    
                    '1' => Array(
                        'title'  => 'Some link',
                        'name'   => 'link',
                        'type'   => 'text',
                    ),
                    
                    '2' => Array(
                        'title'  => 'Header phone',
                        'name'   => 'phone',
                        'type'   => 'text',
                    ),
                    
                    '3' => Array(
                        'title'  => 'Home page content',
                        'type'   => 'spliter',
                    ),
                    
                    '4' => Array(
                        'title'  => 'Choose a page for special block on home',
                        'name'   => 'special_page_id',
                        'type'   => 'pages',
                    ),
                );
                
                display_elements($elements_general, $profex_options);
            ?>  
        </div>        
                
        <div id="rt_social">
            <?php 
                $elements_social = Array(
                    '0' => Array(
                        'title'  => 'Links to social profiles',
                        'type'   => 'spliter',
                    ),
                    
                    '1' => Array(
                        'title'  => 'Facebook',
                        'name'   => 'facebook',
                        'type'   => 'text',
                    ),
                    
                    '2' => Array(
                        'title'  => 'Twitter',
                        'name'   => 'twitter',
                        'type'   => 'text',
                    ),
                    
                    '3' => Array(
                        'title'  => 'Instagram',
                        'name'   => 'instagram',
                        'type'   => 'text',
                    ),
                    
                    '4' => Array(
                        'title'  => 'LiveJournal',
                        'name'   => 'livejournal',
                        'type'   => 'text',
                    ),
                );
                
                display_elements($elements_social, $profex_options);
            ?>
        </div>         
        <div id="rt_footer">
            <?php 
                $elements_footer = Array(
                    '0' => Array(
                        'title'  => 'Copyright text',
                        'name'   => 'copyright',
                        'type'   => 'text',
                    ),
                );
                
                display_elements($elements_footer, $profex_options);
            ?>          
        </div>
        
        <div class="clear"></div>
        <div id="toeThemeEditFormMsg"><?php if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == true) _e('Complete', 'profex'); ?></div>
        <p class="submit">
            <input type="submit" style="margin-left:17px;" class="button-primary" value="<?php _e('Save settings', 'profex') ?>"/>
        </p>
    </div>

    </form>
</div>

<?php 
} 
?>

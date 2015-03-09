<?php
function profex_AdminLiveInit() {
// Live Settings Script for admin

wp_enqueue_script( 'admin-live-script', get_template_directory_uri() . '/includes/Profex-Theme-Options/js/admin-js.js');

wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_enqueue_script('profex-tabs', get_template_directory_uri().'/includes/Profex-Theme-Options/js/tabs.js');

wp_enqueue_style( 'st-admin-style', get_template_directory_uri() . '/includes/Profex-Theme-Options/css/style.css' );
wp_enqueue_style( 'st-admin-tabs', get_template_directory_uri() . '/includes/Profex-Theme-Options/css/jquery-tabs.css' );
}
if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'theme_settings'){
    add_action('admin_footer', 'profex_AdminLiveInit');
}

add_action('admin_menu', 'profex_create_menu');

function profex_create_menu() {
    add_theme_page(__('Profex settings', 'profex'), __('Profex settings', 'profex'), 'edit_theme_options', 'theme_settings', 'profex_settings_page');

    add_action( 'admin_init', 'register_profex_settings' );
}

function register_profex_settings() {
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
            case 'spliter':
            ?>
                <h3><?php _e($element['title'], 'profex'); ?></h3>
            <?php
            break;
            
            case 'text':
            ?>
                <div class="rt_input rt_text">
                    <div class="rt_description">
                        <label for="profex_<?php echo $element['name']; ?>"><?php _e($element['title'], 'profex'); ?></label>
                        <?php if($element['description']) echo '<small>'.$element['description'].'</small>'; ?>
                        <div class="rt_clearfix"></div>
                    </div>
                    <input name="profex_options[<?php echo $element['name']; ?>]" id="profex_<?php echo $element['name']; ?>" type="text" value="<?php echo esc_attr(profex_text_check($profex_settings_array, $element['name'])); ?>" />
                    <div class="rt_clearfix"></div>
                </div>
            <?php
            break;
            
            case 'checkbox':
            ?>
                <div class="rt_input rt_text">
                    <div class="rt_description">
                        <label for="profex_<?php echo $element['name']; ?>"><?php _e($element['title'], 'profex'); ?></label>
                        <?php if($element['description']) echo '<small>'.$element['description'].'</small>'; ?>
                        <div class="rt_clearfix"></div>
                    </div>
                    <input name="profex_options[<?php echo $element['name']; ?>]" id="profex_<?php echo $element['name']; ?>" type="checkbox" value="1" <?php checked(profex_text_check($profex_settings_array, $element['name']) , 1); ?> />
                    <div class="rt_clearfix"></div>
                </div>
            <?php
            break;
            
            case 'textarea':
            ?>
                <div class="rt_input rt_text">
                    <div class="rt_description">
                        <label for="profex_<?php echo $element['name']; ?>"><?php _e($element['title'], 'profex'); ?></label>
                        <?php if($element['description']) echo '<small>'.$element['description'].'</small>'; ?>
                        <div class="rt_clearfix"></div>
                    </div>
                    <textarea name="profex_options[<?php echo $element['name']; ?>]" id="profex_<?php echo $element['name']; ?>" cols="30" rows="10"><?php echo esc_attr(profex_text_check($profex_settings_array, $element['name'])); ?></textarea>
                    <div class="rt_clearfix"></div>
                </div>
            <?php
            break;
            
            case 'pages':
            ?>
                <div class="rt_input rt_text">
                    <div class="rt_description">
                        <label for="profex_<?php echo $element['name']; ?>"><?php _e($element['title'], 'profex'); ?></label>
                        <?php if($element['description']) echo '<small>'.$element['description'].'</small>'; ?>
                        <div class="rt_clearfix"></div>
                    </div>
                    <select name="profex_options[<?php echo $element['name']; ?>]" id="profex_<?php echo $element['name']; ?>">
                        <option value="none" default="default"><?php _e('Select a page', 'profex'); ?></option>
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
            
            case 'categories':
            ?>
                <div class="rt_input rt_text">
                    <div class="rt_description">
                        <label for="profex_<?php echo $element['name']; ?>"><?php _e($element['title'], 'profex'); ?></label>
                        <?php if(isset($element['description']) && $element['description'] != '') echo '<small>'.$element['description'].'</small>'; ?>
                        <div class="rt_clearfix"></div>
                    </div>
                    <select name="profex_options[<?php echo $element['name']; ?>]" id="profex_<?php echo $element['name']; ?>">
                        <option value="none" default="default"><?php _e('Select a category', 'profex'); ?></option>
                        <?php    
                            $opt = $profex_settings_array[$element['name']];
                            $cats = get_categories('orderby=name');
                            foreach ($cats as $cat ) {
                                echo '<option value="'.$cat->cat_ID.'" '.selected( $opt, $cat->cat_ID ).' >'.$cat->cat_name.'</option>';
                            }
                        ?>
                    </select>
                    <div class="rt_clearfix"></div>
                </div> 
            <?php
            break;
            
            case 'editor':
            ?>
                <div class="rt_input rt_text">
                    <div class="rt_description">
                        <label for="multi_<?php echo $element['name']; ?>"><?php _e($element['title'], 'multi'); ?></label>
                        <div class="rt_clearfix"></div>
                    </div>
                    <?php 
                        $value = multi_text_check($multi_settings_array, $element['name']);
                        wp_editor( $value,$element['name'], array ('textarea_rows' => 6, 'textarea_name' => 'profex_options['.$element['name'].']'));
                    ?>
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
                        'title'  => 'Text heading, using like spliter',
                        'type'   => 'spliter',
                    ),
                    
                    '1' => Array(
                        'title'  => 'Text example',
                        'name'   => 'test_text',
                        'type'   => 'text',
                        'description'   => 'Text, why you need to write here something',
                    ),
                    
                    '2' => Array(
                        'title'  => 'Textarea example',
                        'name'   => 'test_textarea',
                        'type'   => 'textarea',
                    ),
                    
                    '3' => Array(
                        'title'  => 'Page selector example',
                        'name'   => 'test_page',
                        'type'   => 'pages',
                    ),
                    
                    '4' => Array(
                        'title'  => 'Categories selector example',
                        'name'   => 'test_categories',
                        'type'   => 'categories',
                    ),
                    
                    '5' => Array(
                        'title'  => 'Checkbox example',
                        'name'   => 'test_checkbox',
                        'type'   => 'checkbox',
                    ),
                );
                
                display_elements($elements_general, $profex_options);
            ?>  
        </div>        
                
        <div id="rt_social">
            <p>Heres you can write your fields</p>
        </div>         
        <div id="rt_footer">
            <p>Heres you can write your fields</p>         
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

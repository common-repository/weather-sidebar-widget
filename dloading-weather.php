<?php
/*
Plugin Name:  Weather sidebar widget
Plugin URI:   http://dloading.com/api/weather/
Description:  weather widget for your sidebar with advanced options
Version:      1.0
Author:       Dloading
Author URI:   http://dloading.com/	
*/


	function dl_weather_widget($args, $widget_args = 1) {
		
		extract( $args, EXTR_SKIP );
		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
	
		$dl_weather_options = get_option('dloading_weather_widget');
		if ( !isset($dl_weather_options[$number]) ) 
		return;

		$title = $dl_weather_options[$number]['title']; 		// single value
		$dl_weather_location = $dl_weather_options[$number]['dl_weather_location']; 		// single value
		$dl_weather_shell = $dl_weather_options[$number]['dl_weather_shell']; 		// single value
		$dl_weather_background = $dl_weather_options[$number]['dl_weather_background']; 		// single value
		$dl_weather_language = $dl_weather_options[$number]['dl_weather_language']; 	// single value
		$dl_weather_credit = $dl_weather_options[$number]['dl_weather_credit']; 	// single value		
			
		echo $before_widget; // start widget display code ?>
<style type="text/css">.dloading-widget{position:relative;font-size:12px!important;font-family:"lucida grande",lucida,tahoma,helvetica,arial,sans-serif!important;zoom:1;}*{margin:0;padding:0;}.dloading-doc{overflow:hidden;width:100%;text-align:left;font-weight:normal;-moz-border-radius:5px;-webkit-border-radius:5px;border-radius:5px;}.dloading-hd{padding:10px;position:relative;zoom:1;overflow:hidden;}.dloading-widget h3{font-size:11px!important;font-weight:normal!important;}.dloading-widget h3,.dloading-widget h4,.dloading-widget p{margin:0!important;padding:0!important;line-height:1.2!important;width:auto!important;}h3 {  margin: 10px 0 5px;  font-size: 20px;}h1, h2, h3, h4, h5, h6 {  margin: 0;  padding: 0;  font-weight: normal;  line-height: 1;}.dloading-widget h4{font-size:16px!important;}h4 {  margin: 14px 0 5px;  font-size: 16px;}.dloading-bd {color: #444;}.dloading-bd{padding:0 1px;}.dloading-ft{position:relative;}.dloading-ft div{overflow:hidden;padding:10px;zoom:1;}.dloading-doc a{text-decoration:none!important;}.dloading-ft a{float:left;display:block;}a {  color: #2D76B9;}a{text-decoration:none;color:#2276BB;}.dloading-ft a img{position:relative;top:2px;}.dloading-ft span{float:right;text-align:right;}</style><style type="text/css">.dloading-ft span{float:right;text-align:right;}.dloading-doc {background-color: #<?=$dl_weather_shell?>;  color: fff; }.dloading-timeline {background: #fff;}</style><div class="dloading-widget"><div class="dloading-doc" style="width: 250px;"><div class="dloading-hd"><h4 class style="color: #FFFFFF;"> <?=$title?></h4></div><div class="dloading-bd"><div class="dloading-timeline" style="height: 360px;"><div class="dloading-dlgs"><div class="dloading-reference-dlg"></div><!-- dlgs show here --> <IFRAME SRC="http://dloading.com/api/weather/weather.php?lang=<?=$dl_weather_language?>&amp;location=<?=$dl_weather_location?>" WIDTH=248 HEIGHT=360 SCROLLING=no name="Weather" border="0" frameborder="0" style="background-color: #<?=$dl_weather_background?>"></IFRAME></div></div></div><div class="dloading-ft"><div><?php if ($dl_weather_credit == 'yes') { echo '<a target="_blank" href="http://dloading.com/"><strong>Dloading</strong></a><span><a target="_blank" class="dloading-join-conv" style="color: rgb(255, 255, 255);" href="http://dloading.com/api/weather/">Get Your Widget Now</a></span>'; } elseif ($dl_weather_credit == 'no') { echo ''; } ?></div></div></div></div>		

			
	<?php echo $after_widget; // end widget display code
	
	}
	
	
	function dl_weather_widget_control($widget_args) {
	
		global $wp_registered_widgets;
		static $updated = false;
	
		if ( is_numeric($widget_args) )
			$widget_args = array( 'number' => $widget_args );			
		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
		extract( $widget_args, EXTR_SKIP );
	
		$dl_weather_options = get_option('dloading_weather_widget');
		
		if ( !is_array($dl_weather_options) )	
			$dl_weather_options = array();
	
		if ( !$updated && !empty($_POST['sidebar']) ) {
		
			$sidebar = (string) $_POST['sidebar'];	
			$sidebars_widgets = wp_get_sidebars_widgets();
			
			if ( isset($sidebars_widgets[$sidebar]) )
				$this_sidebar =& $sidebars_widgets[$sidebar];
			else
				$this_sidebar = array();
	
			foreach ( (array) $this_sidebar as $_widget_id ) {
				if ( 'dl_weather_widget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
					$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
					if ( !in_array( "dl-weather-widget-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.
						unset($dl_weather_options[$widget_number]);
				}
			}
	
			foreach ( (array) $_POST['dl-weather-widget'] as $widget_number => $dloading_weather_widget ) {
				if ( !isset($dloading_weather_widget['title']) && isset($dl_weather_options[$widget_number]) ) // user clicked cancel
					continue;
				
				$title = strip_tags(stripslashes($dloading_weather_widget['title']));
				$dl_weather_location = strip_tags(stripslashes($dloading_weather_widget['text_value']));				
				$dl_weather_shell = strip_tags(stripslashes($dloading_weather_widget['text_value1']));				
				$dl_weather_background = strip_tags(stripslashes($dloading_weather_widget['text_value2']));				
				$dl_weather_language = $dloading_weather_widget['select_value'];
				$dl_weather_credit = $dloading_weather_widget['select_value1'];				
				
				// Pact the values into an array
				$dl_weather_options[$widget_number] = compact( 'title', 'dl_weather_location', 'dl_weather_shell', 'dl_weather_background', 'dl_weather_language', 'dl_weather_credit' );
			}
	
			update_option('dloading_weather_widget', $dl_weather_options);
			$updated = true;
		}
	
		if ( -1 == $number ) { // if it's the first time and there are no existing values
	
			$title = '';
			$dl_weather_location = '';
			$dl_weather_shell = '29A2FF';
			$dl_weather_background = '5ECFFF';
			$dl_weather_language = 'en';
			$dl_weather_credit = '';			
			$number = '%i%';
			
		} else { // otherwise get the existing values
		
			$title = attribute_escape($dl_weather_options[$number]['title']);
			$dl_weather_location = attribute_escape($dl_weather_options[$number]['dl_weather_location']); // attribute_escape used for security
			$dl_weather_shell = attribute_escape($dl_weather_options[$number]['dl_weather_shell']); // attribute_escape used for security
			$dl_weather_background = attribute_escape($dl_weather_options[$number]['dl_weather_background']); // attribute_escape used for security
			$dl_weather_language = $dl_weather_options[$number]['dl_weather_language'];
			$dl_weather_credit = $dl_weather_options[$number]['dl_weather_credit'];			
		}
		$link = '<a target=\"_blank\" href=\"http://dloading.com/\"><strong>Dloading</strong></a><span><a target=\"_blank\" class=\"dloading-join-conv\" style=\"color: rgb(255, 255, 255);\" href=\"http://dloading.com/api/weather/\">Get Your Widget Now</a></span>';
	//	print_r($dl_weather_options[$number]);
	?>
	<p>Note: You may need to refresh before editing your setting to load all Functions.</p>
	<p><label>Title</label><br /><input id="title_value_<?php echo $number; ?>" name="dl-weather-widget[<?php echo $number; ?>][title]" type="text" size="30" value="<?=$title?>" /></p>
    <p>
        <label>Language 
        <select id="select_value_<?php echo $number; ?>" name="dl-weather-widget[<?php echo $number; ?>][select_value]">
            <option <?php if ($dl_weather_language == 'af') echo 'af'; ?> value="af">Afrikaans</option>
            <option <?php if ($dl_weather_language == 'ar') echo 'selected'; ?> value="ar">Arabic</option>
            <option <?php if ($dl_weather_language == 'eu') echo 'selected'; ?> value="eu">Basque</option>
            <option <?php if ($dl_weather_language == 'tw') echo 'selected'; ?> value="tw">Chinese</option>
			<option <?php if ($dl_weather_language == 'da') echo 'selected'; ?> value="da">Danish</option>
			<option <?php if ($dl_weather_language == 'nl') echo 'selected'; ?> value="nl">Dutch</option>
			<option <?php if ($dl_weather_language == 'en') echo 'selected'; ?> value="en">English</option>
			<option <?php if ($dl_weather_language == 'tl') echo 'selected'; ?> value="tl">Filipino</option>
			<option <?php if ($dl_weather_language == 'fr') echo 'selected'; ?> value="fr">French</option>
			<option <?php if ($dl_weather_language == 'de') echo 'selected'; ?> value="de">German</option>
			<option <?php if ($dl_weather_language == 'id') echo 'selected'; ?> value="id">Indonesian</option>
			<option <?php if ($dl_weather_language == 'it') echo 'selected'; ?> value="it">Italian</option>
			<option <?php if ($dl_weather_language == 'no') echo 'selected'; ?> value="no">Norwegian</option>
			<option <?php if ($dl_weather_language == 'fa') echo 'selected'; ?> value="fa">Persian</option>
			<option <?php if ($dl_weather_language == 'pt') echo 'selected'; ?> value="pt">Portuguese</option>
			<option <?php if ($dl_weather_language == 'es') echo 'selected'; ?> value="es">Spanish</option>
			<option <?php if ($dl_weather_language == 'tr') echo 'selected'; ?> value="tr">Turkish</option>
        </select>
        </label>
    </p>
	
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.5.2.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.js"></script>
  <script type="text/javascript" src="http://area-autocomplete.googlecode.com/files/locations.js"></script>  
  <script type="text/javascript" src="http://area-autocomplete.googlecode.com/files/jscolors.js"></script>    
  <style type='text/css'>
  .ui-autocomplete {
    background: rgba(248,248,248,1);
    width: 50px;
    padding: 0;
    border: 1px solid rgba(190,190,190,1);
    list-style-type: none;
    padding-left: 0px;
    height: auto;
    float: left;
    box-shadow: 0 5px 10px rgba(30,30,30,0.1);
    -moz-box-shadow: 0 5px 10px rgba(30,30,30,0.1);
    -webkit-box-shadow:  0 5px 10px rgba(30,30,30,0.1);
}
  
  
  .ui-autocomplete li {    width: 100%;    float: left;    list-style-type: none;}.ui-autocomplete li a:hover, .ui-autocomplete li a:focus, .ui-autocomplete li a.ui-state-hover  {    background: rgba(230,230,230,1);    cursor: pointer;}</style>
 	
    <p><label>Location</label><br /><input id="text_value_<?php echo $number; ?>" name="dl-weather-widget[<?php echo $number; ?>][text_value]" place="location" type="text" size="30" value="<?=$dl_weather_location?>" placeholder="Location" autocomplete="off"/></p>
	<p><label>Shell Background</label><br /><input id="text_value_<?php echo $number; ?>" name="dl-weather-widget[<?php echo $number; ?>][text_value1]" type="text" size="30" value="<?=$dl_weather_shell?>" class="color"/></p>
	<p><label>Weather Background</label><br /><input id="text_value_<?php echo $number; ?>" name="dl-weather-widget[<?php echo $number; ?>][text_value2]" type="text" size="30" value="<?=$dl_weather_background?>" class="color"/></p>

    <p>
        <label>Enable Credit (Yes Recommended) <br />
        <select id="select_value_<?php echo $number; ?>" name="dl-weather-widget[<?php echo $number; ?>][select_value1]">
            <option <?php if ($dl_weather_credit == 'yes') echo 'yes'; ?> value="yes">Yes</option>
            <option <?php if ($dl_weather_credit == 'no') echo 'selected'; ?> value="no">No</option>
        </select>
        </label>
    </p>


	
    <input type="hidden" name="dl-weather-widget[<?php echo $number; ?>][submit]" value="1" />
    
	<?php
	}
	
	
	function dl_weather_widget_register() {
		if ( !$dl_weather_options = get_option('dloading_weather_widget') )
			$dl_weather_options = array();
		$widget_ops = array('classname' => 'dloading_weather_widget', 'description' => __('weather widget for your sidebar with advanced options'));
		$control_ops = array('width' => 250, 'height' => 350, 'id_base' => 'dl-weather-widget');
		$name = __('Weather sidebar widget');
	
		$id = false;
		
		foreach ( (array) array_keys($dl_weather_options) as $o ) {
	
			if ( !isset( $dl_weather_options[$o]['title'] ) )
				continue;
						
			$id = "dl-weather-widget-$o";
			wp_register_sidebar_widget($id, $name, 'dl_weather_widget', $widget_ops, array( 'number' => $o ));
			wp_register_widget_control($id, $name, 'dl_weather_widget_control', $control_ops, array( 'number' => $o ));
		}
		
		if ( !$id ) {
			wp_register_sidebar_widget( 'dl-weather-widget-1', $name, 'dl_weather_widget', $widget_ops, array( 'number' => -1 ) );
			wp_register_widget_control( 'dl-weather-widget-1', $name, 'dl_weather_widget_control', $control_ops, array( 'number' => -1 ) );
		}
	}

add_action('init', dl_weather_widget_register, 1);

?>
<?php
$wp_include = "../wp-load.php";
$i = 0;
while (!file_exists($wp_include) && $i++ < 10) {
  $wp_include = "../$wp_include";
}

// load WordPress
require($wp_include);

if (! current_user_can ( 'edit_posts' ) && ! current_user_can ( 'edit_pages' )) {
	//wp_die('Oops');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Easy Insert Quran</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
    
    <script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>js/jquery/jquery.js"></script>    
	<base target="_self" />
</head>
<body id="link">
<form name="eiq-form" action="#" id="eiq-form">	

	<div class="eiq-mce-body">
		<?php
		$request_url = EIQ_PLUGIN_URL.'/assets/data/surah.json';		
		$request = wp_remote_get($request_url);
		$surahs = '';
		if( !is_wp_error( $request ) ) {
			$body = wp_remote_retrieve_body( $request );
			$surahs = json_decode( $body );
			$surahs = $surahs->data;
		}
		// echo '<pre>';
		// var_dump($surahs);
		?>
		<div class="tab-buttons">
			<ul>
				<li><a>Quran</a></li>
				<li><a>Translation</a></li>
				<li><a>Recitation</a></li>
			</ul>
		</div>
		
		<div class="tab-content-wrap">
			
			<div id="quran" class="tab-content">
				<div class="field-wrap">
					<label>Surah:</label>
					<select id="select-surah" name="surah">
						<?php if(!empty($surahs)): foreach($surahs as $surah): ?>
							<option value="<?php echo $surah->number; ?>" data-max="<?php echo $surah->numberOfAyahs; ?>"><?php echo $surah->number .'. '.$surah->name; ?></option>
						<?php endforeach; endif; ?>						
					</select>
				</div>
				
				<div class="field-wrap">
					<label>Ayah Start:</label>
					<select id="ayah-start" name="ayah-start">
						<option value="1">1</option>						
						<option value="2">2</option>						
						<option value="3">3</option>						
						<option value="4">4</option>						
						<option value="5">5</option>						
						<option value="6">6</option>						
						<option value="7">7</option>						
					</select>
				</div>
				
				<div class="field-wrap">
					<label>Ayah End:</label>
					<select id="ayah-end" name="ayah-end">
						<option value="1">1</option>						
						<option value="2">2</option>						
						<option value="3">3</option>						
						<option value="4">4</option>						
						<option value="5">5</option>						
						<option value="6">6</option>						
						<option value="7">7</option>
					</select>
				</div>
			</div>
			
			
		</div>
		
	</div>	
	
	<div class="eiq-mce-footer">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="<?php _e('Cancel', 'wp-shortcode'); ?>" onClick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="<?php _e('Insert', 'wp-shortcode'); ?>" onClick="eiq_submit_shortcode();" />
		</div>
	</div>	
	
</form>
<script>
	
	jQuery(function($){
		
		$('#select-surah').change(function(){
			var selected = $(this).find('option:selected');
			var max = selected.data('max');
			$('#ayah-start option, #ayah-end option').remove();
			var i;
			for (i = 1; i <= max; i++) {
				$('#ayah-start, #ayah-end').append('<option value="'+i+'">'+i+'</option>');
			} 
		});
		
		$('#ayah-start').change(function(){
			var theVal = $(this).val();			
			$('#ayah-end').val(theVal).find('option').show().removeAttr('disabled');
			var i;
			for (i = 1; i < theVal; i++) {
				$('#ayah-end option:eq('+(i-1)+')').attr('disabled','disabled').hide();
			}
			
			
		});
	});

	function eiq_submit_shortcode() {		
		if(window.tinyMCE) {
			tinyMCEPopup.editor.insertContent('asdf');
			tinyMCEPopup.editor.execCommand('mceRepaint');
			tinyMCEPopup.close();
		}
		return;
	}
</script>
</body>
</html>
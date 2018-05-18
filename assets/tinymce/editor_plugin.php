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
	
	<style>
	.tab-buttons li,
	.tab-buttons ul {
		margin: 0;
		padding: 0;
	}
	.tab-buttons ul {
		padding-left: 1px;
	}
	.tab-buttons li {
		list-style: none;
		display: inline-block;
		margin-left: -1px;
	}
	.tab-buttons li a {
		background: #ededed;
		display: block;
		padding: 5px 12px;
		border: 1px solid #cbcbcb;
		cursor: pointer;
		font-weight: 700;
	}
	.tab-buttons li a.active-btn {
		background: #fff;
		border-bottom: 0;
		padding-bottom: 6px;
	}
	.tab-content-wrap {
		background: #fff;
		border: 1px solid #cbcbcb;
		margin-top: -1px;
		padding: 10px 10px;
	}
	.tab-content {
		display: none;
		height: 100px;
	}
	.active-tab-content {
		display: block;
	}
	.field-wrap {
		padding: 0 0 8px;
	}
	.field-wrap label {
		display: block;
		float: left;
		height: 27px;
		line-height: 27px;
		width: 135px;
		text-align: right;
	}
	.field-wrap .field-control {
		margin-left: 135px;
		padding-left: 10px;
	}
	.field-wrap .field-control select {
		width: 100%;
		height: 27px;
		padding-left: 4px;
	}
	.eiq-mce-footer {
		padding-top: 10px;
	}
	#ayah-start,
	#ayah-end {
		width: 65px;
	}
	#insert,
	#cancel {
		padding: 0 12px;
		height: 30px;
		line-height: 28px;
	}
	option[value="no"] {
		background: #fce29c;
	}	
	option[value=""] {
		background: #b4ebff;
		
	}
	</style>
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
		
		$request_url = EIQ_PLUGIN_URL.'/assets/data/edition.json';		
		$request = wp_remote_get($request_url);
		$editions = '';
		if( !is_wp_error( $request ) ) {
			$body = wp_remote_retrieve_body( $request );
			$editions = json_decode( $body );
			$editions = $editions->data;
		}
		// echo '<pre>';
		// var_dump($surahs);
		?>
		<div class="tab-buttons">
			<ul>
				<li><a class="active-btn" data-tab="quran">Quran</a></li>
				<li><a data-tab="translation">Translation</a></li>
				<li><a data-tab="recitation">Recitation</a></li>
			</ul>
		</div>
		
		<div class="tab-content-wrap">
			
			<div id="quran" class="tab-content active-tab-content">
				<div class="field-wrap">
					<label>Surah:</label>
					<div class="field-control">
					<select id="select-surah" name="surah">
						<?php if(!empty($surahs)): foreach($surahs as $surah): ?>
							<option value="<?php echo $surah->number; ?>" data-max="<?php echo $surah->numberOfAyahs; ?>"><?php echo $surah->number .'. '.$surah->name; ?></option>
						<?php endforeach; endif; ?>						
					</select>
					</div>
				</div>
				
				<div class="field-wrap">
					<label>Ayah Start:</label>
					<div class="field-control">
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
				</div>
				
				<div class="field-wrap">
					<label>Ayah End:</label>
					<div class="field-control">
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
			
			<div id="translation" class="tab-content">
				<div class="field-wrap">
					<label>Translation Language:</label>
					<div class="field-control">
					<select id="trans-lang" name="ayah-end">
						<option value="">- Defaut Setting -</option>						
						<option value="no">- Disable Translation -</option>
						<?php
						$lang_list = array();
						if(!empty($editions)):
							foreach($editions as $ed):
								if($ed->type == 'translation' && $ed->format == 'text'):
									$language = eiq_get_display_language($ed->language);
									if(!isset($lang_list[$language])):
										$lang_list[$language] = array();
									endif;
									
									$lang_list[$language][] = array(
										'identifier' => $ed->identifier,
										'name' => $ed->name,
									);
								endif;
							
							endforeach;

							ksort($lang_list);
						endif;
						?>
						
						<?php foreach($lang_list as $language => $versions): ?>
							<optgroup label="<?php echo $language; ?>">				
								
								<?php foreach($versions as $ver): ?>
									<option value="<?php echo $ver['identifier']; ?>"><?php echo $ver['name']; ?></option>
								<?php endforeach; ?>
								
							</optgroup>
						<?php endforeach; ?>					
						
					</select>
					</div>
				</div>
			</div>
			
			<div id="recitation" class="tab-content">
				<div class="field-wrap">
					<label>Reciter:</label>
					<div class="field-control">
					<select id="reciter" name="ayah-end">
						<option value="">- Defaut Setting -</option>						
						<option value="no">- Disable Recitation -</option>
						<?php if(!empty($editions)): foreach($editions as $ed): if($ed->format == 'audio' && $ed->language == 'ar'): ?>
							<option value="<?php echo $ed->identifier; ?>"><?php echo $ed->name; ?></option>
						<?php endif; endforeach; endif; ?>						
					</select>
					</div>
				</div>
			</div>
			
			
		</div>
		
	</div>	
	
	<div class="eiq-mce-footer">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="<?php _e('Cancel', 'easy-insert-quran'); ?>" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="<?php _e('Insert', 'easy-insert-quran'); ?>" />
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
				$('#ayah-start, #ayah-end').append('<option value="' + i + '">'+i+'</option>');
			}
		});
		
		$('#ayah-start').change(function(){
			var theVal = parseInt($(this).val());			
			var endVal = parseInt($('#ayah-end').val());
			
			$('#ayah-end option').show().removeAttr('disabled');
			
			if(theVal >= endVal) {
				$('#ayah-end').val(theVal);
			} else {
				
			}
			
			
			var i;
			for (i = 1; i < theVal; i++) {
				$('#ayah-end option:eq(' + (i-1) + ')').attr('disabled','disabled').hide();
			}	
			
		});
		
		$('#cancel').click(function(){
			tinyMCEPopup.close();
		});
		
		$('.tab-buttons li a').click(function(){
			if(!$(this).hasClass('active-btn')) {
				$('.tab-buttons li a').removeClass('active-btn');
				$(this).addClass('active-btn');
				
				var tab = $(this).data('tab');
				$('.tab-content').removeClass('active-tab-content');
				$('#'+tab).addClass('active-tab-content');
				
			}
			return false;
		});
		
		$('#insert').click(function(){
			if(window.tinyMCE) {		
				var shortcode = '';
				var surah = $('#select-surah').val();
				var ayah = $('#ayah-start').val();
				var ayahEnd = $('#ayah-end').val();
				var lang = $('#trans-lang').val();
				var reciter = $('#reciter').val();
				
				if(ayah && surah) {
					shortcode = '[insert_quran surah="' + surah + '" ayah="' + ayah + '"';
					if(ayahEnd && ayahEnd > ayah) {
						shortcode += ' end="' + ayahEnd + '"';
					}
					if(lang) {
						shortcode += ' translation="' + lang + '"';
					}
					if(reciter) {
						shortcode += ' reciter="' + reciter + '"';
					}
					shortcode += ']';
				}				
				
				tinyMCEPopup.editor.insertContent(shortcode);
				tinyMCEPopup.editor.execCommand('mceRepaint');
				tinyMCEPopup.close();
			}
			return;
		});	
		
	});

	
</script>
</body>
</html>
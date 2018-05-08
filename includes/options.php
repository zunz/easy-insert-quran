<?php
class EIQ_Options_Page {	
	
    function __construct() {         
        add_action( 'admin_menu', array( $this, 'admin_options' ) );
		add_action( 'admin_init', array( $this, 'eiq_options_init' ) );
    }
	  
    function eiq_options_init() {
		
		/* Register Settings */
		register_setting( 'eiq_optgroup', 'eiq_settings' );	

		/* Set Default Value */
		if(!get_option('eiq_settings')) {
			update_option('eiq_settings', array(
				'translation' => 'disabled',
				'reciter' => 'disabled',
			));
		}
		
		
		add_settings_section(
			'eiq_section_translation',
			__( 'Translation', 'easy-insert-quran' ),
			'__return_false',
			'eiq_optgroup'
		);		
		
		add_settings_field(
			'eiq_translation_field',
			__( 'Translation Language', 'easy-insert-quran' ),
			array( $this, 'translation_lang_cb' ),
			'eiq_optgroup',
			'eiq_section_translation',
			array(
				'slug' => 'translation'
			)
		);
		
		add_settings_section(
			'eiq_section_recitation',
			__( 'Recitation', 'easy-insert-quran' ),
			'__return_false',
			'eiq_optgroup'
		);
		
		add_settings_field(
			'eiq_reciter_field',
			__( 'Reciter', 'easy-insert-quran' ),
			array( $this, 'reciter_cb' ),
			'eiq_optgroup',
			'eiq_section_recitation',
			array(
				'slug' => 'reciter'
			)
		);
		
	}
	
    function admin_options() {
        add_options_page(
			__( 'Easy Insert Quran Default Settings', 'easy-insert-quran' ),
			'<img style="width: 17px; vertical-align: bottom;" src="'. EIQ_PLUGIN_URL .'/assets/images/quran-icon.png" alt="" /> Easy Insert Quran',
			'manage_options',
			'eiq-options',
			array( $this, 'settings_page' )
        );		
    }
	
	
	function translation_lang_cb($args) {
		$settings = get_option( 'eiq_settings' );
		$slug = $args['slug'];
		$name = 'eiq_settings['.$slug.']';
		$value = $settings[$slug];
		
		$request_url = 'http://api.alquran.cloud/edition?type=translation&format=text';
		$request = wp_remote_get($request_url);		
		
		if( !is_wp_error( $request ) ):
			$body = wp_remote_retrieve_body( $request );
			
			$data = json_decode( $body );

			$lang_list = array();
			
			if( !empty( $data ) && $data->status == 'OK'):
			
				foreach($data->data as $langs):
					$language = eiq_get_display_language($langs->language);
					if(!isset($lang_list[$language])):
						$lang_list[$language] = array();
					endif;
					
					$lang_list[$language][] = array(
						'identifier' => $langs->identifier,
						'name' => $langs->name,
					);
				
				endforeach;

				ksort($lang_list);
			?>
			<select name="<?php echo $name; ?>" id="<?php echo $slug; ?>">
			
			<option value="disabled" <?php selected('disabled', $value); ?>>--- <?php _e('Disable Translation', 'easy-insert-quran'); ?> ---</option>
			
			<?php foreach($lang_list as $language => $versions): ?>
				<optgroup label="<?php echo $language; ?>">				
					
					<?php foreach($versions as $ver): ?>
						<option value="<?php echo $ver['identifier']; ?>" <?php selected($ver['identifier'], $value); ?>><?php echo $ver['name']; ?></option>
					<?php endforeach; ?>
					
				</optgroup>
			<?php endforeach; ?>
			
			</select>
		<?php
			endif;
		endif;
	}
	
	
	function reciter_cb($args) {
		$settings = get_option( 'eiq_settings' );
		$slug = $args['slug'];
		$name = 'eiq_settings['.$slug.']';
		$value = $settings[$slug];
		
		$request_url = 'http://api.alquran.cloud/edition?format=audio&language=ar';
		$request = wp_remote_get($request_url);	
		
		if( !is_wp_error( $request ) ):
			
			$body = wp_remote_retrieve_body( $request );			
			$data = json_decode( $body );
			if( !empty( $data ) && $data->status == 'OK'):
			?>
				<select name="<?php echo $name; ?>" id="<?php echo $slug; ?>">
					<option value="disabled" <?php selected('disabled', $value); ?>>--- <?php _e('Disable Recitation', 'easy-insert-quran'); ?> ---</option>
					
					<?php foreach($data->data as $d): ?>
						<option value="<?php echo $d->identifier; ?>" <?php selected($d->identifier, $value); ?>><?php echo $d->name; ?></option>
					<?php endforeach; ?>
					
				</select>			
			<?php
			endif;
		
		endif;
		
	}
   
    function settings_page() {
    ?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<form action="options.php" method="post">
            <?php            
            settings_fields('eiq_optgroup');
            do_settings_sections('eiq_optgroup');           
            submit_button( __( 'Update Settings', 'easy-insert-quran' ) );
            ?>
        </form>
	</div>
	<?php
    }
}

if ( is_admin() ){
	new EIQ_Options_Page;
}
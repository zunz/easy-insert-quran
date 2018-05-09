<?php
class EIQ_Shortcode {
	
	function __construct() {
		add_shortcode('insert_quran', array($this, 'shortcode_func'));		
	}
	
	function shortcode_func($atts) {
		
		$a = shortcode_atts( array(			
			'surah' => false,
			'ayah' => false,
		), $atts );
		
		$a['surah'] = intval($a['surah']);
		$a['ayah'] = intval($a['ayah']);
		
		if($a['surah'] && $a['ayah']) {
			
			$default_options = get_option('eiq_settings');
			
			$reciter = $default_options['reciter'];
			$translation = $default_options['translation'];
			
			$base_url = 'http://api.alquran.cloud';			
			
			
			if($reciter != 'disabled') {
				$request_url = $base_url.'/ayah/'.$a['surah'].':'.$a['ayah'].'/editions/'.$reciter;
			} else {
				$request_url = $base_url.'/ayah/'.$a['surah'].':'.$a['ayah'].'/editions/quran-uthmani';
			}	

			
			if($translation != 'disabled') {
				$request_url .= ','.$translation;
			}
			
			$request = wp_remote_get($request_url);
			

			if( is_wp_error( $request ) ) {			
				return false; // Bail early
			}		
			
			$body = wp_remote_retrieve_body( $request );

			$data = json_decode( $body );
			
			if( ! empty( $data ) && $data->status == 'OK') {
				
				$data = $data->data;	

				$quran_arabic = $data[0]->text;	
				
				$output = '';
				$output .= '<div class="ins-q-wrap">';
				$output .= '<div class="ins-q-arabic-text">';
				$output .= '<p>'.$quran_arabic.' <span class="ayah-ending">۝'.eiq_convert_western_number($data[0]->numberInSurah).'</span></p>';		
				$output .= '</div>';		
				
				if($reciter && isset($data[0]->audio)) {
					
					$output .= '<div class="ins-q-audio">';
					$output .= '<audio controls src="'.$data[0]->audio.'">
					  Your browser does not support the audio element.
					</audio>';
					$output .= '</div>';
					
				}
				
				if($translation != 'disabled' && $data[1]->edition->identifier != 'quran-simple') {				
					
					$output .= '<div class="ins-q-translation">';
					$output .= '<p>'.$data[1]->text.'</p>';
					$output .= '</div>';
				}
				
				$output .= '</div>';
				
				return $output;
			}
			
		}
	}
}

new EIQ_Shortcode;
<?php
class EIQ_Shortcode {
	
	function __construct() {
		add_shortcode('insert_quran', array($this, 'shortcode_func'));		
	}
	
	function shortcode_func($atts) {
		
		$a = shortcode_atts( array(			
			'surah' => false,
			'ayah' => false,
			'end' => false,
			'translation' => false,
			'reciter' => false,
		), $atts );
		
		$surah = intval($a['surah']);
		$ayah = intval($a['ayah']);
		$end = intval($a['end']);
		
		if($surah && $ayah) {
			
			$default_options = get_option('eiq_settings');
			
			$reciter = $default_options['reciter'];
			$translation = $default_options['translation'];	

			if($a['translation']) {
				if($a['translation'] == 'no') {
					$translation = 'disabled';
				} else {
					$translation = $a['translation'];
				}
			}
			
			if($a['reciter']) {
				if($a['reciter'] == 'no') {
					$reciter = 'disabled';
				} else {
					$reciter = $a['reciter'];
				}
			}
			
			$base_url = 'http://api.alquran.cloud';
			
			if($end && ($end > $ayah)) {
				$offset_limit = '?offset='. ($ayah - 1) .'&limit='. ($end - $ayah + 1);
			} else {
				$offset_limit = '?offset='. ($ayah - 1) .'&limit=1';
			}
			
			$editions = 'quran-uthmani';
			
			if($reciter != 'disabled') {
				$editions .= ','.$reciter;
			}		
			
			if($translation != 'disabled') {
				$editions .= ','.$translation;
			}
			
			$request_url = $base_url.'/surah/'.$a['surah'].'/editions/'.$editions.$offset_limit;			
			
			$request = wp_remote_get($request_url);
			

			if( is_wp_error( $request ) ) {			
				return false; // Bail early
			}		
			
			$body = wp_remote_retrieve_body( $request );

			$data = json_decode( $body );
			
			if( ! empty( $data ) && $data->status == 'OK') {
				
				$data = $data->data;				

				$quran_arabic = $data[0];
				$quran_recitation = false;
				$quran_translation = false;
				
				if($reciter != 'disabled') {
					$quran_recitation = $data[1];
				}				
				
				if($translation != 'disabled') {					
					if($quran_recitation) {
						$quran_translation = $data[2];
					} else {
						$quran_translation = $data[1];
					}								
				}
				
				if($quran_recitation->edition->identifier == 'quran-simple') {
					$quran_recitation = false;
				}
				if($quran_translation->edition->identifier == 'quran-simple') {
					$quran_translation = false;
				}
				
				
				$output = '';
				$output .= '<div class="ins-q-wrap">';
				
				foreach($quran_arabic->ayahs as $index => $ayah):
				
					$output .= '<div class="ins-q-entry">';
					
					$output .= '<div class="ins-q-arabic-text">';
					$output .= '<p>'.$ayah->text.' <span class="ayah-ending">۝'.eiq_convert_western_number($ayah->numberInSurah).'</span></p>';		
					$output .= '</div>';		
					
					if($quran_recitation) {
						
						$output .= '<div class="ins-q-audio">';
						$output .= '<audio controls src="'.$quran_recitation->ayahs[$index]->audio.'">
						  Your browser does not support the audio element.
						</audio>';
						$output .= '</div>';
						
					}
					
					if($quran_translation) {						
						$output .= '<div class="ins-q-translation">';
						$output .= '<p>'.$quran_translation->ayahs[$index]->text.'</p>';
						$output .= '</div>';
					}
					
					
					$output .= '</div>';
					
				
				endforeach;			
				
				
				$output .= '</div>';
				
				return $output;
			}
			
		}
	}
}

new EIQ_Shortcode;
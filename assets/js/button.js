jQuery(document).ready(function($) {
	
	// var eiq_surahs = [];
	// $.getJSON( "../data/surah.json", function( data ) {	
		
		// $.each( data.data, function( key, val ) {
			// eiq_surahs.push({ text: val.number + '. ' + val.name, value: val.number });
			// //eiq_surahs.push({ number: val.number, name: val.englishName, numberOfAyahs: val.numberOfAyahs });
		// });
		
	// });
	
	// console.log(eiq_surahs);

    tinymce.create("tinymce.plugins.insert_quran_plugin", {

        //url argument holds the absolute url of our plugin directory
        init : function(ed, url) {
			
			
			// function insQGetSurahs() {
				// var eiq_surahs = [];
				// $.getJSON( url + "/../data/surah.json", function( data ) {	
					
					// $.each( data.data, function( key, val ) {
						// eiq_surahs.push({ text: val.number + '. ' + val.name, value: val.number });
						// //eiq_surahs.push({ number: val.number, name: val.englishName, numberOfAyahs: val.numberOfAyahs });
					// });
					
				// });				
				// return eiq_surahs;
			// }
			
			var eiq_data_num_ayah = [
				
			];
			var eiq_data_surahs = [];
			var eiq_data_ayahs = [];
			$.getJSON( url + "/../data/surah.json", function( data ) {	
				
				$.each( data.data, function( key, val ) {
					eiq_data_num_ayah.push(val.numberOfAyahs);					
					eiq_data_surahs.push({ text: val.number + '. ' + val.name, value: val.number });					
				});
				
			});			
			
            //add new button    
            ed.addButton("eiq_button", {
                title	: "Insert Quran",
                cmd		: "insert_quran",
                image	: url + '/../images/quran-icon.png'
            });

            //button functionality.
            ed.addCommand("insert_quran", function() {
                ed.windowManager.open({
					title	:'Insert Quran',
					body	: [								
							{
								name	: 'eiq_surah',
								id		: 'eiq_surah',
								label	: 'Choose Surah',
								type	: 'listbox',
								values 	: eiq_data_surahs,
								// onselect : function(e){
					                    	// var surIndex = this.value();
					                    	// eiq_data_surahs = [];
											// var maxxx = eiq_data_num_ayah[surIndex];
											
											// var eiq_i;
											// for(eiq_i = 1; eiq_i <= maxxx; eiq_i++) {
												// eiq_data_surahs.push({ text: eiq_i, value: eiq_i });
											// }
											
											// console.log();
					                    // },
							},
							{
								name	: 'eiq_ayah',
								id		: 'eiq_ayah',
								label	: 'Enter Ayah Number',
								type	: 'textbox',
								value	: ''
							},
							// {
								// name	: 'eiq_ayah',
								// id		: 'eiq_ayah',
								// label	: 'Select Ayah Number',
								// type	: 'listbox',
								// value	: eiq_data_surahs
							// },
						
							
							],
					buttons	: [
							{ 
								text	: 'Insert',
								subtype	: 'primary',
								onclick	: 'submit'
							},
							{
								text	: 'Close',
								onclick	: 'close'
							}
						],
					
					onsubmit: function(e) {
						var shortcode = '';
						var ayah = e.data.eiq_ayah;
						var surah = e.data.eiq_surah;
						if(ayah && surah) {
							shortcode = '[insert_quran surah="'+surah+'" ayah="'+ayah+'"]\n';
						}
						tinymce.execCommand('mceInsertContent', false, shortcode);
					}
					
				});
            });
        },

        // createControl : function(n, cm) {
            // return null;
        // },
       
    });

	tinymce.PluginManager.add("insert_quran_plugin", tinymce.plugins.insert_quran_plugin);
	
	


});

jQuery(document).ready(function($) {	

    tinymce.create("tinymce.plugins.insert_quran_plugin", {
       
        init : function(ed, url) {				
			
            //add new button    
            ed.addButton("eiq_button", {
                title	: "Insert Quran",
                cmd		: "insert_quran",
                image	: url + '/../images/quran-icon.png'
            });

            //button functionality.
            ed.addCommand("insert_quran", function() {
                ed.windowManager.open({					
					file : url + '/editor_plugin.php',
					inline : 1,
					width : 350,
                    height : 220					
				},{
					plugin_url : url					
				});
            });
        },
		
		createControl : function(n, cm) {
			return null;
		},
        
    });

	tinymce.PluginManager.add("insert_quran_plugin", tinymce.plugins.insert_quran_plugin);
	
});

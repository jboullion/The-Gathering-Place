(function() {
    tinymce.create('tinymce.plugins.PKButton', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
        	var win = null;
	        	var pk_button_form = '<div id="pk-button-form"><table>'+
	        	'<tr><td><label for="pk-button-title">Title</label></td><td><input type="text" value="" id="pk-button-title" name="pk-button-title"/></td></tr>'+
	        	'<tr><td><label for="pk-button-link">Link</label></td><td><input type="text" value="" id="pk-button-link" name="pk-button-link"/></td></tr>'+
	        	'</table></div>';
	        	
	        	var pk_button_title = '';
	        	var pk_button_link = '';
            	
 			ed.addButton('pkButtonBTN', {
                title : 'Add a Button',
                cmd : 'pkButtonCMD'
            });
 			
            ed.addCommand('pkButtonCMD', function() {
	        	
            	win = ed.windowManager.open({
					title: 'Add a Button',
					body: [{
						type: 'container',
						html: pk_button_form
					}],
					onsubmit: function(e) {
						pk_button_title = jQuery('#pk-button-title').val();
						pk_button_link = jQuery('#pk-button-link').val();
                        //return_text = '<a href="'+pk_button_link+'" class="btn btn-primary" >'+pk_button_title+'</a>';
						return_text = '[pkButton link="'+pk_button_link+'" title="'+pk_button_title+'"]';
                		ed.execCommand('mceInsertContent', 0, return_text);
					}
				});
                
            });
 
        },
 
        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },
 
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Add a Button',
                author : 'Powderkeg',
                authorurl : 'http://www.powderkegwebdesign.com/',
                version : "1.0"
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'pkButton', tinymce.plugins.PKButton );
})();
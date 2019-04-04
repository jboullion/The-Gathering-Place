(function() {
    tinymce.create('tinymce.plugins.PkInsertVideo', {
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
	        	var pk_video_form = '<div id="insert-video-form"><table>'+
	        	'<tr><td><label for="pk-video-title">Title</label></td><td><input type="text" value="" id="pk-video-title" name="pk-video-title"/></td></tr>'+
	        	'<tr><td><label for="pk-video-link">Link</label></td><td><input type="text" value="" id="pk-video-link" name="pk-video-link"/></td></tr>'+
                '<tr><td colspan="2"><label><input type="radio" name="align" value="" checked="checked" />No Align</label></td></tr>'+
                '<tr><td colspan="2"><label><input type="radio" name="align" value="alignleft" />Left Align</label></td></tr>'+
                '<tr><td colspan="2"><label><input type="radio" name="align" value="alignright" />Right Align</label></td></tr>'+
	        	'</table></div>';
	        	
	        	var pk_video_title = '';
	        	var pk_video_link = '';
                var pk_video_align = '';
            	
 			ed.addButton('pkInsertVideo', {
                title : 'Insert Video',
                cmd : 'insertVideo',
            });
 			
            ed.addCommand('insertVideo', function() {
	        	
            	win = ed.windowManager.open({
					title: 'Setup a Video',
					body: [{
						type: 'container',
						html: pk_video_form
					}],
					onsubmit: function(e) {
						pk_video_title = jQuery('#pk-video-title').val();
						pk_video_link = jQuery('#pk-video-link').val();
                        pk_video_align = jQuery("input:radio[name='align']:checked").val();

						return_text = '[pkInsertVideo link="'+pk_video_link+'" title="'+pk_video_title+'" align="'+pk_video_align+'"]';
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
                longname : 'Insert Video',
                author : 'Powderkeg',
                authorurl : 'http://www.powderkegwebdesign.com/',
                version : "0.1"
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'pkInsertVideo', tinymce.plugins.PkInsertVideo );
})();
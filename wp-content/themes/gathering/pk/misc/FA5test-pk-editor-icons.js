jQuery(document).ready(function($) {

	// icon list
	var pk_icon_list = ["glass",	"music",	"search",	"envelope",	"heart",	"star",	"star",	"user",	"film",	"th-large",	"th",
	"th-list",	"check",	"times",	"search-plus",	"search-minus",	"power-off",	"signal",	"gear",
	"trash",	"home",	"file",	"clock",	"road",	"download",	"arrow-circle-down",	"arrow-circle-up",	"inbox",
	"play-circle",	"rotate-right",	"refresh",	"list-alt",
	"lock",	"flag",	"headphones",	"volume-off",	"volume-down",	"volume-up",
	"qrcode",	"barcode",	"tag",	"tags",	"book",	"bookmark",	"print",	"camera",	"font",	"bold",
	"italic",	"text-height",	"text-width",	"align-left",	"align-center",	"align-right",	"align-justify",	"list",	"dedent",	"indent",	"video-camera",	"image",
	"pencil","map-marker","adjust","tint","edit","share-square","check-square","arrows","step-backward","fast-backward",
	"backward","play","pause","stop","forward","fast-forward","step-forward","eject","chevron-left","chevron-right","plus-circle",
	"minus-circle","times-circle","check-circle","question-circle","info-circle","crosshairs","times-circle","check-circle",
	"ban","arrow-left","arrow-right","arrow-up","arrow-down","mail-forward","expand","compress","plus","minus","asterisk",
	"exclamation-circle","gift","leaf","fire","eye","eye-slash","warning","plane","calendar","random","comment","magnet",
	"chevron-up","chevron-down","retweet","shopping-cart","folder","folder-open","arrows-v","arrows-h","bar-chart",
	"twitter-square","facebook-square","camera-retro","key","gears","comments","thumbs-up","thumbs-down","star-half",
	"heart","sign-out","linkedin-square","thumb-tack","external-link","sign-in","trophy","github-square","upload","lemon",
	"phone","square","bookmark","phone-square","twitter","facebook","github","unlock","credit-card","rss","hdd","bullhorn",
	"bell","certificate","hand-right","hand-left","hand-up","hand-down","arrow-circle-left","arrow-circle-right","arrow-circle-up",
	"arrow-circle-down","globe","wrench","tasks","filter","briefcase","arrows-alt","group","chain","cloud","flask","cut","copy","paperclip",
	"save","square","bars","list-ul","list-ol","strikethrough","underline","table","magic","truck","pinterest","pinterest-square","google-plus-square",
	"google-plus","money","caret-down","caret-up","caret-left","caret-right","columns","unsorted","sort-down","sort-up","envelope","linkedin","rotate-left",
	"legal","dashboard","comment","comments","flash","sitemap","umbrella","paste","lightbulb","exchange","cloud-download","cloud-upload","user-md",
	"stethoscope","suitcase","bell","coffee","cutlery","file-text","building","hospital","ambulance","medkit","fighter-jet","beer","h-square",
	"plus-square","angle-double-left","angle-double-right","angle-double-up","angle-double-down","angle-left","angle-right","angle-up","angle-down",
	"desktop","laptop","tablet","mobile-phone","circle","quote-left","quote-right","spinner","circle","mail-reply","github-alt","folder","folder-open",
	"smile","frown","meh","gamepad","keyboard","flag","flag-checkered","terminal","code","reply-all","mail-reply-all","star-half-empty","location-arrow",
	"crop","code-fork","unlink","question","info","exclamation","superscript","subscript","eraser","puzzle-piece","microphone","microphone-slash","shield",
	"calendar","fire-extinguisher","rocket","maxcdn","chevron-circle-left","chevron-circle-right","chevron-circle-up","chevron-circle-down","html5",
	"css3","anchor","unlock-alt","bullseye","ellipsis-h","ellipsis-v","rss-square","play-circle","ticket","minus-square","minus-square","level-up",
	"level-down","check-square","pencil-square","external-link-square","share-square","compass","toggle-down","toggle-up","toggle-right","euro","gbp",
	"dollar","rupee","cny","ruble","won","bitcoin","file","file-text","sort-alpha-asc","sort-alpha-desc","sort-amount-asc","sort-amount-desc",
	"sort-numeric-asc","sort-numeric-desc","thumbs-up","thumbs-down","youtube-square","youtube","xing","xing-square","youtube-play","dropbox","stack-overflow",
	"instagram","flickr","adn","bitbucket","bitbucket-square","tumblr","tumblr-square","long-arrow-down","long-arrow-up","long-arrow-left","long-arrow-right",
	"apple","windows","android","linux","dribbble","skype","foursquare","trello","female","male","gittip","sun","moon","archive","bug","vk","weibo",
	"renren","pagelines","stack-exchange","arrow-circle-right","arrow-circle-left","toggle-left","dot-circle","wheelchair","vimeo-square","turkish-lira","plus-square"];
	
	// generate icon set
	var pk_icon_set = '';
	for(var i = 0; i < pk_icon_list.length; i++) {
		var icon = pk_icon_list[i];
		pk_icon_set += '<li><i class="far fa-'+icon+'">&nbsp;</i></li>';
	}
	pk_icon_set = '<div id="pk-icon-set"><ul>'+pk_icon_set+'</ul></div>';
	
	// add editor plugin
	tinymce.PluginManager.add('pk_icon', function(editor, url) {
		var win = null;
		var sel = '';
		
		// highlight selected icon
		$('body').on('click', '#pk-icon-set li', function() {
			$(this).parent().find('.selected').removeClass('selected');
			$(this).addClass('selected');
			sel = $(this).find('i').attr('class');
		});
		
		// add button
		editor.addButton('pk_icon_list', {
			text: '',
			icon: 'mce-ico pk-icons-icon fab fa-font-awesome',
			
			// open window
			onclick: function() {
				win = editor.windowManager.open({
					title: 'Choose an Icon',
					body: [{
						type: 'container',
						html: pk_icon_set,
						css: 'pk-editor-icons.css'
					}],
					onsubmit: function(e) {
						if(sel != '') {
							editor.insertContent('<i class="'+sel+'">&nbsp;</i>');
						}
					}
				});
			}
		});
	});
});
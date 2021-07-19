(function($, LA){
	"use strict";
	
	$(function(){
		var $table = $('.user-profile-picture');
		var $img = $table.find("img");
		
		$table.closest("form").attr("enctype","multipart/form-data");
		
		$table.on("change", "input[type=file]", function(){
			var src = window.URL.createObjectURL(this.files[0]);
			$img.attr("src", src);
			$img.removeAttr("srcset");
			$update.show();
			
		});
		
		/**
		 * build the gui
		 */
		var $update = $("<span class='update'>Save profile for update.</span>").hide();
		var $file = $("<input name='"+LA.file_input_name+"' type='file' />");
		$("<p></p>").append($file).append("<br>").append($update).insertAfter($img);
	});
	
})(jQuery, LocalAvatars);
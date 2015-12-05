$(document).ready(function() {
	$("select#svenid").change(function(){
		var id = $("select#svenid option:selected").attr('value');
		$.post("select_items.php", {p_vid:id}, function(data){
			$("select#sitemid").html(data);
		});
	});
});

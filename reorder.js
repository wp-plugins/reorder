jQuery(document).ready(function($) {
    $("#order-posts-list").sortable({
	   update : function () {
	   	$("#order-loading span").html('<img src="../wp-content/plugins/reorder/loading.gif" />');
		var order = $("#order-posts-list").sortable("serialize");
		$("#order-loading span").load("../wp-content/plugins/reorder/process-sortable.php?"+order);
      }  , placeholder :"highlight" , forcePlaceholderSize: true, opacity : 0.6, axis: 'y', items: "li"
    });
});
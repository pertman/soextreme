$( document ).ready(function() {
	
	$("#connexion-modal").click(function() {
	  $("#modal-connexion").addClass("is-active");  
	});
	
	$("#inscription-modal").click(function() {
	  $("#modal-inscription").addClass("is-active");  
	});

	$(".delete").click(function() {
	   $(".modal").removeClass("is-active");
	});

	
	$(".modal-menu-inscription, .modal-menu-conexion").hover(
		function(){ $(this).siblings('.modal-menu').addClass("is-hover-modal") },
		function(){ $(this).siblings('.modal-menu').removeClass("is-hover-modal")  }	
	);

	
	$(".modal-menu-inscription, .modal-menu-connexion").click(function(){
		$(".delete").click();

		if($(this).hasClass("modal-menu-inscription"))
		{
			$("#inscription-modal").click();
		}
		if($(this).hasClass("modal-menu-connexion"))
		{
			$("#connexion-modal").click();
		}

	});
	
});
function status(table,column,id,id_name){ 
	$.post("ajax/status.php",{"table":table,"column":column,"id":id,"id_name":id_name},function(respond){
		console.log(respond);
	});
}




function delete_col(table,row,id){ console.log("hi");
	 if (confirm("Are you sure to delete this row ?") == false) {
                    return;
       }
	$.post("ajax/delete.php",{"table":table,"id":id,"column":"id"},function(respond){ 
		
		if(respond){
			$(".message").append('<span>'+respond+'</span>');
		}else{
			$("#"+row).remove();
		    $(".message").append('<span>Delete Succefull</span>');
		}
		setTimeout(function(){ 
			$(".message span").fadeOut(3000);
		}, 5000);
    
	});
}

function removeimg(table,row,id,field){
	 if (confirm("Are you sure to delete this Image ?") == false) {
                    return;
       }
	$.post("ajax/function.php",{"choice":"deleteimg","table":table,"field":field,"id":id},function(respond){ 
		$("#"+row).remove();
	});
}

$( document ).ready(function() {
	setTimeout(function(){ 
			$(".message span").fadeOut(3000);
	}, 5000);
    
})
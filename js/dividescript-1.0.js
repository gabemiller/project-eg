$(document).ready(function(e) {
	
	$('#jump-up-box').modal();
	
	var id = 2;
	var sec = 14;
	var block = $('#tenpoints');
	var blockHeader = block.find('h4').first();
	var quotes = function(){
		//var id = Math.floor((Math.random()*10)+1);
		var saveId = id;
		 $.ajax({
			type: "post",
			url: "ajax/tenpointsHandler.php",
			data: "id="+id,
			dataType:"json", 
			success: function(data,textStatus,jqXHR){
				
				blockHeader.fadeOut('slow',function(){
					blockHeader.html('<span class="id-num">'+ saveId + '</span> ' +data.points);
					changeColor(saveId);
					blockHeader.fadeIn('slow');
					
				});
				
			},
			error: function(jqXHR,textStatus,errorThrown){
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
			}
		 })
		 
		 if(id<10){
		 	id++;
		 } else {
			id = 1;
		 }
	}
	
	var changeColor = function(num){
		
		switch(num){
			case 1:
				block.removeClass('alert-unique').addClass('alert-danger');
				break;
			case 3:
				block.removeClass('alert-danger');
				break;
			case 5:
				block.addClass('alert-success');
				break;
			case 7:
				block.removeClass('alert-success').addClass('alert-info');
				break;
			case 9:
				block.removeClass('alert-info').addClass('alert-unique');
				break;
		}
	}
	
	interval = setInterval(quotes,10*1500);
	
	var counter = $('.counting');
	var counting = function(){
		
		counter.html(sec);
		
		if(sec > 1){
			sec--;
		}else {
			sec = 15;
		}
	}
	
	interval2 = setInterval(counting,1000);
	
});
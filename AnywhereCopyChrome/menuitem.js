var clickHandler = function(info,tab){

		var url = "http://anywherecopy.elasticbeanstalk.com/anywherecopy.php";
		var userId = localStorage['userId'];
		var xhr = new XMLHttpRequest();
	
		if(info.selectionText){
			xhr.open("POST",url,true);
			xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhr.send("userId="+userId+"&selected="+info.selectionText);
		}
	
		if(info.linkUrl){
			xhr.open("POST",url,true);
			xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhr.send("userId="+userId+"&selected="+info.linkUrl);
		}
		
		if(info.srcUrl){
			xhr.open("POST",url,true);
			xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhr.send("userId="+userId+"&selected="+info.srcUrl);
		}	
}

function install_check(){
	if(localStorage.getItem('isInstalled')){
		return;
	}

		chrome.contextMenus.create(
		{
			"title": "Copy Anywhere",
			"contexts" : ["page","selection","image","link"],
			"onclick" : clickHandler
		});
	
	localStorage.setItem('isInstalled','true');
}

install_check();


$(document).ready(function(){
	$("form").submit(
		function(event){
				event.preventDefault();
				$('#registering').css("visibility","visible");
				var values = $(this).serialize();
				var username = $('#usernamefield').val();
			
				var request = $.ajax(
					{
						url:"http://anywherecopy.elasticbeanstalk.com/signup.php",
						type: "post",
						dataType: 'json',
						data: values,
						success: function(data){
							if($.trim(data.where) == 'uname'){
								$("#unameresult").text(data.message);	
							}
							if($.trim(data.where) == 'confirm'){
								$('#confirmresult').text(data.message);
							}
							if($.trim(data.status) == 'success'){
								localStorage['userId'] = username;
								chrome.tabs.create({url: 'registered.html'});
							}
						},
						error: function(data){
							
						}
					}
				);
				
		}
		
	);
	
	
});
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
			//var newUrl = info.linkUrl.replace('http://','');
			xhr.send("userId="+userId+"&selected="+info.linkUrl);
		}
		
		if(info.srcUrl){
			xhr.open("POST",url,true);
			xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xhr.send("userId="+userId+"&selected="+info.srcUrl);
		}	
}

chrome.contextMenus.create(
		{
			"title": "Copy Anywhere",
			"contexts" : ["page","selection","image","link"],
			"onclick" : clickHandler
		});
		

var values = "dummy";
			
function install_check(){
	
			if(localStorage.getItem('isInstalled')){
				return;
			}
			
			$(document).ready(function(){
				$.ajax(
					{
						url:"http://anywherecopy.elasticbeanstalk.com/signup.php",
						type: "post",
						dataType: 'json',
						data: values,
						success: function(data){
							if($.trim(data.where) == 'uname'){
								
							}
							if($.trim(data.where) == 'confirm'){
							
							}
							if($.trim(data.status) == 'success'){
								//alert('Enter this pin in the Android App :: '+ $.trim(data.identity));
								alert('Enter this PIN now, in the Android App:'+$.trim(data.identity));
								localStorage['userId'] = $.trim(data.identity);
								
								document.cookie = "userpin"+"="+$.trim(data.identity); 
								
								chrome.tabs.create({"url":'registered.html'});	
								
							}
						},
						error: function(data){
							alert('Failure');
						}
					}
				);
			});
			
			localStorage.setItem('isInstalled','true');
}

install_check();
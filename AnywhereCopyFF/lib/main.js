const MY_EXTENSION_VERSION = "0.1";

var pinValue = '';

function pinset(tab){
	var tabs = require("sdk/tabs");
	tabs.activeTab.attach(
		{
			contentScriptFile: require("self").data.url('pinback.js')
		}	
	);
}



exports.main = function(options,callback){
	var pinValue = '';
	console.log("main function called");
	if(!require("preferences-service").get("acap.install.value")){
		console.log("installed");
		var Request  = require("sdk/request").Request;
		var signuprequest = Request({
			url: "http://anywherecopy.elasticbeanstalk.com/signup.php",
			contentType: "application/json; charset=utf-8",
			onComplete: function(response){
				//var outcome = response.json;
				console.log("Singup:: " + response.json.identity);
				var name = "acap.install.value";
				require("preferences-service").set(name,response.json.identity);
				pinValue = response.json.identity;
				var tabs = require("sdk/tabs");
				tabs.open({
					url: require("self").data.url("registered.html"),
					onReady: pinset
				});
				console.log("Signup:: request complete");
			}
		});
		
		signuprequest.post();
	}
	
	require("page-mod").PageMod(
		{
			include: require("self").data.url('registered.html'),
			contentScriptFile: require("self").data.url('pinback.js'),
			onAttach: function(worker){
				worker.port.emit("load",pinValue);
			}
		}
	);
};

var contextMenuLink = require("sdk/context-menu");
 var menuItem = contextMenuLink.Item({
  label: "Anywhere Copy",
  context: contextMenuLink.SelectorContext("a","img"),
  contentScriptFile: require("self").data.url("firefoxcopylink.js"),
  onMessage: function(data){
  	var userid = require("preferences-service").get("acap.install.value");
  	console.log(userid);
  	console.log(data);
  	
  	var Request  = require("sdk/request").Request;
    console.log("begin");
    Request({
			url: "http://anywherecopy.elasticbeanstalk.com/anywherecopy.php",
			content: {'userId':userid,'selected':data},
			contentType: "application/x-www-form-urlencoded",
			onComplete: function(response){
				
				console.log("Copy complete");
				
			}
	}).post();
	
	console.log("end");
  }
});

var contextMenuText = require("sdk/context-menu");
 var menuItem = contextMenuText.Item({
  label: "Anywhere Copy",
  context: contextMenuText.SelectionContext(),
  contentScriptFile: require("self").data.url("firefoxcopytext.js"),
  onMessage: function(data){
  	var userid = require("preferences-service").get("acap.install.value");
  	console.log(userid);
  	console.log(data);
  	
  	var Request  = require("sdk/request").Request;
    console.log("begin");
    Request({
			url: "http://anywherecopy.elasticbeanstalk.com/anywherecopy.php",
			content: {'userId':userid,'selected':data},
			contentType: "application/x-www-form-urlencoded",
			onComplete: function(response){
				console.log("Copy complete");
			}
	}).post();
	
	console.log("end");
  }
});
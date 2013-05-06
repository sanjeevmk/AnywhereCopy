self.port.on("load",function(tag){
	console.log(tag);
	//jQuery('#pinholder').text = tag;
	var pinholder = document.getElementById("pinholder");
	pinholder.innerHTML = tag;
});
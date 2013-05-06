self.on("click", function (node,data) {
	
	if(node.nodeName === 'A'){
		toCopy = node.href;
	}
	if(node.nodeName === 'IMG'){
		toCopy = node.src;
	}
	   
    self.postMessage(toCopy);
});
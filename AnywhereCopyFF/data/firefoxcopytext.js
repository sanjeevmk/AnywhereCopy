self.on("click", function () {
	var toCopy = window.getSelection().toString();
    console.log(toCopy);
    self.postMessage(toCopy);
});
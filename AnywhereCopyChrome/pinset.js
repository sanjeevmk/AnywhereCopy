$(document).ready(function(){
	
	var c_value = document.cookie;

	var c_start = c_value.indexOf(" " + "userpin" + "=");
	if (c_start == -1)
	{
  		c_start = c_value.indexOf("userpin" + "=");
	}
	if (c_start == -1)
  	{
  		c_value = null;
  	}
	else
  	{
		c_start = c_value.indexOf("=", c_start) + 1;
  		var c_end = c_value.indexOf(";", c_start);
  		if (c_end == -1)
  		{
			c_end = c_value.length;
		}
	
		c_value = unescape(c_value.substring(c_start,c_end));
		
		$('#pinholder').text(c_value);
	}
});
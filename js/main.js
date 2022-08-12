function ch_location(url,sek) {

	var millsek = sek*1000;
	
	window.setTimeout(function() {
	
		window.location = url;
		
	},millsek);
	
}

function chk_inputlength(inputid,inputlength) {

	var input = document.getElementById(inputid);

	if(input.value.length >= inputlength)
	{
		newvalue = input.value.slice(0,inputlength);

		input.value = newvalue; 
	}
}


/*
    Icon-Crawler for https://material.io/resources/icons/?style=baseline

    Go to this website, open the Developer Console and paste this code.
    You get a PHP parsable string. It contains an array $a and a function
    called "string get_random_icon()". I was to lazy to write down all
    by hand. I also tested this one only on the link provided.
    
    I don't know if it works for any other icon sets on the page since
    I only needed the baseline style.
*/

function get_element()
{
	return document.querySelectorAll(".icon-image-preview");
}

function parse(elem)
{
	let str = "<?php $a = array();\n";
	for (let i = 0; i < elem.length; i++)
	{
		str += '$a[] = "' + elem[i].innerHTML + '";\n';
	}
	
	str += write_function();
	
	str += "?>";
	console.log(str);
}

function write_function()
{
	return "\nfunction get_random_icon()\n{\n\tglobal $a;\n\treturn array_rand($a);\n}\n";
}
parse(get_element());
/*

	DIVERGENT MIND MEDIA

	all scripts (c)2008 - 2009 Divergent Mind Media, LLC
	
	authored by Jay Sanders

*/

//#########################################
// TABBED WINDOW FUNCTIONS

function tabbedWindows(total, tab)
{
	for(i = 1; i <= total; i++)
	{		
		if (tab == i) showDiv('tabBox_' + i);
		else hideDiv('tabBox_' + i);
	}
}

function verticalTabbedWindows(total, tab)
{
	for(i = 1; i <= total; i++)
	{
		tabButton = document.getElementById('tabButton_'+ i);
		tabBox = 'tabBox_' + i;
		
		if (tab == i)
		{
			tabButton.className="verticalTabOn";
			//tabButton.style.background="#FFFFFF";
			showDiv(tabBox);
		}
		else
		{
			tabButton.className="verticalTab";
			//tabButton.style.background="#6592CE"; 
			hideDiv(tabBox);
		}
	}
}

//#########################################
// DIV TOGGLE FUNCTIONS

function showDiv(div)
{
		document.getElementById(div).style.display = "block" ;
}

function hideDiv(div)
{
		document.getElementById(div).style.display = "none" ;
}

//###########################################
// DIAGNOSTICS AND TESTING

function diagnostics(message)
{
	alert("Diagnostics: " + message);
}

function test(message)
{
	alert('TEST: ' + message);
}

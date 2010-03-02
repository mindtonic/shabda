function toggleMenu(currMenu)
{
	if (document.getElementById)
	{
		mainId = currMenu+'_main'
		new Effect.toggle(mainId, 'blind')		
		new Effect.toggle(currMenu, 'blind')
				
		button = document.getElementById(currMenu+'_button')
		if (button.value == "more") button.value = "less"
		else button.value = "more"		
				
		return false
	}
	else return true
}
function toggleForm()
{
	new Effect.toggle(postForm, 'slide')		
	button = document.getElementById('form_button')
	if (button.value == "add comment") button.value = "close form"
	else button.value = "add comment"						
}
var countEl = document.getElementById("num_slot");

function plus()
{
	if(isNaN(countEl.value))
	{
		countEl.value = 0;
		return;
	}	
	else
		countEl.value ++;
}


function minus()
{
	if(isNaN(countEl.value))
	{
		countEl.value = 0;
		return;
	}
	else
		if (countEl.value > 0) 
			countEl.value --;
}
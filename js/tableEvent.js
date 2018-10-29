
var elements;
const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

function book()
{
	var num =  document.getElementById("num_slot").value;
	if(isNaN(num) || num <= 0)
	{
		alert("Please insert a valid number of slots you would book");
		return;
	}
	if(document.getElementById("num_slot").value > 50)
	{
		alert("You have exceed the maximum number of requested slots in a week. Please do not overcome 50 slots requested");
		return;
	}
	var str = "";
	for(var i = 0; i < elements.length-1; i++)
		str += ""+elements[i].id+"|";
	str += ""+elements[i].id; //the last one without the delimiter
	//alert("You would to book: "+str);
	var cookieName = "slots";
	document.cookie = ""+cookieName+"="+str;
	window.location.replace("handlers/booking_handler.php");
}

function goingToDel()
{
	if(confirm("The last booking operation will be deleted. Are you sure?"))
		window.location.replace("handlers/delete_handler.php");
}

function greenOrRed(cell)
{
	var num =  document.getElementById("num_slot").value;
	if (isNaN(num))
		return;
	if(num <= 0) 
		return;
	//filter the correct cell and put them into elements
	availableFrom(cell, num);
	var str = "";
	for(var i = 0; i < elements.length; i ++)
		str += elements[i].id+" ";
	//alert("To be colored: "+str);
	if( elements.length >= num )
		changeColor('green');
	else
		changeColor('red');
}

function resetColor()
{
	changeColor('white');
}

//starting from cell, it returns the # of available cells following this one
function availableFrom(cell, num)
{
	elements = [];
	var table = document.getElementById("table_calendar");
	var tmp, selector;
	var enough = false;
	var startingDay = cell.id.slice(0, cell.id.indexOf("_"));
	var startingIndex = days.indexOf(startingDay);
	var startingSlot = cell.id.slice(cell.id.indexOf("_")+1);
	var isValidSlot = false;
	for(var curr_day = startingIndex; curr_day < days.length && !enough; curr_day ++ )
	{
		selector = "[id^="+days[curr_day]+"_]";
		tmp = Array.prototype.slice.call(document.querySelectorAll(selector));
		for(var index = 0; index < tmp.length && !enough; index ++)
		{
			//skip the slot of the first day before the ones selected
			if(!isValidSlot && 
				(days[curr_day] == startingDay && tmp[index].id.slice(cell.id.indexOf("_")+1) == startingSlot))
				isValidSlot = true;
			if(isValidSlot && tmp[index].className == 'blank')
			{
				elements.push(tmp[index]);
				if(elements.length >= num)
					enough = true;
			}
		}	
	}	
}


function changeColor(color)
{
	for(i = 0; i < elements.length; i ++)
		elements[i].style.backgroundColor = color;
}












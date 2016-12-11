//alert("Basys");
var page = 1;
var startGroupObject = null;
var startTeacherObject = null;
var startAuditoriasObject = null;
var asideAjaxGroupsContent = document.getElementById('asideAjaxGroupsContent');
asideAjaxGroupsContent.innerHTML = "";	
getElementsList();
//====================================================================================
function getElementsList(pageData=1){
	var theUrl = "/ajaxGroups/arrGroup";
	var theParam = "functionHandler=viewElementList&status=anons&page=" + pageData;			
	setAjaxQuery(theUrl,theParam);	
}
function viewElementList(){
//	alert(myReq.responseText);
	page = responseXMLDocument.getElementsByTagName('page')[0].textContent;
	asideAjaxGroupsContent.innerHTML = "";
	var pageDomElement = responseXMLDocument.getElementsByTagName('pager');	
	if (pageDomElement.length){
		var pagerText = pageDomElement[0].textContent;
		var pager = JSON.parse(pagerText);
		viewPager(pager,asideAjaxGroupsContent,page,getElementsList);	
	}
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff');
	if (nextStaff.length != 0){
		var table = document.createElement('table');
		table.innerHTML = "<tr><th>название группы</th><th>специальность</th><th>цена курса</th><th>число занятий</th><th>Число студентов</th></tr>";
		table.className = "resultTable";
		asideAjaxGroupsContent.appendChild(table);
		for (var i = 0; i < nextStaff.length; i++){
			var nextGroup = JSON.parse(nextStaff[i].textContent);	
			var tr = document.createElement('tr');
			tr.setAttribute('idGroup', nextGroup.id);
			tr.onclick = getPerson;
			tr.innerHTML = "<td>"+nextGroup.title+"</td><td>"+nextGroup.cpecTitle+"</td><td>"+nextGroup.price+"</td><td>"+nextGroup.numLesson+"</td><td>"+nextGroup.countStudent+"</td>";			
			table.appendChild(tr);
		}
	}	
}

//===========================================================================================================
function getPerson(){	
	var idGroup = this.getAttribute('idGroup');
	var theUrl = "/ajaxGroups/fullInformationForStart";
	var theParam = "functionHandler=startGroupById&idGroup=" + idGroup;			
	setAjaxQuery(theUrl,theParam);
}
function startGroupById(){
//	alert(myReq.responseText);
	if (responseXMLDocument.getElementsByTagName('nextAuditorias').length){
		startAuditoriasObject = JSON.parse( responseXMLDocument.getElementsByTagName('nextAuditorias')[0].textContent );
	}
	if (responseXMLDocument.getElementsByTagName('nextTeachers').length){
		startTeacherObject = JSON.parse( responseXMLDocument.getElementsByTagName('nextTeachers')[0].textContent );
	}
	var param = { "day":"день","week":"недели","month":"месяца" };
	var personalData = document.getElementById('personalData');	
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff')[0].textContent;
	if (nextStaff != null){
		var group = JSON.parse(nextStaff);
		startGroupObject = group;
		personalData.innerHTML = "<h2>Старт занятий группы " + group.title + " по специальности " + group.cpecTitle + "<h2>";
		personalData.innerHTML += "<p>У группы запланировано " + group.numLesson + " занятий длительностью " + group.duration + " часа</p>";
		if(group.periodicity == 'day'){
			personalData.innerHTML += "<p>Цикличность: ежедневные занятия " + group.quantity + " занятиe в день</p>";
		}else{
			personalData.innerHTML += "<p>Цикличность: " + group.quantity + " занятий в течение " + param[group.periodicity] + " </p>";
		}
		var todayData = new Date();
		var optionsForData = {year: 'numeric',month: 'numeric',day: 'numeric'};
		var dataString = myf_getStringData(todayData);
		var form = document.createElement('form');
		form.name = 'startTemetableForm';
		personalData.appendChild(form);
		form.innerHTML = "<p>Выберите дату фактического начала занятий <input type='date' name='startFactData' value='" + dataString + "' oninput='getTemetableForGroup()'></input></p>";		
		form.innerHTML += "<p>Как составлять расписание: <label><input type='radio' name='variantTemetable' value='auto' onblur ='getTemetableForGroup()' checked>програмно</input></label><label><input type='radio' name='variantTemetable' value='hand' onblur ='getTemetableForGroup()'>в ручном режиме</input></label></p></form><hr>";		
		var div = document.createElement('div');
		div.className = "divForViewPeriod";
		div.id = "divForViewPeriod";
		div.innerHTML = "";
		personalData.appendChild(div);
		getTemetableForGroup();
	}
}
function getTemetableForGroup(){
//	alert('getTemetableForGroup');	
	if (startGroupObject){
		var startTemetableForm = document.forms.startTemetableForm;
		var startFactData = startTemetableForm.elements.startFactData.value;
		var variantTemetable = startTemetableForm.elements.variantTemetable.value;		
		var divForViewPeriod = document.getElementById('divForViewPeriod');
		divForViewPeriod.innerHTML = "";
		if(variantTemetable == 'hand'){
			viewAutoStartGroup('ggg','rtt');
//			viewHandForm(divForViewPeriod);
		}else{
			if(startGroupObject.periodicity == 'week'){
				viewWeekForm(divForViewPeriod);
			}else if(startGroupObject.periodicity == 'month'){
				viewMonthForm(divForViewPeriod);	
			}else{
				viewDayForm(divForViewPeriod);	
			}
		}		
	}
		
}
function validateWeekTemetable(){	
	var numDay = 0;
	var arrDays = {};
	var arrTimes = {};
	for( var i = 0; i < document.forms.periodTamet.elements.length; i++){
		var newElemForm = document.forms.periodTamet.elements[i];
		if (newElemForm.type == "time"){arrTimes[newElemForm.name] = newElemForm.value;}				
	}	
	for( var i = 0; i < document.forms.periodTamet.elements.length; i++){
		var newElemForm = document.forms.periodTamet.elements[i];	
		if (newElemForm.type == "checkbox" && newElemForm.checked == true){	
			numDay++;
			arrDays[newElemForm.value] = arrTimes[newElemForm.value];	
		}
	}
	if(numDay != startGroupObject.quantity){
		dispModalInformWindow("Запланировано неверноe количество занятий в цикле. Исправьте данные в форме.")
		return false;
	}	
	for(day in arrDays){ 
		if(!arrDays[day]){ dispModalInformWindow("Выберите время проведения занятия."); return false;} 		
	}
	var theParam = "functionHandler=viewAutoStartGroup&idGroup=" + startGroupObject.id + "&periodicity=" + startGroupObject.periodicity;
	theParam += "&startData=" + document.forms.startTemetableForm.elements.startFactData.value;
	theParam += "&auditorias=" + document.forms.periodTamet.elements.auditorias.value + "&teacher=" + document.forms.periodTamet.elements.teacher.value; 	
	for(day in arrDays)	{ theParam += "&" + day + "=" + arrDays[day];  }
	var theUrl = "/ajaxStartGroups/autoStart";		
	setAjaxQuery(theUrl,theParam);
	return false;	
}
//=================================================================================
//ф-я подтверждения расписания
function viewAutoStartGroup(elel,flag){
	var closeButton = document.getElementById('closeButton');
	if (closeButton != null){  closeButton.onclick(); }
	var modalWindow = document.createElement('div');  
	modalWindow.className = "modalBodyWindow";	
	document.body.appendChild(modalWindow);
	modalWindow.innerHTML = "<p class='clearfix'><button type='button' id='closeButton' style='cursor:pointer;float:right;background:#FFA07A;'>close</button></p><hr><div id='timetablesResultDiv' class='dddd'></div>";
	var closeButton = document.getElementById('closeButton');	
	closeButton.onclick = function(){ modalWindow.parentNode.removeChild(modalWindow); }
	var timetablesResultDiv = document.getElementById('timetablesResultDiv');
	timetablesResultDiv.innerHTML = "<h2>Проверьте расписание группы " + startGroupObject.title + " и при необходимости внесите изменения.</h2>";
	timetablesResultDiv.innerHTML += "<form name='lastModifyTemetableForm' ><p><input type='button' name='go' onclick='addTemetable()' value='сохранить расписание в базе данных'></p></form>";
	if(flag === undefined){
		var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff');
		var countElement = nextStaff.length;
	}else{ var countElement = startGroupObject.numLesson; }	
	var table = document.createElement('table');
	table.innerHTML = "<tr><th>дата</th><th>время</th><th>аудитория</th><th>преподаватель</th><th>замечания</th></tr>";
	table.className = "temetableTable";
	document.forms.lastModifyTemetableForm.appendChild(table);
	for (var i = 0; i < countElement; i++){
		if(flag === undefined){
			var nextElement = JSON.parse(nextStaff[i].textContent);
		}else{ nextElement = { 'data':null,'time':null,'auditorias':null,'teacher':null }; }				
		var tr = document.createElement('tr');
		table.appendChild(tr);
		var message = (nextElement.message)?nextElement.message:'';
		tr.innerHTML = "<td><input type='date' name='data_"+i+"' value='"+nextElement.data+"' required></input></td><td><input type='time' name='time_"+i+"' value='"+nextElement.time+"' required></input></td>";
		tr.innerHTML += "<td><select name='auditorias_"+i+"' size='1'></select></td><td><select name='teacher_"+i+"' size='1'></select></td><td>" + message + "</td>";		
		for ( teacher in startTeacherObject){
			if(teacher == nextElement.teacher){
				var option = new Option(startTeacherObject[teacher], teacher, true, true);
			}else{ var option = new Option(startTeacherObject[teacher], teacher); }	
			var n = 'teacher_'+i;
			document.forms.lastModifyTemetableForm.elements[n].appendChild(option);
		}		
		for ( auditorias in startAuditoriasObject){
			if(auditorias == nextElement.auditorias){
				var option = new Option(startAuditoriasObject[auditorias], auditorias, true, true);
			}else{ var option = new Option(startAuditoriasObject[auditorias], auditorias); }			
			var n = 'auditorias_'+i;
			document.forms.lastModifyTemetableForm.elements[n].appendChild(option);		
		}		
	}
}
function addTemetable(){
	var theParam = "functionHandler=viewAutoStartGroup&idGroup=" + startGroupObject.id
	for( var i = 0; i < document.forms.lastModifyTemetableForm.elements.length; i++){
		var newElemForm = document.forms.lastModifyTemetableForm.elements[i];
		theParam += "&"	+ newElemForm.name + "=" + newElemForm.value;	
	}
	var theUrl = "/ajaxStartGroups/addTemetable";
	var closeButton = document.getElementById('closeButton');
	closeButton.onclick();
//	alert(theParam);
	setAjaxQuery(theUrl,theParam);
	return false;	
}
//=================================================================================
function myf_getStringData(dataObject){
	var month = dataObject.getMonth() + 1;
	if(month < 10){ month = '0' + month; }
	var day = dataObject.getDate();
	if(day < 10){ day = '0' + day; }
	var todayDataStr = dataObject.getFullYear() + "-" + month + "-" + day;
	return todayDataStr;
}
//=======================================================================================
//Ф-я вывода формы для еженедельного расписания================================
function viewWeekForm(domElementForViewForm){
	domElementForViewForm.innerHTML = "<p>выберите дни для проведения занятий</p><form name='periodTamet' class='periodTametForm clearfix'></form>";
	var periodTamet = document.forms.periodTamet;
	periodTamet.innerHTML = "<div class='divWeek'><label><input type='checkbox'  value='1'></input><span>понедельник</span><input type='time' name='1'></input></label></div>";
	periodTamet.innerHTML += "<div class='divWeek'><label><input type='checkbox' value='2'></input><span>вторник</span><input type='time' name='2'></input></label>";
	periodTamet.innerHTML += "<div class='divWeek'><label><input type='checkbox'  value='3'></input><span>среда</span><input type='time' name='3'></input></label>";
	periodTamet.innerHTML += "<div class='divWeek'><label><input type='checkbox'  value='4'></input><span>четверг</span><input type='time' name='4'></input></label>";
	periodTamet.innerHTML += "<div class='divWeek'><label><input type='checkbox' value='5'></input><span>пятница</span><input type='time' name='5'></input></label>";
	periodTamet.innerHTML += "<div class='divWeek'><label><input type='checkbox' value='6'></input><span>суббота</span><input type='time' name='6'></input></label>";
	periodTamet.innerHTML += "<div class='divWeek'><label><input type='checkbox'  value='7'></input><span>воскресенье</span><input type='time' name='7'></input></label>";
	periodTamet.innerHTML += "<p class='clearBoth'><select name='teacher' size='1'><option value='0'></option></select> Выберите преподавателя для проведения занятия</p>";
	periodTamet.innerHTML += "<p class='clearBoth'><select name='auditorias' size='1'><option value='0'></option></select> Выберите аудиторию для проведения занятий</p>";
	periodTamet.innerHTML += "<p class='clearBoth'><input type='button' name='go' onclick='validateWeekTemetable()' value='составить расписание'></input></p>";
	for ( teacher in startTeacherObject){
		var option = document.createElement('option');
		option.value =  teacher;
		option.textContent = startTeacherObject[teacher];
		document.forms.periodTamet.elements.teacher.appendChild(option);
	}
	for ( auditorias in startAuditoriasObject){
		var option = document.createElement('option');
		option.value =  auditorias;
		option.textContent = startAuditoriasObject[auditorias];
		document.forms.periodTamet.elements.auditorias.appendChild(option);
	}
}
//Ф-я вывода формы для ежемесячного расписания================================
function viewMonthForm(domElementForViewForm){
	domElementForViewForm.innerHTML = "<p>выберите числа для проведения занятий</p><form name='periodTamet' class='periodTametForm clearfix'></form>";
	var periodTamet = document.forms.periodTamet;
	for(var i = 0; i < 5; i++){
		var divR = document.createElement('div');
		divR.className ="divR";
		periodTamet.appendChild(divR);
		for(var j = 1; j <= 6; j++){
			var divS = document.createElement('div');
			divS.className ="divS";
			var numDay = (i * 6) + j;
			divS.innerHTML = "<label><input type='checkbox'  value='"+numDay+"'></input><span>"+numDay+"</span><input type='time' name='"+numDay+"'></input></label>"
			divR.appendChild(divS);	
			if(numDay == 30){
				var divS = document.createElement('div');
				divS.className ="divS";
				divS.innerHTML = "<label><input type='checkbox'  value='31'></input><span>31</span><input type='time' name='31'></input></label>"
				divR.appendChild(divS);	
			}	
		}
	}
	var p = document.createElement('p');
	p.className = "clearBoth";
	p.innerHTML = "<select name='auditorias' size='1'><option value='0'></option></select> Выберите аудиторию для проведения занятий";
	periodTamet.appendChild(p);
	for ( auditorias in startAuditoriasObject){
		var option = document.createElement('option');
		option.value =  auditorias;
		option.textContent = startAuditoriasObject[auditorias];
		document.forms.periodTamet.elements.auditorias.appendChild(option);
	}
	var p = document.createElement('p');
	p.className = "clearBoth";
	p.innerHTML = "<select name='teacher' size='1'><option value='0'></option></select> Выберите преподавателя для проведения занятия";
	periodTamet.appendChild(p);
	for ( teacher in startTeacherObject){
		var option = document.createElement('option');
		option.value =  teacher;
		option.textContent = startTeacherObject[teacher];
		document.forms.periodTamet.elements.teacher.appendChild(option);
	}
	var p = document.createElement('p');
	p.className = "clearBoth";
	p.innerHTML = "<input type='button' name='go' onclick='validateWeekTemetable()' value='составить расписание'></input>";
	periodTamet.appendChild(p);
}
//Ф-я вывода формы для ежедневного расписания================================
function viewDayForm(domElementForViewForm){
	domElementForViewForm.innerHTML = "<p>выберите время для проведения ежедневных занятий</p><form name='periodTamet' class='periodTametForm clearfix'></form>";
	var periodTamet = document.forms.periodTamet;
	for(var i = 1; i <= startGroupObject.quantity; i++){
		var p = document.createElement('p');
		p.className = "strDay";
		p.innerHTML = "<input type='checkbox'  value='"+i+"' checked></input><input type='time' name='"+i+"'></input> Время занятия " + i;
		periodTamet.appendChild(p);
	}	
	var p = document.createElement('p');
	p.className = "clearBoth";
	p.innerHTML = "<select name='auditorias' size='1'><option value='0'></option></select> Выберите аудиторию для проведения занятий";
	periodTamet.appendChild(p);
	var p = document.createElement('p');
	for ( auditorias in startAuditoriasObject){
		var option = document.createElement('option');
		option.value =  auditorias;
		option.textContent = startAuditoriasObject[auditorias];
		document.forms.periodTamet.elements.auditorias.appendChild(option);
	}
	p.className = "clearBoth";
	p.innerHTML = "<select name='teacher' size='1'><option value='0'></option></select> Выберите преподавателя для проведения занятия";
	periodTamet.appendChild(p);
	for ( teacher in startTeacherObject){
		var option = document.createElement('option');
		option.value =  teacher;
		option.textContent = startTeacherObject[teacher];
		document.forms.periodTamet.elements.teacher.appendChild(option);
	}	
	var p = document.createElement('p');
	p.className = "clearBoth";
	p.innerHTML = "<input type='button' name='go' onclick='validateWeekTemetable()' value='составить расписание'></input>";
	periodTamet.appendChild(p);
}
//Ф-я вывода формы для ручного выбора расписания================================
function viewHandForm(domElementForViewForm){
	
}
//=====================================================================================================================================





















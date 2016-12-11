//alert('temetable');
var page = 1;
var asideAjaxGroupsContent = document.getElementById('asideAjaxGroupsContent');
asideAjaxGroupsContent.innerHTML = "";	
getTemetableList(1);
//====================================================================================

function viewTemetableList(){	
//	alert(myReq.responseText);
	page = responseXMLDocument.getElementsByTagName('page')[0].textContent;	
	asideAjaxGroupsContent.innerHTML = "";	
	var pageDomElement = responseXMLDocument.getElementsByTagName('pager');		
	if (pageDomElement.length){
		var pagerText = pageDomElement[0].textContent;
		var pager = JSON.parse(pagerText);		
		viewPager(pager,asideAjaxGroupsContent,page,getTemetableList);	
	}		
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff');
	if (nextStaff.length != 0){
		var table = document.createElement('table');
		table.innerHTML = "<tr><th>группа</th><th>специальность</th><th>дата</th><th>время</th><th>преподаватель</th></tr>";
		table.className = "resultTable";
		asideAjaxGroupsContent.appendChild(table);
		for (var i = 0; i < nextStaff.length; i++){
			var nextGroup = JSON.parse(nextStaff[i].textContent);	
			var tr = document.createElement('tr');
			tr.onclick = getPerson;
			tr.setAttribute('idTemet', nextGroup.id);
			tr.innerHTML = "<td>"+nextGroup.groupTitle+"</td><td>"+nextGroup.cpecTitle+"</td><td>"+nextGroup.dataPlan+"</td><td>"+nextGroup.timePlan+"</td><td>"+nextGroup.teacherSurname+"</td>";			
			table.appendChild(tr);
		}
	}		
}

//===========================================================================================================
function getPerson(){	
	var idTemet = this.getAttribute('idTemet');
	var theUrl = "/ajaxTemetable/personalTemetable";
	var theParam = "functionHandler=viewpersonalTemetableList&idTemet=" + idTemet;			
	setAjaxQuery(theUrl,theParam);
}
function viewpersonalTemetableList(){
//	alert(myReq.responseText);
	var personalData = document.getElementById('personalData');
	personalData.innerHTML = "<hr><h2>Персональные данные занятия<h2>";
	var nextCpec = responseXMLDocument.getElementsByTagName('nextCpec');
	if(nextCpec.length != 0){	
		cpecialitys = JSON.parse(nextCpec[0].textContent);		
		var arrDataForTableStudent = {'title':'специальность'};
		viewTablePesonalData(cpecialitys,arrDataForTableStudent,personalData);	
	}
	var nextGroup = responseXMLDocument.getElementsByTagName('nextGroup');
	if(nextGroup.length != 0){	
		group = JSON.parse(nextGroup[0].textContent);		
		var arrDataForTableStudent = {'title':'название группы','numLesson':'К-во занятий у группы','startDataFact':'Старт обучения факт'};
		viewTablePesonalData(group,arrDataForTableStudent,personalData);	
	}
	var nextAuditorias = responseXMLDocument.getElementsByTagName('nextAuditorias');
	if(nextAuditorias.length != 0){	
		auditotias = JSON.parse(nextAuditorias[0].textContent);		
		var arrDataForTableStudent = {'title':'аудитория','adress':'адрес'};
		viewTablePesonalData(auditotias,arrDataForTableStudent,personalData);	
	}
	var nextTemetable = responseXMLDocument.getElementsByTagName('nextTemetable');	
	if(nextTemetable.length != 0){	
		lesson = JSON.parse(nextTemetable[0].textContent);		
		var arrDataForTableStudent = {'dataPlan':'дата занятия','timePlan':'время занятия','duration':'продолжительность занятия,часов','teacherSurname':'преподаватель','theme':'тема занятия'};
		viewTablePesonalData(lesson,arrDataForTableStudent,personalData);	
	}
	var p = document.createElement('p');
	p.innerHTML = "<hr><p><a href='/lesson/newlesson/" + lesson.id + "'><span>провести это занятие</span></a></p><hr>";
	personalData.appendChild(p);
}







//alert("Basys");
var page = 1;
var startGroupObject = null;
var asideAjaxGroupsContent = document.getElementById('asideAjaxGroupsContent');
asideAjaxGroupsContent.innerHTML = "";	
getElementsList();
//====================================================================================
function getElementsList(pageData=1){
	var theUrl = "/ajaxGroups/arrGroup";
	var theParam = "functionHandler=viewElementList&status=work&page=" + pageData;			
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
	var theUrl = "/ajaxGroups/personalGroup";
	var theParam = "functionHandler=viewpersonalGroupData&idGroup=" + idGroup;			
	setAjaxQuery(theUrl,theParam);
}
function viewpersonalGroupData(){
	var personalData = document.getElementById('personalData');
	personalData.innerHTML = "<h2>Персональные данные группы<h2>";
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff')[0].textContent;
	if (nextStaff != null){
		var group = JSON.parse(nextStaff);
		startGroupObject = group;
		var arrDataForTableStudent = {'title':'название','price':'цена курса','countStudent':'число студентов','bossSurname':'руководитель группы','startDataPlan':'дата начала занятий,план','startDataFact':'дата начала занятий,факт','cpecTitle':'специальность','countStudent':'число студентов в группе'};
		viewTablePesonalData(group,arrDataForTableStudent,personalData);
	}
	getEndingInfo();	
}
//==============================================================================================================
function getEndingInfo(){	
	var theUrl = "/ajaxTemetable/temetebleByIdGroup";
	var theParam = "functionHandler=viewEndingInfo&idGroup=" + startGroupObject.id;
	setAjaxQuery(theUrl,theParam);
}
function viewEndingInfo(){
//	alert(myReq.responseText);
	var personalData = document.getElementById('personalData');
	var totalLesson = responseXMLDocument.getElementsByTagName('totalLesson');
	if(totalLesson.length != 0){
		var totalLessonText = totalLesson[0].textContent;
		if (totalLessonText != null){ var lessonInfo = JSON.parse(totalLessonText); }	
	}	
	var p = document.createElement('p');
	p.textContent = "Было запланированно " + lessonInfo.allLessonPlan + " занятий.Из них проведено " + lessonInfo.allLessonFact;
	personalData.appendChild(p);
	var form = 	document.createElement('form');
	form.name = 'endingGroup';
	personalData.appendChild(form);
	form.innerHTML = "<p>Как поступать со студентами?</p>";
	form.innerHTML += "<p><label><input type='radio' name='variant' value='student'>оставить в списках студентов</input></label><label><input type='radio' name='variant' value='graduate' checked>перевести в число выпускников</input></label></p>"
	form.innerHTML += "<p><input type='button' name='go' onclick='endingTeachingGroup()' value='закончить занятия группы'></input></p>";
}
function endingTeachingGroup(){
	var variant = (document.forms.endingGroup.elements.variant[0].checked)?'student':'graduate';
	var theUrl = "/ajaxGroups/endingGroup";
	var theParam = "functionHandler=getElementsList&idGroup=" + startGroupObject.id + "&variant=" + variant;
	setAjaxQuery(theUrl,theParam);	
}








//alert("teachers");
var page = 1;
var asideAjaxGroupsContent = document.getElementById('asideAjaxGroupsContent');
asideAjaxGroupsContent.innerHTML = "";	
getTeacherstList(1);

//====================================================================================
function getTeacherstList(pageData){
	var theUrl = "/ajaxTeachers/teachersList";
	var theParam = "functionHandler=viewTeachersList&page=" + pageData;			
	setAjaxQuery(theUrl,theParam);
}
function viewTeachersList(){
//	alert(myReq.responseText);
	page = responseXMLDocument.getElementsByTagName('page')[0].textContent;
	asideAjaxGroupsContent.innerHTML = "";
	var pageDomElement = responseXMLDocument.getElementsByTagName('pager')
	if (pageDomElement.length){
		var pagerText = pageDomElement[0].textContent;
		var pager = JSON.parse(pagerText);
		viewPager(pager,asideAjaxGroupsContent,page,getTeacherstList);	
	}
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff');
	if (nextStaff.length != 0){
		var table = document.createElement('table');
		table.innerHTML = "<tr><th>фамилия</th><th>имя</th><th>телефон</th><th>дата роджения</th><th>skype</th></tr>";
		table.className = "resultTable";
		asideAjaxGroupsContent.appendChild(table);
		for (var i = 0; i < nextStaff.length; i++){
			var nextGroup = JSON.parse(nextStaff[i].textContent);	
			var tr = document.createElement('tr');
			tr.onclick = getPerson;
			tr.setAttribute('idTeach', nextGroup.id);
			tr.innerHTML = "<td>"+nextGroup.surname+"</td><td>"+nextGroup.name+"</td><td>"+nextGroup.telephon+"</td><td>"+nextGroup.birthday+"</td><td>"+nextGroup.skype+"</td>";			
			table.appendChild(tr);
		}
	}	
}

//===========================================================================================================
function getPerson(){
	var idTeach = this.getAttribute('idTeach');
	var theUrl = "/ajaxTeachers/personalTeacher";
	var theParam = "functionHandler=viewpersonalTecherList&idTeach=" + idTeach;			
	setAjaxQuery(theUrl,theParam);
}
function viewpersonalTecherList(){
//	alert(myReq.responseText);
	var personalData = document.getElementById('personalData');
	personalData.innerHTML = "<h2>Персональные данные преподавателя<h2>";
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff')[0].textContent;
	if (nextStaff != null){
		var teacher = JSON.parse(nextStaff);
		var arrDataForTableStudent = {'name':'имя','surname':'фамилия','birthday':'Дата рождения','telephon':'телефон','adress':'адрес','email':'email','skype':'skype','status':'статус преподавателя'};
		viewTablePesonalData(teacher,arrDataForTableStudent,personalData);
	}
	var nextGroup = responseXMLDocument.getElementsByTagName('nextGroup');
	if(nextGroup.length != 0){
		var h2 = document.createElement('h2');
		h2.textContent = teacher.surname + " " + teacher.name + " является руководителем в группах: ";
		personalData.appendChild(h2);
		for (var i = 0; i < nextGroup.length; i++){
			var group = JSON.parse(nextGroup[i].textContent);
			var p = document.createElement('h2');
			p.textContent = group.title;
			personalData.appendChild(p);
		}		
	}
}












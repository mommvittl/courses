//alert("student");
var page = 1;
var asideAjaxGroupsContent = document.getElementById('asideAjaxGroupsContent');
asideAjaxGroupsContent.innerHTML = "";	
getStudentList(1);
//====================================================================================
function getStudentList(pageData){
	var theUrl = "/ajaxStudents/studentList";
	var theParam = "functionHandler=viewStudentsList&page=" + pageData;			
	setAjaxQuery(theUrl,theParam);
}
function viewStudentsList(){
//	alert(myReq.responseText);
	page = responseXMLDocument.getElementsByTagName('page')[0].textContent;
	asideAjaxGroupsContent.innerHTML = "";
	var pageDomElement = responseXMLDocument.getElementsByTagName('pager');	
	if (pageDomElement.length){
		var pagerText = pageDomElement[0].textContent;
		var pager = JSON.parse(pagerText);
		viewPager(pager,asideAjaxGroupsContent,page,getStudentList);	
	}
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff');
	if (nextStaff.length != 0){
		var table = document.createElement('table');
		table.innerHTML = "<tr><th>фамилия</th><th>имя</th><th>телефон</th><th>дата роджения</th><th>договор</th></tr>";
		table.className = "resultTable";
		asideAjaxGroupsContent.appendChild(table);
		for (var i = 0; i < nextStaff.length; i++){
			var nextGroup = JSON.parse(nextStaff[i].textContent);	
			var tr = document.createElement('tr');
			tr.onclick = getPerson;
			tr.setAttribute('idStud', nextGroup.id);
			tr.innerHTML = "<td>"+nextGroup.surname+"</td><td>"+nextGroup.name+"</td><td>"+nextGroup.telephon+"</td><td>"+nextGroup.birthday+"</td><td>"+nextGroup.dogovor+"</td>";			
			table.appendChild(tr);
		}
	}	
}

//===========================================================================================================
function getPerson(){
	var idStud = this.getAttribute('idStud');
	var theUrl = "/ajaxStudents/personalStudent";
	var theParam = "functionHandler=viewpersonalStudentList&idStud=" + idStud;			
	setAjaxQuery(theUrl,theParam);
}

function viewpersonalStudentList(){
	var personalData = document.getElementById('personalData');
	personalData.innerHTML = "<h2>Персональные данные студента<h2>";
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff')[0].textContent;
	if (nextStaff != null){
		var student = JSON.parse(nextStaff);
		var arrDataForTableStudent = {'name':'имя','surname':'фамилия','birthday':'Дата рождения','telephon':'телефон','adress':'адрес','email':'email','skype':'skype','kharacteristica':'характеристика','dogovor':'договор'};
		viewTablePesonalData(student,arrDataForTableStudent,personalData);
	}
	var nextCpec = responseXMLDocument.getElementsByTagName('nextCpec')[0].textContent;
	if (nextCpec != null){
		var h2 = document.createElement('h2');
		h2.textContent = "Данные группы где учится студент";
		personalData.appendChild(h2);
		var groupCpec = JSON.parse(nextCpec);
		var arrDataForTableStudent = {'cpecTitle':'специальность','cpecBoss':'руководитель курса','title':'название группы','status':'статус группы','numLesson':'К-во занятий у группы','groupBoss':'Руководитель группы','price':'цена обучения','startDataPlan':'Старт обучения план','startDataFact':'Старт обучения факт','endDataPlan':'Окончание обучения'};
		viewTablePesonalData(groupCpec,arrDataForTableStudent,personalData);
	}
}






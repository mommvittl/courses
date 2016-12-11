//alert("Basys");
var cpecForgroupList = document.forms.groupStudentSelectCpec.elements.cpecForgroupList;
//cpecForgroupList.oninput = getGroupListByCpecialitys;
cpecForgroupList.oninput = function(){ getGroupListByCpecialitys(); };
getGroupListByCpecialitys();
var arrayStudents = [];
var groupsObject;
var page = 1;
//========================================================================================================
function getGroupListByCpecialitys(pageData=1){

	var theUrl = "/ajaxGroups/arrGroup";
	var theParam = "functionHandler=viewGroupListData&cpecialitys=" + cpecForgroupList.value + "&page=" + pageData;		
//	alert(theParam);	
	setAjaxQuery(theUrl,theParam);	
}
function viewGroupListData(responseXMLDocument){
//	alert(myReq.responseText);
	var asideAjaxGroupsContent = document.getElementById('asideAjaxGroupsContent');
	asideAjaxGroupsContent.innerHTML = "";	
	var nextCpec = responseXMLDocument.getElementsByTagName('nextCpec')[0].textContent;	
	if(nextCpec != 0){
		var cpec = responseXMLDocument.getElementsByTagName('cpecialitys')[0].textContent;		
		var cpecialitys = JSON.parse(cpec);
		var p = document.createElement('p');
		p.textContent = "Специальность: " + cpecialitys.title;
		asideAjaxGroupsContent.appendChild(p);
		var p = document.createElement('p');
		p.textContent = "Базовая цена курса: " + cpecialitys.priseBasis;
		asideAjaxGroupsContent.appendChild(p);
		var p = document.createElement('p');
		cpecialitysQuantity = cpecialitys.quantity
		p.textContent = "Кол-во учебных часов курса: " + cpecialitysQuantity;
		asideAjaxGroupsContent.appendChild(p);
		var p = document.createElement('p');
		p.textContent = "Руководитель курса: " + cpecialitys.bossSurname;
		asideAjaxGroupsContent.appendChild(p);		
	}		
	page = responseXMLDocument.getElementsByTagName('page')[0].textContent;	
	var pagerElem = responseXMLDocument.getElementsByTagName('pager');
	if(pagerElem.length != 0){
		var pagerText = pagerElem[0].textContent;
		if (pagerText != null){
			var pager = JSON.parse(pagerText);
			viewPager(pager,asideAjaxGroupsContent,page,getGroupListByCpecialitys);	
		}	
	}
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff');
	var table = document.createElement('table');
	table.innerHTML = "<tr><th>Название</th><th>Дата старта план</th><th>Дата старта факт</th></tr>";
	table.className = "resultTable";
	asideAjaxGroupsContent.appendChild(table);
	for (var i = 0; i < nextStaff.length; i++){
		var nextGroup = JSON.parse(nextStaff[i].textContent);		
		var tr = document.createElement('tr');
		tr.setAttribute('idGroup', nextGroup.id) ;
		tr.innerHTML = "<td>"+nextGroup.title+"</td><td>"+nextGroup.startDataPlan+"</td><td>"+nextGroup.endDataPlan+"</td>";
		table.appendChild(tr);
		tr.onclick = getNewGroupList;
	}
}
//=============================================

function getNewGroupList(){
	var idGroup = this.getAttribute('idGroup');
	var theUrl = "/ajaxGroups/groupList";
	var theParam = "functionHandler=viewStudentsListData&idGroup=" + idGroup;			
	setAjaxQuery(theUrl,theParam);	
}
function viewStudentsListData(){
	var personalData = document.getElementById('personalData');
	personalData.innerHTML = "";
	var nextCpec = responseXMLDocument.getElementsByTagName('nextCpec')[0].textContent;	
	if(nextCpec != 0){
		var group = JSON.parse(nextCpec);
		groupsObject = group;
		var table = document.createElement('table');
		table.className = "personalTable";
		table.innerHTML = "<tr><td>название группы</td><td>" + group.title + "</td></tr>";
		table.innerHTML += "<tr><td>Руководитель группы</td><td>" + group.bossSurname + "</td></tr>";
		table.innerHTML += "<tr><td>статус</td><td>" + group.status + "</td></tr>";
		table.innerHTML += "<tr><td>Количество занятий группы</td><td>" + group.numLesson + "</td></tr>";		
		personalData.appendChild(table);
	}
	var but = document.createElement('button');
	but.textContent = "Добавить студентов в группу";
	but.type = "button";
	but.className = "addButton";
	but.onclick = function(){ getStudentsInGroup(1); }
	personalData.appendChild(but);
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff');
	if (nextStaff.length != 0){
		var table = document.createElement('table');
		table.innerHTML = "<tr><th>фамилия</th><th>имя</th><th>телефон</th><th>Email</th><th>skype</th><th>статус</th><th></th><th></th></tr>";
		table.className = "resultTable";
		personalData.appendChild(table);
		arrayStudents = [];
		for (var i = 0; i < nextStaff.length; i++){
			var nextGroup = JSON.parse(nextStaff[i].textContent);
			arrayStudents[i] = nextGroup;	
			var tr = document.createElement('tr');
			tr.setAttribute('idStud', nextGroup.id) ;
			tr.setAttribute('idInArr',i) ;
			tr.innerHTML = "<td>"+nextGroup.surname+"</td><td>"+nextGroup.name+"</td><td>"+nextGroup.telephon+"</td><td>"+nextGroup.email+"</td><td>"+nextGroup.skype+"</td><td>"+nextGroup.status+"</td>";
			tr.innerHTML += "<td class='delete'><button onclick='modalUpdateWindow(\"updateStudentsInGroup("+nextGroup.id+")\")' class='addButton'>перевести</button></td>";
			tr.innerHTML += "<td class='delete'><button onclick='modalDeleteWindow(\"deleteStudentsInGroup("+nextGroup.id+")\")' class='deletebutton'>отчислить</button></td>";
			table.appendChild(tr);
		}
//		tr.onclick = getNewGroupList;
	}	
}
//==============================================================================================
function getStudentsInGroup(pageData){	
	var theUrl = "/ajaxGroups/studentList";
	var theParam = "functionHandler=viewSdudentListForAddToGroup&page=" + pageData;			
	setAjaxQuery(theUrl,theParam);		  
}

function viewSdudentListForAddToGroup(){
	page = responseXMLDocument.getElementsByTagName('page')[0].textContent;
	var asideAjaxGroupsContent = document.getElementById('asideAjaxGroupsContent');
	var closeButton = document.getElementById('closeButton');
	if (closeButton != null){  closeButton.onclick(); }
	var modalWindow = document.createElement('div');  
	modalWindow.className = "modalWindow";	
	asideAjaxGroupsContent.appendChild(modalWindow);
	modalWindow.innerHTML = "<p class='clearfix'><button type='button' id='closeButton' style='cursor:pointer;float:right;background:#FFA07A;'>close</button></p><hr>";
	var closeButton = document.getElementById('closeButton');	
	closeButton.onclick = function(){ modalWindow.parentNode.removeChild(modalWindow); }
	var pagerElem = responseXMLDocument.getElementsByTagName('pager');
	if (pagerElem.length != 0){
		var pagerText = pagerElem[0].textContent;
		var pager = JSON.parse(pagerText);
		viewPager(pager,modalWindow,page,getStudentsInGroup);	
	}
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff');
	if (nextStaff.length != 0){
		var form = document.createElement('form');
		form.name = 'addStudentInGroupForm';
		form.innerHTML = "<p><input type='reset'></input><input type='button' value='Добавить студентов в группу' onclick='addStudentsInGroup()'></input></p>";
		modalWindow.appendChild(form);
		form.onsubmit = addStudentsInGroup;
		var table = document.createElement('table');
		table.innerHTML = "<tr><th>фамилия</th><th>имя</th><th>дата рожд.</th><th>телефон</th><th>Email</th><th>skype</th><th>Договор</th<th></th</tr>";
		table.className = "listElementTable";
		form.appendChild(table);
		for (var i = 0; i < nextStaff.length; i++){
			var nextGroup = JSON.parse(nextStaff[i].textContent);
			var tr = document.createElement('tr');
			tr.setAttribute('idStud', nextGroup.id) ;
			tr.innerHTML = "<td>"+nextGroup.surname+"</td><td>"+nextGroup.name+"</td><td>"+nextGroup.birthday+"</td><td>"+nextGroup.telephon+"</td><td>"+nextGroup.email+"</td><td>"+nextGroup.skype+"</td><td>"+nextGroup.dogovor+"</td>";
			tr.innerHTML += "<td><input type='checkbox' value='" + nextGroup.id + "'></input></td>";
			table.appendChild(tr);
		}
		
	}
}
function addStudentsInGroup(){
	var theUrl = "/ajaxGroups/addStudentInGroupForm";
	var theParam = "functionHandler=viewStudentsListData&idGroup=" + groupsObject.id + "&arrStudents=" + arrElementForAdd;			
	var arrElementForAdd = [];	
	for( var i = 0; i < document.forms.addStudentInGroupForm.elements.length; i++){		
		var newElemForm = document.forms.addStudentInGroupForm.elements[i];		
		if (newElemForm.type == "checkbox" && newElemForm.checked == true){
			theParam += "&" + newElemForm.value + "=" + newElemForm.value;
		}		
	}	
	
	setAjaxQuery(theUrl,theParam);	
	closeButton.onclick();
	return false;	
}

//=================================================================================================
function deleteStudentsInGroup(idStud){
	var theUrl = "/ajaxGroups/deleteStudent";
	var theParam = "functionHandler=viewStudentsListData&idGroup=" + groupsObject.id + "&idStud=" + idStud;			
	setAjaxQuery(theUrl,theParam);	
}
//==================================================================================================
function updateStudentsInGroup(idStud){
//	alert(idStud);
	var theUrl = "/ajaxGroups/updateStudent";
	var theParam = "functionHandler=viewStudentsListData&idGroup=" + groupsObject.id + "&idStud=" + idStud;			
	setAjaxQuery(theUrl,theParam);		
}
//========================================================================================================










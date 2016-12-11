//alert("Basys");
var page = 1;
var asideAjaxGroupsContent = document.getElementById('asideAjaxGroupsContent');
asideAjaxGroupsContent.innerHTML = "";	
getElementsList();
//====================================================================================
function getElementsList(pageData=1){
	var theUrl = "/ajaxGroups/arrGroup";
	var theParam = "functionHandler=viewElementList&cpecialitys=0&page=" + pageData;			
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
		table.innerHTML = "<tr><th>название группы</th><th>цена курса</th><th>число занятий</th><th>статус</th></tr>";
		table.className = "resultTable";
		asideAjaxGroupsContent.appendChild(table);
		for (var i = 0; i < nextStaff.length; i++){
			var nextGroup = JSON.parse(nextStaff[i].textContent);	
			var tr = document.createElement('tr');
			tr.onclick = getPerson;
			tr.setAttribute('idGroup', nextGroup.id);
			tr.innerHTML = "<td>"+nextGroup.title+"</td><td>"+nextGroup.price+"</td><td>"+nextGroup.numLesson+"</td><td>"+nextGroup.status+"</td>";			
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
		var student = JSON.parse(nextStaff);
		var arrDataForTableStudent = {'title':'название','price':'цена курса','numLesson':'число занятий','countStudent':'число студентов','status':'статус','duration':'длительность одного занятия','bossSurname':'руководитель группы','startDataPlan':'дата начала занятий,план','startDataFact':'дата начала занятий,факт','endDataPlan':'дата окончания занятий,план','cpecTitle':'специальность','countStudent':'число студентов в группе'};
		viewTablePesonalData(student,arrDataForTableStudent,personalData);
	}
	
}














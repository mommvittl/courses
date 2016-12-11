//alert( document.cookie );
//alert( getCookie('PHPSESSID') );
var addGroupsForm = document.forms.addGroupsForm;
var idCpecial = addGroupsForm.elements.idCpecial;
idCpecial.oninput = getNewCpecData;
var periodicity = addGroupsForm.elements.periodicity;
periodicity.oninput = viewQuantity;
var quantity = addGroupsForm.elements.quantity;
quantity.oninput = viewQuantity;
var cpecialitysQuantity = 0;
var duration = addGroupsForm.elements.duration;
duration.oninput = viewQuantity;
var startDataPlan = addGroupsForm.elements.startDataPlan;
startDataPlan.oninput = viewQuantity;
getNewCpecData();

function  viewQuantity(){	
	if((duration.value > 0) && (quantity.value > 0) && (cpecialitysQuantity > 0) ){
		var numberLesson = Math.ceil(cpecialitysQuantity / duration.value);
		var lenLessonPeriod = Math.ceil(numberLesson / quantity.value);
		var param = { "day":"дней","week":"недель","month":"месяцев" };
		var asideAjaxQuantityContent = document.getElementById('asideAjaxQuantityContent');
		asideAjaxQuantityContent.innerHTML = "<p>Для выбранных параметров:</p>";
		var p = document.createElement('p');
		p.textContent = "Число занятий: " + numberLesson;
		asideAjaxQuantityContent.appendChild(p);
		var p = document.createElement('p');
		p.textContent = "Продолжительность курса: " + lenLessonPeriod + " " + param[periodicity.value] ;
		asideAjaxQuantityContent.appendChild(p);
		if(startDataPlan.value != "" ){
			var myDate = new Date(startDataPlan.value);
			if(periodicity.value == 'day'){
				myDate.setDate(myDate.getDate() + lenLessonPeriod );
			}else if(periodicity.value == 'month'){
				myDate.setMonth(myDate.getMonth() + lenLessonPeriod);
			}else if(periodicity.value == 'week'){
				myDate.setMonth(myDate.getMonth() + Math.ceil(lenLessonPeriod / 4 ));
			}
			var asideAjaxDateContent = document.getElementById('asideAjaxDateContent');
			asideAjaxDateContent.innerHTML = "<p>Ориентировочная дата окончания занятий группы:</p>";
			var p = document.createElement('p');
			p.textContent = myDate.toDateString();
			asideAjaxDateContent.appendChild(p);
			var s = myDate.getMonth();
			s++;
			var endDate = myDate.getFullYear() + "-" + s + "-" + myDate.getDate();
			var endDataPlan = addGroupsForm.elements.endDataPlan;
			endDataPlan.value = endDate;
			var numLesson = addGroupsForm.elements.numLesson;
			numLesson.value = numberLesson;
		}
		
	}
};
//===============================================================================
function getNewCpecData(){
	if (idCpecial.value > 0){
		var theUrl = "/ajaxGroups/newCpec";
		var theParam = "functionHandler=viewNewCpecData&cpecialitys=" + idCpecial.value;			
		setAjaxQuery(theUrl,theParam);		
	}	
};
function viewNewCpecData(responseXMLDocument){
	var nextStaff = responseXMLDocument.getElementsByTagName('nextStaff')[0].textContent;		
	var cpecialitys = JSON.parse(nextStaff);
//	alert("RESPONSE : " + cpecialitys.title);
	var asideAjaxCpecialitysContent = document.getElementById('asideAjaxCpecialitysContent');
	asideAjaxCpecialitysContent.innerHTML = "";
	var p = document.createElement('p');
	p.textContent = "Специальность: " + cpecialitys.title;
	asideAjaxCpecialitysContent.appendChild(p);
	var p = document.createElement('p');
	p.textContent = "Базовая цена курса: " + cpecialitys.priseBasis;
	asideAjaxCpecialitysContent.appendChild(p);
	var p = document.createElement('p');
	cpecialitysQuantity = cpecialitys.quantity
	p.textContent = "Кол-во учебных часов курса: " + cpecialitysQuantity;
	asideAjaxCpecialitysContent.appendChild(p);
	var p = document.createElement('p');
	p.textContent = "Руководитель курса: " + cpecialitys.bossSurname;
	asideAjaxCpecialitysContent.appendChild(p);
	viewQuantity();
};
//===================================================================================



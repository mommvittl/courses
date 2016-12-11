//====================================================================================
function getTemetableList(pageData){
	var arrParam = { 'status':'plan','id_teacherPlan':'3','workGroupFlag':'work' };
	var strArrParam = JSON.stringify(arrParam);
	var theUrl = "/ajaxTemetable/arrTemetableByArrParam";
	var theParam = "functionHandler=viewTemetableList&arrParam=" + strArrParam + "&page=" + pageData ;			
	setAjaxQuery(theUrl,theParam);
}





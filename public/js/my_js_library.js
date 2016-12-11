var myReq = getXMLHttpRequest();
var responseXMLDocument;
// перехват onclick на ссылках. Чтобы повесить перехват, нужно присвоить ссылке класс intercep
// при выборе Ок в окне - переход по адресу, указанному в св-ве href тега А.
var intercep = document.getElementsByClassName('intercep');
	for (var i = 0; i <intercep.length; i++) 
	{ intercep[i].onclick = myf_modal_window; }		
	function myf_modal_window()
		{
	//	var ll = this.getAttribute('href');
		var modal = document.createElement('div');  		
		modal.innerHTML = "<h1>Вы уверены, что хотите удалить элемент?</h1><button id=\"ok_but\">OK</button><button id=\"cancel_but\" autofocus>Cancel</button></p>";
		document.body.insertBefore(modal, document.body.firstChild);	
		var ok_but = document.getElementById('ok_but');
		var cancel_but = document.getElementById('cancel_but');	
		modal.style.cssText="width:600px; max-width: 100%; padding:10px; background:#F4A460; color:#800000; text-align:center; font: 1em/2em arial; border: 4px solid #A52A2A; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%);";
		ok_but.style.cssText="border-radius:10px; padding: 10px 20px; background:#FFE4E1; cursor:pointer; outline:none; margin-right: 20px;";
		cancel_but.style.cssText="border-radius:10px; padding: 10px 20px; background:#F5F5DC; cursor:pointer; outline:none; margin-left: 20px;";
			
		cancel_but.onclick = function() 
			{ document.body.removeChild(modal); };			
		ok_but.onclick = function() 
			{
			document.body.removeChild(modal);
			return true;
	//		window.location = ll;				
			};		
		return false;
		};
//------------------------------------------------------------------------------------------	
// ===== Ajax ===создание===обьекта====XMLHttpRequest===========
	function getXMLHttpRequest()
		{
		var req;
		if(window.ActiveXObject){
			try{
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e){
				req = false;
			}
		}else{
			try{
				req = new XMLHttpRequest();
			}
			catch (e){
				req = false;
			}
		}
		if( !req ){
			alert("Error - ошибка  1 создания обьекта XMLHttpRequest");
		}else{
			return req;	
		}			
		};
// ---------------------------------------------------------------
//====функция====вывода===модального==Error===окна================
	function dispModalErrorWindow(stringError){
		var modalErrorWindow = document.createElement('div');
		modalErrorWindow.innerHTML = "<h1>Sorry....Error...</h1><hr/><h2>"+stringError+"</h2>";
		document.body.insertBefore(modalErrorWindow, document.body.firstChild);
		modalErrorWindow.style.cssText="width:800px;max-width: 100%;height: 800px;max-height: 100%;cursor:pointer;padding:10px;background:#F4A460;color:#800000;text-align:center;font: 1em/2em arial;border: 4px solid #A52A2A;position:fixed;z-index: 1000;top:50%;left:50%;transform:translate(-50%, -50%);box-shadow: 6px 6px #14536B;";
		modalErrorWindow.onclick = function(){
			document.body.removeChild(modalErrorWindow);
		}
	};
//----------------------------------------------------------------
//====функция====вывода===модального==Inform===окна================
	function dispModalInformWindow(stringInform){
		var modalInformWindow = document.createElement('div');
		modalInformWindow.innerHTML = "<h1>"+stringInform+"</h1>";
		document.body.insertBefore(modalInformWindow, document.body.firstChild);
		modalInformWindow.style.cssText="width:600px;max-width: 100%;height: 600px;max-height: 100%;cursor:pointer;padding:10px;background:#7A96A1;color:#FFFFF0;text-align:center;font: 1em/2em arial;border: 4px double #1E0D69;position:fixed;z-index: 1000;top:50%;left:50%;transform:translate(-50%, -50%);box-shadow: 6px 6px #14536B;";
		modalInformWindow.onclick = function(){
			document.body.removeChild(modalInformWindow);
			
		}
	};
//----------------------------------------------------------------	
	function dispModalQuestion()
		{
		var modal = document.createElement('div');  		
		modal.innerHTML = "<h1>Вы уверены, что хотите удалить элемент?</h1><button id=\"ok_but\">OK</button><button id=\"cancel_but\" autofocus>Cancel</button></p>";
		document.body.insertBefore(modal, document.body.firstChild);	
		var ok_but = document.getElementById('ok_but');
		var cancel_but = document.getElementById('cancel_but');	
		modal.style.cssText="width:600px; max-width: 100%; padding:10px; background:#F4A460; color:#800000; text-align:center; font: 1em/2em arial; border: 4px solid #A52A2A; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%);box-shadow: 6px 6px #14536B;";
		ok_but.style.cssText="border-radius:10px; padding: 10px 20px; background:#FFE4E1; cursor:pointer; outline:none; margin-right: 20px;";
		cancel_but.style.cssText="border-radius:10px; padding: 10px 20px; background:#F5F5DC; cursor:pointer; outline:none; margin-left: 20px;";		
		cancel_but.onclick = function() 
			{ document.body.removeChild(modal); };			
		ok_but.onclick = function() 
			{
			document.body.removeChild(modal);
			return true;			
			};		
		return false;
		};
//------------------------------------------------------------------------------------------
//======функция====отпр.===ajax===POST===запроса================
	function setAjaxQuery(theUrl,theParam){
//		alert(theUrl + " , " + theParam);
	if(myReq){
		if(myReq.readyState == 4 || myReq.readyState == 0){
			myReq.open("POST",theUrl,true);
			myReq.onreadystatechange = getAjaxResponse;
			myReq.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			myReq.setRequestHeader("Content-length",theParam.length);
			myReq.setRequestHeader("Connection","close");
			myReq.send(theParam);
		}else{
			setTimeout('setAjaxQuery()',1000);	
		}
	}else{
		alert("Error - ошибка 2 создания обьекта XMLHttpRequest");
	};
	};
//--------------------------------------------------------------

//======функция====разбора===входящего==XMLDocument==============
//==варианты==принимаемых===корневых===тегов:====================
//==<response>==<error>==<underreporting>========================
//==var myReq = getXMLHttpRequest();==вставляем в начало скрипта
//==var responseXMLDocument;==обьявляем переменную через кот передаем XML документ на обработку
//Если отклик правильный то ф-я вызывает обработчик имя которого передано из сервера в теге <functionHandler>
//туда передаем корневой элемент XML - т.е. <response>
	function getAjaxResponse(){
		
	if(myReq.readyState == 4 ){
		if(myReq.status == 200){
			var theXMLresponseDoc = myReq.responseXML;
			if (!theXMLresponseDoc || !theXMLresponseDoc.documentElement) {
				alert("Неверная структура документа XML .  " + myReq.responseText);
			}else{
//				alert(myReq.responseText);
				firstNodeName = theXMLresponseDoc.childNodes[0].tagName;
				switch (firstNodeName) {
				  case 'response':
					var theRootXMLresponseTag = theXMLresponseDoc.childNodes[0];
					var functionNameHandler = theXMLresponseDoc.getElementsByTagName('functionHandler')[0].textContent;					
					responseXMLDocument = theXMLresponseDoc;
					var handlerXMLresponseName = functionNameHandler+"(responseXMLDocument)";	
					setTimeout(handlerXMLresponseName,0);		 		
					break;
				  case 'underreporting':
//					var functionNameHandler = theXMLresponseDoc.getElementsByTagName('functionHandler')[0].textContent;
					dispModalInformWindow(theXMLresponseDoc.childNodes[0].textContent);
					break;
				  case 'error':
//					var functionNameHandler = theXMLresponseDoc.getElementsByTagName('functionHandler')[0].textContent;
					dispModalErrorWindow(theXMLresponseDoc.childNodes[0].textContent);					
					break;
				}
			}
		}
	}	
};
//--------------------------------------------------------------
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}//=============================================================================
//В ф-ю передаем строку с именем(и если нужно параметрами) ф-ии куда переходить если ДА
	function modalDeleteWindow(functNameForLocation){			
		var modal = document.createElement('div');  		
		modal.innerHTML = "<h1>Вы уверены, что хотите удалить этот элемент?</h1><button id=\"ok_but\">OK</button><button id=\"cancel_but\" autofocus>Cancel</button></p>";
		document.body.insertBefore(modal, document.body.firstChild);
		modal.style.cssText="width:500px;max-width: 100%;height: 200px;max-height: 100%;cursor:pointer;padding:10px;background:#F4A460;color:#800000;text-align:center;font: 1em/2em arial;border: 4px double #1E0D69;position:fixed;z-index: 1000;top:50%;left:50%;transform:translate(-50%, -50%);box-shadow: 6px 6px #14536B;"
		var ok_but = document.getElementById('ok_but');
		var cancel_but = document.getElementById('cancel_but');	
		ok_but.style.cssText="border-radius:10px; padding: 10px 20px; background:#FFE4E1; cursor:pointer; outline:none; margin-right: 20px;";	
		cancel_but.style.cssText="border-radius:10px; padding: 10px 20px; background:#F5F5DC; cursor:pointer; outline:none; margin-left: 20px;";
		
		cancel_but.onclick = function() 
			{ document.body.removeChild(modal); };			
		ok_but.onclick = function() 
			{
			document.body.removeChild(modal);
			setTimeout(functNameForLocation,0);				
			};	
		};
//------------------------------------------------------------------------------------------
//В ф-ю передаем строку с именем(и если нужно параметрами) ф-ии куда переходить если ДА
	function modalUpdateWindow(functNameForLocation){			
		var modal = document.createElement('div');  		
		modal.innerHTML = "<h1>Вы уверены, что хотите изменить элемент?</h1><button id=\"ok_but\">OK</button><button id=\"cancel_but\" autofocus>Cancel</button></p>";
		document.body.insertBefore(modal, document.body.firstChild);
		modal.style.cssText="width:500px;max-width: 100%;height: 200px;max-height: 100%;cursor:pointer;padding:10px;background:#BDBDBD;color:#3B3C1D;text-align:center;font: 1em/2em arial;border: 4px double #1E0D69;position:fixed;z-index: 1000;top:50%;left:50%;transform:translate(-50%, -50%);box-shadow: 6px 6px #14536B;"
		var ok_but = document.getElementById('ok_but');
		var cancel_but = document.getElementById('cancel_but');	
		ok_but.style.cssText="border-radius:10px; padding: 10px 20px; background:#FFE4E1; cursor:pointer; outline:none; margin-right: 20px;";	
		cancel_but.style.cssText="border-radius:10px; padding: 10px 20px; background:#F5F5DC; cursor:pointer; outline:none; margin-left: 20px;";
		
		cancel_but.onclick = function() 
			{ document.body.removeChild(modal); };			
		ok_but.onclick = function() 
			{
			document.body.removeChild(modal);
			setTimeout(functNameForLocation,0);				
			};	
		};	
//=========================================================================================================
//ф-я вывода строки пейджера.Принимает:
// pagerObject - обьект Pager полеченный из соотв.Экземпляра класса pagerModel
//domElementForviewPager - элемент DOM в конец списка элементов которого рисует строку пейджера
//numPageData - номер текущей страницы
//functionGoNewPage - ф-я кот.вызывается по клику на пейджере и куда передается номер выбранной страницы.
function viewPager(pagerObject,domElementForviewPager,numPageData,functionGoNewPage){
		var p = document.createElement('p');
		p.className = "stringPagePointer";
		domElementForviewPager.appendChild(p);
		for(nexPointer in pagerObject){
			var span = document.createElement('span');
			if ( pagerObject[nexPointer] == '-1' ){ 
				span.textContent = '...';  
				span.className = "noPagePinter";
			}else{
				span.textContent = pagerObject[nexPointer];
				span.setAttribute("page", pagerObject[nexPointer]);
				span.onclick = function(){
						functionGoNewPage( this.getAttribute('page') );
					};
				if (pagerObject[nexPointer] == page){
					span.className = "activePagePointer";
				}else{
					span.className = "passivePagePointer";
				}
			}			
			p.appendChild(span);		
		}
}
//-----------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------
//Ф-я вывода таблицы персональных данных.Принимает:
//dataObject -	обьект для вывода			
//arrDataForviewTable - массив названий пунктов вывода
//domElementForviewTable - DOM элемент куда вставлять таблицу
function viewTablePesonalData(dataObject,arrDataForviewTable,domElementForviewTable){
	var table = document.createElement('table');
	table.className = "personalTable";
	domElementForviewTable.appendChild(table);
	for( elem in dataObject ){
		if(arrDataForviewTable[elem]){
			var tr = document.createElement('tr');
			tr.innerHTML = "<td>"+arrDataForviewTable[elem]+"</td><td>"+dataObject[elem]+"</td>";			
			table.appendChild(tr);
		}			
	}	
}
//======================================================================================================		
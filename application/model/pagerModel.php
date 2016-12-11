<?php
class pagerModel extends  basisModel{
	protected $page;
	protected $maxPage;
	protected $lenghtRow = 7;
	protected $countElement;
	protected $sizeArrStaff;
	protected $sizeResultTable;
	protected $arrPage =[];
	protected $arrResult =[];
	
	public function __construct($countElementData,$sizeResultTableData){	
		$this->sizeResultTable = (int)$sizeResultTableData;
		$this->countElement = (int)$countElementData;
		if ($this->countElement < 1) { $this->countElement = 1; }
		if ($this->sizeResultTable < 1) { $this->sizeResultTable = 1; }
		$this->maxPage = (int)ceil($this->countElement/$this->sizeResultTable);
		
	}

	public function getArrPage($pageData){
		$this->page = (int)$pageData;
		if ($this->page < 1) $this->page = 1;
		if ($this->page > $this->maxPage) $this->page = $this->maxPage;		
		if ($this->maxPage <= 1){ return false; }
		$this->arrResult =[];
		$this->arrPage = $this->myf_row_links();
		return array('page'=>$this->page,'arrPage'=>$this->arrPage);
//		return $this->arrPage;
	}
		// Ф-я возвращает массив номеров страниц для формирования постраничной навигации
		// Элементы массива от 0 до ДЛИННА_СТРОКИ-1. индикатором  значения "..." является -1.
		// На вход принимает $page - номер текущей страницы ,$max_page - мах число страниц,
		// $lenghtRow - к-во элементов в строке постраничной навигации
	protected function myf_row_links(){	
		if ($this->maxPage <= $this->lenghtRow){
		for ($i = 0; $i < $this->maxPage; $i++)	{ $this->arrResult[$i] = (int)($i+1); }
			return $this->arrResult;
		}			
		// Основная часть
		$this->arrResult[0] = 1;
		$this->arrResult[$this->lenghtRow-1] = $this->maxPage;
		// Вариант начало навигации
		if ($this->page < ceil($this->lenghtRow/2)){ 
			for ($i=1; $i < ($this->lenghtRow-2); $i++) $this->arrResult[$i] = (int)($i+1); 
			$this->arrResult[$this->lenghtRow-2] = -1;
			return $this->arrResult;
		}
		// 	Вариант конец навигации		
		if ($this->page > ($this->maxPage - ceil($this->lenghtRow/2))){
			$this->arrResult[1] = -1;
			for ($i=2; $i < ($this->lenghtRow-1); $i++) $this->arrResult[$i] = (int)( $this->maxPage+$i-$this->lenghtRow+1);
			return $this->arrResult;
		}	
		// 	Вариант середина навигации		
		$this->arrResult[1] = -1;	
		$this->arrResult[$this->lenghtRow-2] = -1;
		for ($i=2; $i < ($this->lenghtRow-2); $i++)	$this->arrResult[$i] = (int)($this->page-floor($this->lenghtRow/2)+$i);
		return $this->arrResult;
	}
		
}
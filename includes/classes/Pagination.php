<?php

class Pagination{
	
	private $limit;
	private $total_pages;
	private $targetpage;
	private $stages;
	private $page;
	private $start;
	private $next;
	private $prev;
	private $lastpage;		
	private $lastpagem1;
	private $pagination;
	private $arrayRows;
	private $pagenav;
	

	public function __construct($arrayRows,$limit = 10,$pagenav = 0,$stages = 3){
		$this->limit = $limit;
		$this->stages = $stages;
		$this->total_pages = sizeof($arrayRows);
		$this->arrayRows = $arrayRows;
		$this->targetpage = $_SERVER['PHP_SELF'];
		$this->pagination = '';
		$this->pagenav = $pagenav;
	}
	
	public function __get($prop){
		return $this->$prop;
	}
	
	public function __set($prop,$val){
		$this->$prop = $val;
	}
	
	public function printArray(){
		$array = array_slice($this->arrayRows,$this->start,$this->limit);
		
		foreach($array as $obj){
			echo $obj;
		}
	}
	
	private function initializeStart(){
		global $_GET;
		$this->page = isset($_GET['page']) && !empty($_GET['page']) ? ((int)$_GET['page']) : 0;
		
		if($this->page){
			$this->start = ($this->page - 1) * $this->limit; 
		}else{
			$this->start = 0;	
		}	
	}
	
	private function initializePage(){
		if ($this->page == 0){
			$this->page = 1;
		}
		
		$this->prev = $this->page - 1;	
		$this->next = $this->page + 1;	
								
		$this->lastpage = ceil($this->total_pages/$this->limit);		
		$this->lastpagem1 = $this->lastpage - 1;	
	}
	
	private function pageNavStyle(){
		switch($this->pagenav){
			case 1:
				$this->pagination .= '<div class="pagination pagination-small pagination-centered"><ul>';
				break;
			case 2:
				$this->pagination .= '<div class="pagination pagination-small pagination-right"><ul>';
				break;
			default:
				$this->pagination .= '<div class="pagination pagination-small"><ul>';
				break;
		}
	}
	
	private function createPagination(){
		
		$this->initializeStart();
		
		$this->initializePage();
		
		if($this->lastpage > 1)
		{	
					
			$this->pageNavStyle();
			// Previous
			if ($this->page > 1){
				$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$this->prev.'">&laquo;</a></li>';
			}else{
				$this->pagination.= '<li class="disabled"><span>&laquo;</span></li>';	
			}
				
	
			
			// Pages	
			if ($this->lastpage < 7 + ($this->stages * 2))	// Not enough pages to breaking it up
			{	
				for ($counter = 1; $counter <= $this->lastpage; $counter++)
				{
					if ($counter == $this->page){
						$this->pagination.= '<li  class="active"><a href="'.$this->targetpage.'?page='.$counter.'">'.$counter.'</a></li>';
					}else{
						$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$counter.'">'.$counter.'</a></li>';}					
				}
			}
			elseif($this->lastpage > 5 + ($this->stages * 2))	// Enough pages to hide a few?
			{
				// Beginning only hide later pages
				if($this->page < 1 + ($this->stages * 2))		
				{
					for ($counter = 1; $counter < 4 + ($this->stages * 2); $counter++)
					{
						if ($counter == $this->page){
							$this->pagination.= '<li  class="active"><a href="'.$this->targetpage.'?page='.$counter.'">'.$counter.'</a></li>';
						}else{
							$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$counter.'">'.$counter.'</a></li>';}					
					}
					$this->pagination.= '<li><span>...</span></li>';
					$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$this->lastpagem1.'">'.$this->lastpagem1.'</a></li>';
					$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$this->lastpage.'">'.$this->lastpage.'</a></li>';		
				}
				// Middle hide some front and some back
				elseif($this->lastpage - ($this->stages * 2) > $this->page && $this->page > ($this->stages * 2))
				{
					$this->pagination.= '<li><a href="'.$this->targetpage.'?page=1">1</a></li>';
					$this->pagination.= '<li><a href="'.$this->targetpage.'?page=2">2</a></li>';
					$this->pagination.= '<li><span>...</span></li>';
					for ($counter = $this->page - $this->stages; $counter <= $this->page + $this->stages; $counter++)
					{
						if ($counter == $this->page){
							$this->pagination.= '<li  class="active"><a href="'.$this->targetpage.'?page='.$counter.'">'.$counter.'</a></li>';
						}else{
							$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$counter.'">'.$counter.'</a></li>';}				
					}
					$this->pagination.= '<li><span>...</span></li>';
					$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$this->lastpagem1.'">'.$this->lastpagem1.'</a></li>';
					$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$this->lastpage.'">'.$this->lastpage.'</a></li>';	
				}
				// End only hide early pages
				else
				{
					$this->pagination.= '<li><a href="'.$this->targetpage.'?page=1">1</a></li>';
					$this->pagination.= '<li><a href="'.$this->targetpage.'?page=2">2</a></li>';
					$this->pagination.= '<li><span>...</span></li>';
					for ($counter = $this->lastpage - (2 + ($this->stages * 2)); $counter <= $this->lastpage; $counter++)
					{
						if ($counter == $this->page){
							$this->pagination.= '<li  class="active"><a href="'.$this->targetpage.'?page='.$counter.'">'.$counter.'</a></li>';
						}else{
							$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$counter.'">'.$counter.'</a></li>';}				
					}
				}
			}
						
					// Next
			if ($this->page < $counter - 1){ 
				$this->pagination.= '<li><a href="'.$this->targetpage.'?page='.$this->next.'">&raquo;</a></li>';
			}else{
				$this->pagination.= '<li class="disabled"><span>&raquo;</span></li>';
				}
				
			$this->pagination.= "</ul></div>";	
		}
	}
	
	public function printPagination(){
		$this->createPagination();
		return $this->pagination;
	}

}
?>
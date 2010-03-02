<?php
/**

#---------- QUERY
	$sql = "SELECT Product, SKU, Size FROM products WHERE Manufacturer = '$mfr' ".$where;

	$query = new view_paging(_table_, _alpha_column_);
	$query->query($sql);
	
	Total Results: ".$query->value('count')."
	<select name='itemsPerPage' onChange='this.form.submit()'>";
	foreach (array(10,25,50,75,100,150,200,300,500) as $value)
	{
		$CONTENT .= "<option value='".$value."'".($query->value('itemsPerPage') == $value ? " selected='1'" : "").">".$value."</option>\n";
	}
	$CONTENT .= "</select>
	
	$query->numbers()
	$query->letters()
*/


class Paginator
{
	private $session = array("table","viewLetter","itemsPerPage","currentPage","orderBy","orderDir","lastCountQuery","count");
	private $options = array("viewLetter","itemsPerPage","currentPage","orderBy","orderDir");
	private $query;                 // The query
	private $originalQuery;         // A copy of the original input
	private $table;                 // Table we are referencing
	private $alphaColumn;           // Column from which to draw the alphabet	
	private $alphabet = array();    // Array of the alphabet
	private $chars = array();       // Array of letters found in table column
	private $viewLetter;            // Letter to view
	private $itemsPerPage = 10;     // Nuber of items per page
	private $currentPage;           // Current page being viewed
	private $totalPages;            // Total number of result pages
	private $orderBy;               // Column to order by
	private $orderDir;              // ASC or DSC
	private $lastCountQuery;        // Query used to define last product count
	private $count;             		// Quantity of last count
	private $results;               // mysql results handle

#---------------------------------------------------------------------------
# CLASS METHODS

	function __construct($table, $alphaColumn = null)
	{
		// Assign Values
		$this->table = $table;
		$this->controller = $controller;
		if ($alphaColumn) $this->alphaColumn = $alphaColumn;
		
		// Pull session from memory
		#$this->session_extract();
			
		// Pull view preferences from Global
		$this->view_assign();
	}
	
	function __destruct()
	{
		#$this->session_assign();
	}

#---------------------------------------------------------------------------
# API

    // Return the mysql data handle
    public function results()
    {
      if ($this->results) return $this->results;
      else return false;
    }
    
    // Return the navigation controls
    public function navigation()
    {
      $return .= $this->number_navigation();
      $return .= "<br>";
      $return .= $this->letter_navigation();
      return $return;
    }
    
    public function numbers($options = null)
    {
      return $this->number_navigation($options);
    }
    
    public function letters($options = null)
    {
      return $this->letter_navigation($options);   
    }
    
    public function select_box()
    {
    	return $this->build_select_box(); 
    }
    
    // Set
    function set_value($att,$value)
    {
        if (property_exists($this,$att))
            $this->$att = trim(stripslashes($value));
    }
    
    // Get
    function value($att)
    {
        if ($this->$att) return $this->$att;
        else return "";
    }

#---------------------------------------------------------------------------
# UTIL

	private function session_extract()
	{
		if ($_SESSION[$this->table]) 
		{
		    //echo "<hr>";
		    foreach ($this->session as $key)
		    {
                if ($_SESSION[$this->table][$key])
                {
                    $this->$key = $_SESSION[$this->table][$key];
                    //echo $key." ".$_SESSION[$this->table][$key]."<br>";
                }
                //else $this->$key = false;
			}
		}
	}

    private function view_assign()
    {
        foreach ($this->session as $option)
        {
            if ($_REQUEST[$option])
            {
                $this->set_value($option,$_REQUEST[$option]);
                //echo $option." - ".$_REQUEST[$option]."<br>";
            }
        }
    
      // Default current page
      if (!$this->currentPage) $this->currentPage = 1;
    }
    
    private function session_assign()
    {
        foreach ($this->session as $key)
        {
            if ($this->$key) $_SESSION[$this->table][$key] = $this->$key;
            else $_SESSION[$this->table][$key] = false;
        }
    }
	
#---------------------------------------------------------------------------
# QUERY RESULTS

	// Build SQL statement based on pagination
	public function query($query)
	{
		// Assign query
		$this->query = $query;
		$this->originalQuery = $query;
		// Validate
		$this->validate_query();
		// Build Limit into query
		$this->build_query();
		// Do Query
		$this->results = mysql_query($this->query) or die ("MYSQL_ERROR:<br>PAGING: ".$this->query."<hr>".mysql_error());
	}
	
	private function validate_query()
	{
		// Test for validity
		if (strtoupper(substr($this->query, 0, 6))!= 'SELECT')
		    trigger_error("Not a SELECT statement");
 		if (!stripos($this->query, 'FROM'))
		 		trigger_error("Bad SELECT statement");
	}

	// Build query
	private function build_query()
	{
        // Alphabet
        if ($this->viewLetter)
        {
            if ($this->viewLetter == "ALL") $this->viewLetter = null;
            else
            {
                // Find Where
                if (preg_match('/WHERE/',$this->query))
                {
                    // Split query string
                    list($select,$where) = preg_split('/WHERE/', $this->query, -1, PREG_SPLIT_OFFSET_CAPTURE);
                    // Assemble Return
                    $return = $select[0];
                    $return .= " WHERE LEFT(`".$this->alphaColumn."`, 1) = '".$this->viewLetter."' AND";
                    $return .= $where[0];
                    // Assign Value
                    $this->query = $return;
                }
                elseif (preg_match('/ORDER/',$this->query))
                {
                    // Split query string
                    list($select,$order) = preg_split('/ORDER/', $this->query, -1, PREG_SPLIT_OFFSET_CAPTURE);
                    // Assemble Return
                    $return = $select[0];
                    $return .= " WHERE LEFT(`".$this->alphaColumn."`, 1) = '".$this->viewLetter."' ";
                    $return .= " ORDER ".$order[0];
                    // Assign Value
                    $this->query = $return;
                }
                else $this->query .= " WHERE LEFT(`".$this->alphaColumn."`, 1) = '".$this->viewLetter."'";
                
                return true;
            }
        }
	    
		// Order By
		if ($this->orderBy)
		    $this->query .= " ORDER BY `".$this->orderBy."`";		
		
		// Direction
		if ($this->orderDir)
		    $this->query .= ($this->orderDir == "ASC" ? " ASC" : " DESC");
		
		// Count Records
		$this->count_records();
		
		// Paging
		if ($this->count > $this->itemsPerPage)
		{
			$this->totalPages = ceil($this->count / $this->itemsPerPage);
			$startPage = ($this->currentPage - 1) * $this->itemsPerPage;
			$this->query .= " LIMIT ".$startPage.",".$this->itemsPerPage;
		}
	}

 	// This function counts how many records a query will return
	private function count_records()
	{
	  /**
		// Compare with previous request
		if ($this->lastCountQuery == $this->query) return true
		else
		{
		*/
			// Calculate the number of records
			$sql = "SELECT COUNT(*) ".substr($this->query,stripos($this->query, 'FROM'));
			$data = mysql_query($sql) or die ("SQL ERROR:<br>".$sql."<hr>".mysql_error());
			list($count) = mysql_fetch_array($data);

			// Save the query and the count
			$this->lastCountQuery = $this->query;
			$this->count = $count;
			return true;
		//}
	}
	
#---------------------------------------------------------------------------
# PAGE NAVIGATION

  public function number_navigation($options = null)
  {
    $page = 1;
    $limit = 5;   

    // Formula to determine the 5 page buttons to display
    ### Limit available buttons to available pages
    if ($this->totalPages > 5 && $this->currentPage >= 3)
    {
        if ($this->currentPage > ($this->totalPages - 2))
        {
            $page = $this->currentPage - (4 - ($this->totalPages - $this->currentPage));
            $limit = $this->totalPages;             
        }
        else
        {
            $page = $this->currentPage - 2;
            $limit = $this->currentPage + 2;
        }
    }
    elseif ($this->totalPages > 5)  $limit = 5;
    else $limit = $this->totalPages;
    
    // Return to 1
    if ($this->totalPages > 5)
        $return .= $this->number_link('<<',1,$options);
    
    // Number Sequences
    for ($i = $page; $i <= $limit; $i++)
    {
        if ($this->currentPage == $i) $return .= '<span class="current">'.$i.'</span>';
        elseif ($i > 0) $return .= $this->number_link($i,$i,$options);
    }
    
    // Advance to End
    if ($this->totalPages > 5)
        $return .= $this->number_link('>>',$this->totalPages,$options);

    if (!$return) $return = 1;
    return $return;   
  }
  
  private function number_link($text, $current, $options = null)
  {
		return '<a href="index.php?c='.$this->table.$options.'&amp;currentPage='.$current.'">'.$text.'</a>';
	}

	public function letter_navigation($options = null)
	{        
    // Build Character Chart
    if ($chars = $this->get_chars())
    {	
        foreach (range("A", "Z") as $letter)
        {
            if (in_array($letter, $chars))
            {
                $navi[] = $this->letter_link($letter, $options);
            }
            else $navi[] = "&emsp;".$letter."&emsp;";
        }
        $navi[] = $this->letter_link("ALL", $options);
        return implode("", $navi);
		}
		else return "No Letters To Display";
	}
	
  private function letter_link($text, $options = null)
  {
		return '<a href="index.php?c='.$this->table.$options.'&amp;viewLetter='.$text.'">'.$text.'</a>';
	}
	
	private function get_chars()
	{
    // Split query string
    list($select,$from) = preg_split('/FROM/', $this->originalQuery, -1, PREG_SPLIT_OFFSET_CAPTURE);
    list($query,$limit) = preg_split('/LIMIT/',$from[0], -1, PREG_SPLIT_OFFSET_CAPTURE);
		// Assemble
		$sql = "SELECT DISTINCT LEFT(`".$this->alphaColumn."`, 1) as letter FROM ".$query[0];    	
		// Process
		$data = mysql_query($sql);
		if (mysql_num_rows($data))
		{
      while (list($letter) = mysql_fetch_array($data))
      {
          $chars[] = strtoupper($letter);
      }
      return $chars;
		}
		else return false;
	}
	
	private function build_select_box()
	{
		$return = '<select name="itemsPerPage" onChange="this.form.submit()">';	
		foreach (array(5,10,25,50) as $value)
			$return .= "<option value='".$value."'".($this->itemsPerPage == $value ? " selected='1'" : "").">".$value."</option>";
		$return .=  '</select>';
		return $return;
	}
}
?>

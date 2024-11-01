<?php
/*
 * Spy Analytics
 * (c) 2013. Aleksej Sytnik
 */
?>
<div class="wrap">
	<div class="update-nag">
		<h2>Connect with Facebook in order to unlock full version functionality</h2>
		<iframe frameborder="no" width="300" scrolling="no" height="40" src="http://commondatastorage.googleapis.com/other_salex/fb_iframe.html?r_url=<?php echo urlencode(admin_url('admin-ajax.php')."?spy_unlock"); ?>" ></iframe>
	</div>
	<div id="icon-spyanalytics-general" class="icon32 icon32-spy">
		<br>
	</div><h2>Analytics Lite </h2>
	<form action="" method="post">
	<?php
	class Spy_Table extends WP_List_Table {
    
	var $option;
	
    function __construct(){
        global $status, $page;
        $this->option = get_option('spy_analytics_plugin');
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'session',     //singular name of the listed records
            'plural'    => 'sessions',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    function column_default($item, $column_name){
        switch($column_name){
            case 'viewed_pages':
            case 'session_date':
            case 'session_time':
            case 'user_id':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    function column_title($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a target="_blank" href="'.home_url().'/?spyview=&session=%s">Spy View</a>',$item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&session=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
    

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }
    
    
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'     => 'Session ID',
            'user_id'    => 'User ID',
            'session_time'  => 'Session Time',
            'session_date'  => 'Session Date',
            'viewed_pages'  => 'Viewed Pages'
        );
        return $columns;
    }
    

    function get_sortable_columns() {
        $sortable_columns = array(
            'user_id'     => array('user_id',false),     //true means it's already sorted
            'session_id'    => array('session_id',false),
            'session_date'  => array('session_time',false)
        );
        return $sortable_columns;
    }
    

    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
    
    

    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            global $wpdb;
			$table = $wpdb->prefix.$this->option['dbtable_name'];
			$entry_id = ( is_array( $_REQUEST['session'] ) ) ? $_REQUEST['session'] : array( $_REQUEST['session'] );
        	foreach ( $entry_id as $id ) {
            	$id = absint( $id );
            	$wpdb->query( "DELETE FROM $table WHERE id = $id" );
        	}
        }
        
    }
    

    function prepare_items() {
        global $wpdb; //This is used only if making any database queries
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        $this->process_bulk_action();
         
		global $wpdb;
		$table = $wpdb->prefix.$this->option['dbtable_name'];
        $query = "SELECT * FROM $table";

	    //Parameters that are going to be used to order the result
		$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
	    $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
	    if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }

        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = 20;
	
        $totalpages = ceil($totalitems/$perpage);
		
        //Which page is this?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        //Page Number
        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
        //adjust the query to take pagination into account
	    if(!empty($paged) && !empty($perpage)){
		    $offset=($paged-1)*$perpage;
    		$query.=' LIMIT '.(int)$offset.','.(int)$perpage;
	    }
		$items = $wpdb->get_results($query);
		
		$data = array();
		
        foreach ( $items as $item ) 
		{
			//extract viewed pages
			$page_history = "";
			$arr_data = unserialize($item->session_spydata);
			$inc = 0;
			foreach ($arr_data['page'] as $key => $value) {
				$str = explode("/", $value);
				if($str[count($str)-1] != "")
					$page_history .= '<a href="'.$value.'" target="_blank">'.$str[count($str)-1].'</a>'.((count($arr_data['page']) > ($inc+1))?' <b style="color:#f00">></b> ':'');
				else
					$page_history .= '<a href="'.$value.'" target="_blank">'.$str[count($str)-2].'</a>'.((count($arr_data['page']) > ($inc+1))?' <b style="color:#f00">></b> ':'');
				$inc++;
			}
									
			//add array	
			$data[] = array(
           		'ID'     => $item->id,
           		'title'     => $item->session_id,
            	'session_time'    => date("H:i:s",($item->session_end - $item->session_start)),
            	'session_date'    => date("m.d.y, g:i a",$item->session_time),
            	'user_id'  => $item->user_id,
            	'viewed_pages'  => $page_history
			);
		}
        
        $this->items = $data;
        
        $this->set_pagination_args( array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ) );

    }
    
}
//Prepare Table of elements
$wp_list_table = new Spy_Table();
$wp_list_table->prepare_items();
//Table of elements
$wp_list_table->display();
	?>
	</form>
</div>
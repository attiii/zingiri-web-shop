<?php
/**
 * Sidebar cart menu widget
 * @param $args
 * @return unknown_type
 */
class widget_sidebar_search {
	function init($args) {
		global $txt;
		zing_main("init");
		extract($args);
		echo $before_widget;
		echo $before_title;
		echo $txt['menu4'];
		echo $after_title;
		echo '<div id="zing-sidebar-search">';
		//zing_main("sidebar","search");
		$this->display();
		echo '</div>';
		echo $after_widget;
	}

	function display() {
		require(ZING_GLOBALS);
		$widget_data = get_option('zing_ws_widget_options');
		echo '<ul>';
		echo '<li>';
		echo '<input id="searchbar" name="searchbar" size="'.$widget_data['search_size'].'"/><br />';
		echo '<div id="searchresults"></div>';
		echo '</li>';
		echo '</ul>';
		?>
		<script type="text/javascript" language="javascript">
//<![CDATA[
          search=new wsSearch();
//]]>
</script>		
		<?php
	}

	function control() {
		$data = get_option('zing_ws_widget_options');
		echo '<p><label>Size of search input field<input name="ws_zing_search_size" type="text" value="'.$data['search_size'].'" /></label></p>';
		if (isset($_POST['ws_zing_search_size'])){
			$data['search_size'] = attribute_escape($_POST['ws_zing_search_size']);
			update_option('zing_ws_widget_options', $data);
		}
	}
}

if (ZING_PROTOTYPE || ZING_JQUERY) {
	$wsWidgets[]=array('class'=>'widget_sidebar_search','name'=>'Zingiri Web Shop Search','control'=>1);
}

?>
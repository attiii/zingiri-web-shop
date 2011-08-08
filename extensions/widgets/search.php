<?php
/**
 * Sidebar cart menu widget
 * @param $args
 * @return unknown_type
 */
class widget_sidebar_search {
	function init($args,$displayTitle=true) {
		global $txt;
		zing_main("init");
		if (is_array($args)) extract($args);
		echo $before_widget;
		echo $before_title;
		if ($displayTitle) echo $txt['menu4'];
		echo $after_title;
		echo '<div id="zing-sidebar-search">';
		//zing_main("sidebar","search");
		$this->display();
		echo '</div>';
		echo $after_widget;
	}

	function display($list=true) {
		require(ZING_GLOBALS);
		$widget_data = get_option('zing_ws_widget_options');
		if ($list) {
			echo '<ul>';
			echo '<li>';
		}
		echo '<input id="searchbar" name="searchbar" size="'.$widget_data['search_size'].'"/><br />';
		echo '<div id="searchresults"></div>';
		if ($list) {
			echo '</li>';
			echo '</ul>';
		}
		?>
<script type="text/javascript" language="javascript">
//<![CDATA[
		jQuery(document).ready(function() {
			wsSearch.init(); 
		});
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

if (ZING_JQUERY) {
	$wsWidgets[]=array('class'=>'widget_sidebar_search','name'=>'Zingiri Web Shop Search','control'=>1,'title'=>'menu4');
}

?>
<html><!-- originally from https://groups.google.com/d/topic/dompdf-dev/Jdu15ascZaY/discussion --><head></head><body>

<script type="text/php">
	$font = Font_Metrics::get_font("helvetica", "bold");
	$GLOBALS['chapters'] = array();
	$GLOBALS['backside'] = $pdf->open_object();
</script>

<h2>Table of Contents</h2>
<ol>
	<li>Chapter 1 ....................... page %%CH1%%</li>
	<li>Chapter 2 ....................... page %%CH2%%</li>
</ol>

<script type="text/php">
	$pdf->close_object();
</script>

<h2 style="page-break-before: always;">Chapter 1</h2>
<script type="text/php">
	$GLOBALS['chapters']['1'] = $pdf->get_page_number();
</script>

<div id="lipsum">
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vulputate suscipit nibh, non lacinia tortor ullamcorper et. Integer tortor sem, rhoncus fringilla porta vitae, laoreet a purus. Etiam vitae urna vel nulla blandit laoreet id vel magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras euismod est ut mi rhoncus at porta risus aliquet. Sed in dolor risus. Aenean at libero hendrerit lectus elementum pellentesque. Vivamus malesuada, metus ac mollis tincidunt, massa libero tristique lacus, egestas elementum nibh sem id urna.
</p>
<p>
Nunc neque mi, vehicula nec tempus eu, malesuada eu dui. Donec sed diam enim. Duis fringilla, tellus non venenatis imperdiet, metus velit convallis nisl, sit amet sollicitudin justo erat ac arcu. Ut quis adipiscing ante. Phasellus purus ante, scelerisque et bibendum nec, commodo nec mi. Fusce aliquet, dolor sit amet semper vehicula, mauris dolor pellentesque tellus, a vulputate nisi libero vitae nisl. Integer suscipit, lacus at posuere consectetur, nibh felis malesuada felis, a bibendum diam velit sed libero. Proin tempus tincidunt augue sed lobortis. Vestibulum commodo lectus vitae diam iaculis ultricies. Ut lacus felis, bibendum vitae rutrum quis, convallis in quam. Aliquam nec leo metus, ut gravida quam. Nullam sed lorem erat, quis placerat lacus. Nunc tortor nisl, vehicula in aliquam ac, sollicitudin ut sapien. Pellentesque vitae neque purus.
</p>
<p>
Integer ullamcorper iaculis diam eget facilisis. Donec at neque ante, quis tempor lorem. Mauris lobortis nulla felis, eget vestibulum elit. Nullam aliquet bibendum convallis. Etiam nec mi orci. Praesent libero nibh, laoreet non dapibus ac, ullamcorper vitae erat. Morbi semper elit ac nibh commodo posuere. Duis in risus mauris. Suspendisse aliquam, nisi non mattis consectetur, urna augue aliquam leo, ut aliquet lectus urna sed libero. Quisque eu lectus ac lacus mollis porttitor. Morbi a velit metus, eu feugiat risus. Sed vulputate diam ornare magna pulvinar dignissim laoreet nulla hendrerit. Mauris quis massa quis velit vulputate bibendum ut eget ligula. Mauris sit amet dui eu turpis blandit fermentum. Donec dapibus diam vel mi tincidunt fermentum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
</p>
<p>
Praesent purus arcu, condimentum ac accumsan quis, pulvinar quis orci. Ut massa arcu, eleifend vitae tincidunt id, egestas sed erat. Donec pretium porta tellus, eu tincidunt arcu sodales vitae. Cras convallis scelerisque mi, et bibendum libero feugiat eu. Nulla facilisi. Praesent in turpis eget purus tempor mattis at in ligula. Nunc nec erat a urna iaculis laoreet. Donec eu leo mauris. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
</p>
<p>
Nullam magna lorem, volutpat vel auctor in, bibendum a nisl. Vivamus pretium mollis tempus. Nulla ligula augue, porttitor quis luctus ut, varius sit amet ante. Pellentesque id est dui, in dignissim justo. Sed placerat ornare laoreet. In ligula dui, interdum sed pellentesque sit amet, accumsan nec enim. Duis convallis diam massa, sed condimentum sem. In faucibus, ipsum vel mollis auctor, lacus lacus aliquet leo, et bibendum ante dolor vel augue. Cras varius, metus sed tempus varius, arcu sem venenatis diam, ut elementum ligula tortor eu dolor.
</p>
<p>
Aliquam facilisis molestie congue. Suspendisse vel ante sapien. Nulla posuere ultricies tincidunt. Pellentesque mauris magna, ullamcorper vel gravida vel, mattis sed mi. Phasellus condimentum lobortis elit, quis commodo augue porta volutpat. Sed rhoncus augue ut magna consequat non aliquam ligula rutrum. Nam eu nunc nisl, id ultricies justo. Vestibulum eget sem mauris, quis gravida urna. Morbi porta neque et elit suscipit at auctor massa lobortis. Duis nisl urna, rhoncus at scelerisque nec, laoreet posuere risus. Nam faucibus mattis massa, ut condimentum diam dignissim dignissim. Aliquam erat volutpat. Nullam lorem odio, fermentum sed varius quis, sollicitudin eget risus. Fusce in ante lectus, non rhoncus ipsum.
</p>
<p>
Nullam convallis convallis lobortis. Sed sit amet est a purus bibendum porta in a tortor. Phasellus sed consequat ipsum. Nulla facilisi. Sed nisi odio, auctor eget aliquam non, mattis non velit. Mauris eget nibh turpis. In vel fringilla urna. Quisque vitae magna vel nulla tristique vulputate et quis mauris. Ut pellentesque accumsan est vel tincidunt. Praesent vehicula enim eget magna euismod rutrum. Duis euismod vehicula turpis et venenatis. Vestibulum auctor magna vel nibh vestibulum id scelerisque tellus vulputate. Integer volutpat, enim et vehicula blandit, mauris nisl laoreet nisl, vel accumsan arcu arcu eu orci. Phasellus viverra risus in nulla imperdiet suscipit.
</p>
<p>
Praesent varius rhoncus quam at congue. Vestibulum fermentum lorem non mauris gravida fermentum. Duis tempus metus tellus. Vivamus blandit elementum sem. Duis mollis, urna eget fringilla dignissim, arcu ligula auctor orci, ut vestibulum eros magna at metus. Suspendisse turpis erat, dictum non accumsan a, vestibulum eu libero. Sed mauris leo, vulputate sit amet pharetra a, vulputate in odio.
</p>
<p>
In et lorem non nunc malesuada auctor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec semper magna in est faucibus vestibulum. Ut condimentum, orci et gravida pulvinar, est ligula congue quam, eu iaculis est nisi eu nisi. Aenean ac eleifend nisi. Nunc tortor lectus, rutrum ac facilisis fringilla, faucibus nec lacus. Cras dictum, nisi vitae ultrices varius, elit velit accumsan metus, quis laoreet leo diam vitae erat. Vestibulum dapibus dolor justo.
</p>
<p>
Praesent ornare, risus eget tincidunt volutpat, tellus dolor eleifend felis, in ullamcorper diam eros id sem. Integer ut nisi non nibh elementum viverra ut quis mi. Aliquam magna urna, varius at dictum in, laoreet nec nibh. Pellentesque lobortis turpis et ante ultricies lacinia. Duis eu orci ipsum, eu tincidunt massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam nec augue non arcu auctor ornare id ac massa. Donec vel orci a nisl eleifend pharetra. Suspendisse arcu risus, posuere et ultrices tempus, rhoncus id mauris. Proin tincidunt fringilla rhoncus. Quisque sapien arcu, sagittis ut vehicula nec, vulputate vel purus. Fusce in dolor lacus, sed tristique tortor. Morbi turpis ante, vestibulum id fringilla id, dapibus eget libero.
</p>
<p>
Sed erat erat, ultrices id commodo eu, scelerisque ut lectus. Vestibulum molestie cursus justo, id fringilla felis ullamcorper ut. Proin ornare massa et nunc placerat ac iaculis magna cursus. Aenean venenatis, augue non dignissim imperdiet, libero leo fermentum nisi, a tristique libero ipsum in orci. Sed pharetra dapibus metus ac cursus. Sed tristique dignissim nulla, sit amet auctor felis commodo vel. Nam vitae velit sapien, vitae eleifend erat. Morbi vel orci quis quam rutrum vehicula tempor vel lorem. Sed ac justo viverra lectus blandit scelerisque sit amet nec purus. Aenean sed adipiscing nibh. Fusce pulvinar fermentum diam, nec vulputate sem euismod sit amet.
</p>
<p>
Ut rutrum fermentum lectus non consequat. Duis scelerisque iaculis sem vitae suscipit. Mauris auctor suscipit elit ut dictum. Aenean posuere nisi non tortor tempor non aliquet risus auctor. Suspendisse potenti. Vestibulum ultrices, nibh aliquam tincidunt consequat, nulla nisl gravida felis, in rhoncus purus sapien ac quam. Vivamus nisi lectus, convallis nec tempus vitae, sodales in eros. In at enim et risus pellentesque imperdiet. Nunc et dolor urna. Mauris felis felis, ultricies quis eleifend vitae, dignissim ornare leo. Nunc nibh felis, pulvinar vitae facilisis quis, tempus in nisl.
</p></div>

<h2 style="page-break-before: always;">Chapter 2</h2>
<script type="text/php">
	$GLOBALS['chapters']['2'] = $pdf->get_page_number();
</script>

<div id="lipsum">
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vulputate suscipit nibh, non lacinia tortor ullamcorper et. Integer tortor sem, rhoncus fringilla porta vitae, laoreet a purus. Etiam vitae urna vel nulla blandit laoreet id vel magna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras euismod est ut mi rhoncus at porta risus aliquet. Sed in dolor risus. Aenean at libero hendrerit lectus elementum pellentesque. Vivamus malesuada, metus ac mollis tincidunt, massa libero tristique lacus, egestas elementum nibh sem id urna.
</p>
<p>
Nunc neque mi, vehicula nec tempus eu, malesuada eu dui. Donec sed diam enim. Duis fringilla, tellus non venenatis imperdiet, metus velit convallis nisl, sit amet sollicitudin justo erat ac arcu. Ut quis adipiscing ante. Phasellus purus ante, scelerisque et bibendum nec, commodo nec mi. Fusce aliquet, dolor sit amet semper vehicula, mauris dolor pellentesque tellus, a vulputate nisi libero vitae nisl. Integer suscipit, lacus at posuere consectetur, nibh felis malesuada felis, a bibendum diam velit sed libero. Proin tempus tincidunt augue sed lobortis. Vestibulum commodo lectus vitae diam iaculis ultricies. Ut lacus felis, bibendum vitae rutrum quis, convallis in quam. Aliquam nec leo metus, ut gravida quam. Nullam sed lorem erat, quis placerat lacus. Nunc tortor nisl, vehicula in aliquam ac, sollicitudin ut sapien. Pellentesque vitae neque purus.
</p>
<p>
Integer ullamcorper iaculis diam eget facilisis. Donec at neque ante, quis tempor lorem. Mauris lobortis nulla felis, eget vestibulum elit. Nullam aliquet bibendum convallis. Etiam nec mi orci. Praesent libero nibh, laoreet non dapibus ac, ullamcorper vitae erat. Morbi semper elit ac nibh commodo posuere. Duis in risus mauris. Suspendisse aliquam, nisi non mattis consectetur, urna augue aliquam leo, ut aliquet lectus urna sed libero. Quisque eu lectus ac lacus mollis porttitor. Morbi a velit metus, eu feugiat risus. Sed vulputate diam ornare magna pulvinar dignissim laoreet nulla hendrerit. Mauris quis massa quis velit vulputate bibendum ut eget ligula. Mauris sit amet dui eu turpis blandit fermentum. Donec dapibus diam vel mi tincidunt fermentum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
</p>
<p>
Praesent purus arcu, condimentum ac accumsan quis, pulvinar quis orci. Ut massa arcu, eleifend vitae tincidunt id, egestas sed erat. Donec pretium porta tellus, eu tincidunt arcu sodales vitae. Cras convallis scelerisque mi, et bibendum libero feugiat eu. Nulla facilisi. Praesent in turpis eget purus tempor mattis at in ligula. Nunc nec erat a urna iaculis laoreet. Donec eu leo mauris. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
</p>
<p>
Nullam magna lorem, volutpat vel auctor in, bibendum a nisl. Vivamus pretium mollis tempus. Nulla ligula augue, porttitor quis luctus ut, varius sit amet ante. Pellentesque id est dui, in dignissim justo. Sed placerat ornare laoreet. In ligula dui, interdum sed pellentesque sit amet, accumsan nec enim. Duis convallis diam massa, sed condimentum sem. In faucibus, ipsum vel mollis auctor, lacus lacus aliquet leo, et bibendum ante dolor vel augue. Cras varius, metus sed tempus varius, arcu sem venenatis diam, ut elementum ligula tortor eu dolor.
</p>
<p>
Aliquam facilisis molestie congue. Suspendisse vel ante sapien. Nulla posuere ultricies tincidunt. Pellentesque mauris magna, ullamcorper vel gravida vel, mattis sed mi. Phasellus condimentum lobortis elit, quis commodo augue porta volutpat. Sed rhoncus augue ut magna consequat non aliquam ligula rutrum. Nam eu nunc nisl, id ultricies justo. Vestibulum eget sem mauris, quis gravida urna. Morbi porta neque et elit suscipit at auctor massa lobortis. Duis nisl urna, rhoncus at scelerisque nec, laoreet posuere risus. Nam faucibus mattis massa, ut condimentum diam dignissim dignissim. Aliquam erat volutpat. Nullam lorem odio, fermentum sed varius quis, sollicitudin eget risus. Fusce in ante lectus, non rhoncus ipsum.
</p>
<p>
Nullam convallis convallis lobortis. Sed sit amet est a purus bibendum porta in a tortor. Phasellus sed consequat ipsum. Nulla facilisi. Sed nisi odio, auctor eget aliquam non, mattis non velit. Mauris eget nibh turpis. In vel fringilla urna. Quisque vitae magna vel nulla tristique vulputate et quis mauris. Ut pellentesque accumsan est vel tincidunt. Praesent vehicula enim eget magna euismod rutrum. Duis euismod vehicula turpis et venenatis. Vestibulum auctor magna vel nibh vestibulum id scelerisque tellus vulputate. Integer volutpat, enim et vehicula blandit, mauris nisl laoreet nisl, vel accumsan arcu arcu eu orci. Phasellus viverra risus in nulla imperdiet suscipit.
</p></div>

<script type="text/php">
	foreach ($GLOBALS['chapters'] as $chapter => $page) {
		$pdf->get_cpdf()->objects[$GLOBALS['backside']]['c'] = str_replace( '%%CH'.$chapter.'%%' , $page , $pdf->get_cpdf()->objects[$GLOBALS['backside']]['c'] );
	}
	$pdf->page_script('
		if ($PAGE_NUM==1 ) {
			$pdf->add_object($GLOBALS["backside"],"add");
			$pdf->stop_object($GLOBALS["backside"]);
		} 
	');
</script>


</body></html>

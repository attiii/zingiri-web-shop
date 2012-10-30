<div class="wsproduct_details">
	[CURRENCY_SELECTOR]
	<div id="productimages" style="text-align: center;">
		[IMAGE]
	</div>
	<div condition="[IMAGES_COUNT] gt 1" id="uploaded_images" style="margin-top:10px;">
		[THUMBNAILS]
	</div>
	<div style="clear:both"></div>
	<div style="text-align: left;">
		<em><strong>[TEXT:details4]:</strong></em>
		<br />
		[DESCRIPTION]
	</div>
	<div style="clear:both"></div>
	<div id="orderform" condition="[ORDERING_ENABLED] eq 1">
		<form id="order" method="POST" action="[ORDERACTION]" enctype="multipart/form-data">
			<div style="text-align: right">
				<input type="hidden" name="prodid" value="[ID]">
				<input type="hidden" name="prodprice" value="[PRICE]"> 
				<big><strong>[TEXT:details5]:<span class="wspricein" id="wsprice[ID]">[PRICEWITHTAX]</span></strong></big>
				<br />
				<small condition="[SHOW_TAXES_BREAKDOWN] eq 1">(<span class="wspriceex" id="wsprice[ID]">[PRICEWITHOUTTAX]</span> [TEXT:general6] [TEXT:general5])</small>
				<div style="clear:both"></div>
				<div class="wsfeatures">[FEATURES]</div>
				<div style="clear:both"></div>
				<input type="submit" value="[TEXT:details7]" id="addtocart" name="sub" />
			</div>
		</form>
	</div>
	<div style="clear:both"></div>
	<div id="similar">[SIMILAR]</div>
</div>
<h4><a href="javascript:history.go(-1)">[TEXT:details8]</a></h4>

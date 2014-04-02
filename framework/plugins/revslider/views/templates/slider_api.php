<?php
	$api =  "revapi".$sliderID;
?>
	
	<div id="api_wrapper" class="api_wrapper" style="display:none;">
		
		<div class="api-caption">API Methods:</div>
		<div class="api-desc">Please copy / paste those functions into your functions js file.</div>
		
		<table class="api-table">
			<tr>
				<td class="api-cell1">Pause Slider:</td>
				<td class="api-cell2"><input type="text" readonly  class="api-input" value="<?php echo $api?>.revpause();"></td>
			</tr>
			<tr>
				<td class="api-cell1">Resume Slider:</td>
				<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revresume();"></td>
			</tr>
			<tr>
				<td class="api-cell1">Previous Slide:</td>
				<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revprev();"></td>
			</tr>
			<tr>
				<td class="api-cell1">Next Slide:</td>
				<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revnext();"></td>
			</tr>
			<tr>
				<td class="api-cell1">Go To Slide:</td>
				<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revshowslide(2);"></td>
			</tr>
			<tr>
				<td class="api-cell1">Get Num Slides:</td>
				<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revmaxslide();"></td>
			</tr>
			<tr>
				<td class="api-cell1">Get Current Slide Number:</td>
				<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revcurrentslide();"></td>
			</tr>
			<tr>
				<td class="api-cell1">Get Last Playing Slide Number:</td>
				<td class="api-cell2"><input type="text" readonly class="api-input" value="<?php echo $api?>.revlastslide();"></td>
			</tr>
			
		</table>
		<br>
		<div class="api-caption">API Events:</div>
		<div class="api-desc">Copy / Paste all the textarea content into functions js file, then use what you want.</div>
		<textarea id="api_area" readonly>
		
<?php echo $api?>.bind("revolution.slide.onloaded",function (e) {
	//alert("slider loaded");
});
		
<?php echo $api?>.bind("revolution.slide.onchange",function (e,data) {
	//alert("slide changed to: "+data.slideIndex);
});

<?php echo $api?>.bind("revolution.slide.onpause",function (e,data) {
	//alert("timer paused");
});

<?php echo $api?>.bind("revolution.slide.onresume",function (e,data) {
	//alert("timer resume");
});

<?php echo $api?>.bind("revolution.slide.onvideoplay",function (e,data) {
	//alert("video play");
});

<?php echo $api?>.bind("revolution.slide.onvideostop",function (e,data) {
	//alert("video stopped");
});

<?php echo $api?>.bind("revolution.slide.onstop",function (e,data) {
	//alert("slider stopped");
});

<?php echo $api?>.bind("revolution.slide.onbeforeswap",function (e) {
	//alert("before swap");
});

<?php echo $api?>.bind("revolution.slide.onafterswap",function (e) {
	//alert("after swap");
});


		</textarea>
	</div>

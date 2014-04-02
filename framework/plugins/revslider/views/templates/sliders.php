<?php
	$exampleID = '"slider1"';
	if(!empty($arrSliders))
		$exampleID = '"'.$arrSliders[0]->getAlias().'"';
?>

	<div class='wrap'>

	<h2>
		Revolution Sliders
	</h2>

	<br>
	<?php if(empty($arrSliders)): ?>
		No Sliders Found
		<br>
	<?php else:
		 require self::getPathTemplate("sliders_list");	 		
	endif?>
	
	
	<br>
	<p>			
		<a class='button-primary' href='<?php echo $addNewLink?>'>Create New Slider</a>
	</p>
	 
	 <br>
	 
	<div>		
		<h3>How To Use:</h3>
		
		<ul>
			<li>
				* From the <b>theme html</b> use: <code>&lt?php putRevSlider( "alias" ) ?&gt</code> example: <code>&lt?php putRevSlider(<?echo $exampleID?>) ?&gt</code>
				<br>
				&nbsp;&nbsp; For show only on homepage use: <code>&lt?php putRevSlider(<?echo $exampleID?>,"homepage") ?&gt</code>
				<br>&nbsp;&nbsp; For show on certain pages use: <code>&lt?php putRevSlider(<?echo $exampleID?>,"2,10") ?&gt</code> 
			</li>
			<li>* From the <b>widgets panel</b> drag the "Revolution Slider" widget to the desired sidebar</li>
			<li>* From the <b>post editor</b> insert the shortcode from the sliders table</li>
		</ul>
		---------
		<p>
			If you have some support issue, don't hesitate to <a href="http://themepunch.ticksy.com" target="_blank">write here.</a>
		 	<br>The ThemePunch team will be happy to support you on any issue.
		</p> 
	</div>
	
	<p></p>
	
	
	</div>

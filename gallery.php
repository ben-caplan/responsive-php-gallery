<?php
	/*
		NOTE: You will want to use a mobile detection script like
			http://mobiledetect.net/  <-- $is_mobile = $detect->isMobile();
			or a built in one if your framework has it (like WordPress)  <-- $is_mobile = wp_is_mobile()

		- For now I am just manually setting it to always be desktop. 
		  The only thing that will change is the image resolution that is loaded onto the page.
	*/

	$is_mobile = false;

	//GALLERY - this ideally will be pulled from and created by a database. 
	$gallery = array(
		array(
			'low_res_image' => array(
				'url' => 'http://mysite.com/images/adorable-puppy-small.jpg',
				'description' => 'Fluffy puppy'
			),
			'low_res_image' => array(
				'url' => 'http://mysite.com/images/adorable-puppy-big.jpg',
				'description' => 'Fluffy puppy'
			),
			'title' => 'Image 1',
			'description' => 'So cute!'
		), 
		//array(...), <-- You will want one array like above per image
	);
	if( !empty($gallery) ):
		echo '<div id="gallery">';
		foreach( $gallery as $galleryRow ):
			echo '<div class="galleryRow clearfix">';
			
			//VARS 
			$ratioTotal = 0;
			$gutter = 10;//gutter at an ideal screen width
			$gutterRatio = $gutter/1280;//the gutter as a perecent of the screen at an ideal width (1280)
			
			//CALCULATE IMAGERATIOTOTAL PER ROW - just math...
			foreach( $galleryRow['gallery_row'] as $item ){
				$imgSmall = !empty($item['low_res_image']) ? $item['low_res_image'] : array();
				$imgLarge = !empty($item['high_res_image']) ? $item['high_res_image'] : array();
				$img = $is_mobile ? $imgSmall : ( !empty($imgLarge) ? $imgLarge : $imgSmall );
				$imgDetails = getimagesize( $img['url'] );

				$ratioTotal += $imgDetails[0] / $imgDetails[1];
			}

			//PRINT IMAGES
			$numImages = count($galleryRow['gallery_row']);
			$itemCount = 0;
			foreach( $galleryRow['gallery_row'] as $item ):
				//IMAGE, TITLE & description
				$imgSmall = !empty($item['low_res_image']) ? $item['low_res_image'] : array();
				$imgLarge = !empty($item['high_res_image']) ? $item['high_res_image'] : array();
				$img = wp_is_mobile() ? $imgSmall : ( !empty($imgLarge) ? $imgLarge : $imgSmall );
				$title = !empty($item['title']) ? $item['title'] : '';
				$description = !empty($item['description']) ? $item['description'] : '';

				//DETERMINE WIDTHS & MARGINS
				$itemCount++;
				$imgDetails = getimagesize( $img['url'] );
				$percentWidth = (($imgDetails[0] / $imgDetails[1]) / $ratioTotal)*(100-((($numImages-1)*$gutterRatio)*100));
				$imgGutter = $percentWidth * $gutterRatio;
				
				if($itemCount===1) $margin = 'margin: 0 '.($gutterRatio/2*100).'% '.($gutterRatio*100).'% 0;';
				elseif($itemCount%$numImages === 0) $margin = 'margin: 0 0 '.($gutterRatio*100).'% '.($gutterRatio/2*100).'%;';
				else $margin = 'margin: 0 '.($gutterRatio/2*100).'% '.($gutterRatio*100).'%';
				?>
					<figure class="galleryItem" style="width:<?php echo $percentWidth; ?>%; <?php echo $margin; ?>">
						<img src="<?php echo $img['url']; ?>" alt="<?php echo !empty($img['description']) ? $img['description'] : 'Image of '.$title; ?>" />
						<?php if(!empty($title) || !empty($description)): ?>
						<div class="caption">
							<?php if(!empty($title)) echo "<h3>$title</h3>"; ?>
							<?php if(!empty($description)) echo "<p>$description</p>"; ?>
						</div>
						<?php endif;//end if !empty $title or $description ?>
					</figure>
				<?php
			endforeach;//end foreach $galleryRow as $item
			echo '</div><!-- .galleryRow -->';
		endforeach;//end foreach $gallery as $galleryRow
		echo '</div><!-- #gallery -->';
	endif;
?>

<?php


/* CONSTANTS
**********************/

/*	GLOBALS
**********************/
$self_closing_tags = ["area","base","br","col","command","embed","hr","img","input","keygen","link","meta","param","source","track","wbr"];
$feedback = [];


/*	FUNCTIONS
**********************/

/*
*	Output the html of the feedback messages
*	
*	array( string $alert_css_class , string $alert_message )
*	
*	$alert_css_class = success | info | warning | danger
*	$alert_message = The aler message.
*	
*/
function feedback($styles){
	global $feedback;
	
	$output = "";
	foreach( $feedback as $feed){
		$output .= '<p class="msg bg-'.$feed[0].'">'.$feed[1].'</p>';
	}
	echo '<div class="feedback '.$styles.'">' . $output . '</div>';
}



/*	
*	HTML Tags Reports
*	
*/
function get_html_report($file){
	global $self_closing_tags;
	
	if( !$file || $file['name'] == '' || $file['tmp_name'] == '' ){
		return false;
	}
	
	$filename = $file['name'];
	$filepath = $file['tmp_name'];
	
	$content = file_get_contents($filepath); //Get file contents
	preg_match_all("/<([\w\/]+)[^>]*>/", $content, $tags); // Match all tags
	
	$unsorted_tags = array_count_values($tags[1]); //Sum tags by name
	//print_r($unsorted_tags); // DEBUG

	//Group open and close tags by absolute tag name
	$sorted_tags=[];
	foreach($unsorted_tags as $raw_name => $count){
		preg_match("/(?:\/|)(\w*)/", $raw_name, $tag);
		$tag = $tag[1];
	
		if( $tag == $raw_name ){ //Opened
			$sorted_tags[$tag]['open'] = $count;
		}else{ //Closed
			$sorted_tags[$tag]['close'] = $count;	
		}
		
	};
	ksort($sorted_tags); //Sort tags list alphabeticly
	//print_r($sorted_tags); // DEBUG
	
	
	//Get max absolute tag count
	$total_tags_count = 0;
	foreach($sorted_tags as $tag_name => $count){
		if( !isset($count['open']) ){
			$count['open'] = 0;
		};
		if( !isset($count['close']) ){
			$count['close'] = 0;
		};
		
		$tag_count = $count['open'] + $count['close'];
		if( $total_tags_count < $tag_count){
			$total_tags_count = $tag_count;
		}
	}
	
	
	
	ob_start();
	?>
	<table id="stats-table" class="table table-striped">
		<thead>
			<tr>
				<th>Tag Name</th>
				<th>Open</th>
				<th>Close</th>
				<th>Frecuency</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$total_alerts = 0;
		foreach($sorted_tags as $tag_name => $count){
		if( !isset($count['open']) ){
			$count['open'] = 0;
		};
		if( !isset($count['close']) ){
			$count['close'] = 0;
		};
		$tag_total = $count['open'] + $count['close'];
		$tag_porcentage = round($tag_total *100 / $total_tags_count,1);
		$tags_msg = $tag_porcentage ."% / ". $tag_total;
		
		$css_class = "";
		if( !in_array($tag_name, $self_closing_tags) && $count['open'] !== $count['close'] ){
			$total_alerts++;
			$css_class = "alert";
		}
	?>
			<tr class="<?php echo $css_class; ?>">
				<th><?php echo $tag_name; ?></th>
				<td><?php echo $count['open']; ?></td>
				<td><?php echo $count['close']; ?></td>
				<td class="stat">
					<span style="width: <?php echo $tag_porcentage; ?>%; " data-msg="<?php echo $tags_msg; ?>"><span><?php echo $tags_msg; ?></span></span>
				</td>
			</tr>
	<?php
		}
	?>	
		</tbody>
	</table>
	<?php
	$stats = ob_get_contents();
	ob_end_clean();
	
	
	return array(
		'filename' 		=> $filename,
		'total_alerts' 	=> $total_alerts,
		'stats' 		=> $stats
	);
}
?>
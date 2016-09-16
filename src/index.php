<?php
include_once(realpath( __DIR__ . "\inc\config.php"));
include_once(dirname(__FILE__) .'/inc/functions.php');

wl_add_security();
	
$report = false;
if( isset($_POST['submit']) ){
	if( isset($_FILES['frm_file']['name']) ){
		if( $_FILES['frm_file']['name'] == '') {
			wl_add_feedback([
				'type'		=>	'warning',
				'message'	=>	'Please upload a file.'
			]);
		}else{
			if( $_FILES['frm_file']['type'] != "text/html" ){
				wl_add_feedback([
					'type'		=>	'danger',
					'message'	=>	'Only .HTM or .HTML files are supported.'
				]);
			}else{
				$report = get_html_report($_FILES['frm_file']);
			}
		}
	}else{
		wl_add_feedback([
			'type'		=>	'warning',
			'message'	=>	'Please upload a file.'
		]);
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HTML Tags Report - WundermanLab</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- STYLES -->
	<link rel="stylesheet" href="//bootswatch.com/paper/bootstrap.min.css">
	<?php wl_admin_css(); ?>
	<style>
		#main{
			padding-top: 65px;
		}
		#stats-table thead th:nth-child(1){
			width: 100px;
		}
		#stats-table thead th:nth-child(2),
		#stats-table thead th:nth-child(3){
			width: 70px;
			text-align: center;
		}
		#stats-table tbody td{
			text-align: center;
		}
		#stats-table tr.alert th,
		#stats-table tbody tr.alert td:nth-child(2),
		#stats-table tbody tr.alert td:nth-child(3){
			color: red;
			font-weight: bold;
		}
		#stats-table tr.alert th{
			background: pink;
		}
		#stats-table td.stat{
			text-align: left;
		}
		#stats-table td.stat > span{
			display: inline-block;
			background: lightgreen;
			padding: .2em 0 0 .3em;
			line-height: 1.8em;
			font-size: .75em;
			white-space: nowrap;
			min-height: 4px;
		}
		#stats-table td.stat:hover > span:before{
			content: attr(data-msg);
		}
		#stats-table td.stat > span span{
			display: none;
		}
		
		p label{
			font-weight: bold;
		}
		.wdg-report{
			padding-left: 8em;
		}
		.wdg-alerts{
			background: #ffb6c1;
			display: inline-block;
			font-size: 4em;
			width: 1.8em;	
			padding: .2em;
			line-height: 1em;
			text-align: center;
			float: left;
			margin-left: -2em;
			color: red;
			border: 2px solid red;
		}
		.wdg-alerts label{
			font-size: 1.2rem;
			display: block;
			line-height: 1rem;
		}
		.wdg-alerts.alerts-0{
			background: lightgreen;
			color: green;
			border: 2px solid green;
		}
		@media screen and (max-width: 510px){
			h1{
				font-size: 7vw;
			}
		}
		.navbar-brand {
			position: relative;
			padding-left: 65px;
		}
		.navbar-brand img{
			width: 45px;
			display: block;
			position: absolute;
			top: 50%;
			left: 10px;
			transform: translateY(-50%);
		}
		@media screen and (max-width: 480px){
			.btn-mobile{
				width: 100%;
				height: 44px;
			}
		}

	</style>
</head>
<body class="container-fluid">

	<?php include_once("inc/analyticstracking.php") ?>
	
	<div id="wrapper">
		
		<header id="header">
			<nav class="navbar navbar-inverse navbar-fixed-top">
			  <div class="container-fluid">
					
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-menu">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a class="navbar-brand" href="#"><img src="images/logo.png" title="Find the missing tags!" />Tags Report</a>
				</div>
				
				<div class="collapse navbar-collapse" id="mobile-menu">
					<?php wl_login_widget(); ?>
				</div>
			  </div>
			</nav>
		</header>

		<main id="main" class="row">
			
			<?php wl_feedback(['styles' => 'col-sm-8 col-sm-offset-2']); ?>
		
			<div id="content" class="col-sm-8 col-sm-offset-2">
				
				
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#tab-stats" aria-controls="home" role="tab" data-toggle="tab">Report</a></li>
					<li role="presentation" class="pull-right"><a href="#tab-help" aria-controls="messages" role="tab" data-toggle="tab">Help</a></li>
				</ul>
				
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="tab-stats">
					<?php if($report){ ?>
						<h3 class="" data-width="400">Results</h3>
						
						<div class="wdg-report clearfix">
							<div class="wdg-alerts alerts-<?php echo $report['total_alerts']; ?>"><label>Total alerts:</label> <?php echo $report['total_alerts']; ?></div>
							<p><label>File name:</label> <?php echo $report['filename']; ?></p>	
							<p><a href="index.php" class="btn btn-primary">New Report</a></p>
						</div>

						<hr />
						<?php echo $report['stats'];	?>

					<?php }else{ ?>
					
						<h3>New report</h3>
						<p>Upload the HTM/HTML file from which to make the tags statistic report.</p>
						<form id="form" class="form" action="index.php" method="POST" enctype="multipart/form-data">
							<div class="form-group col-sm-8 col-md-6">
								<input type="file" id="frm-file" name="frm_file">
							</div>
							<button id="btn-batch" class="btn btn-primary btn-mobile col-xs-12 col-sm-3 col-md-2" type="submit" name="submit">Make Report</button>
						</form>
					<?php } ?>
					</div>
					<div role="tabpanel" class="tab-pane" id="tab-help">
						<h3>What this tool does?</h3>
						
						<p>The main function of this tool is to determine if there are odd HTML tags in a file, which would mean that some tags where not properly closed.</p>
						<p>This tool also generates a statistical report on the use of HTML tags.</p>
						<ul>
							<li>The labels are automatically detected by means of regular expressions.</li>
							<li><a href="#self-closing-tags">Self closing tags</a> are excluded from the alert.</li>
						</ul>
						
						<h4 id="report-columns">Report columns</h4>
						<ul>
							<li><label>Tag Name:</label> The found tag name</li>
							<li><label>Open:</label> Number of opening tags, ex.: &lt;a href="..."&gt;, &lt;br /&gt;, &lt;img src=&quot;...&quot; /&gt;, etc.</li>
							<li><label>Close:</label> Number of closing tags, ex.: &lt;/a&gt;, &lt;/tr&gt;, etc.</li>
							<li><label>Frequency:</label> The porcentage of ocurrence of this tag aginst the most used tag.</li>
						</ul>
						
						<h4 id="self-closing-tags">Self closing tags</h4>
						<p>List of all valid HTML <a href="https://www.w3.org/TR/html5/syntax.html#void-elements">void-elements</a> that are excluded from the alert report.</p>
						<ul>
							<li>area</li>
							<li>base</li>
							<li>br</li>
							<li>col</li>
							<li>command</li>
							<li>embed</li>
							<li>hr</li>
							<li>img</li>
							<li>input</li>
							<li>keygen</li>
							<li>link</li>
							<li>meta</li>
							<li>param</li>
							<li>source</li>
							<li>track</li>
							<li>wbr</li>
						</ul>
						
						<h4 id="tip-compile-multiple-files-in-a-single-big-one">Tip: Compile multiple files in a single big one</h4>
						<p>In order make a quick validation on multiple files you can compile all the individual files in a single one and upload that file to the tool.</p>
						<ul>
							<li><strong>Pros</strong>: You can quickly validate a large number of files in a single report.</li>
							<li><strong>Cons</strong>: You will not know in which file is the specific missing tag.</li>
						</ul>
						
						<h5 id="windows">Windows</h5>
						<ol>
							<li>Open a <a href="https://www.youtube.com/watch?v=X3NtiEbNe-c">CLI</a> windows on the parent folder containing all the files to compile.</li>
							<li>Run this command: <pre><code><span class="variable">$ </span>copy *.htm compiled_list.txt
							</code></pre></li>
							<li>All the files with <strong>HTM</strong> extension will be compiled inside the new file <strong>compiled_list.txt</strong>.</li>
							<li>Upload <strong>compiled_list.txt</strong> file to the tool.</li>
							<li>Done!</li>
						</ol>
						
						<h5 id="mac">Mac</h5>
						<p>TBD</p>
					</div>
				</div>
			</div><!-- /#content -->
		</main>
	</div>
	
	
	<!-- SCRIPTS -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="//rawgit.com/Lukas238/better-input-file/master/src/betterInputFileButton.js"></script>
	<script>
		$('input:file').betterInputFile({
			'btnClass': 'btn btn-secondary',
			'placeholder': '  No file selected'
		});
			
	</script>
	
</body>
</html>
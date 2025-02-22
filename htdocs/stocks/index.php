<!-- Stocks Page -->
<?php 
	include '../../private/common/initialization.php';
	$page_title = 'Stocks';
?>

<?php include '../common/top/top.php'; ?>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="https://d3v3cbxkdlyonc.cloudfront.net/stocks/favicon.ico">
		<link rel="stylesheet" type="text/css" href="stocks-style.css">
	</head>

<?php
	include 'content-stocks.php'; 
?>

<?php include '../common/bottom/bottom.php'; ?>
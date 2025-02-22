<!-- Crypto Page -->
<?php
	include_once '../../private/common/initialization.php';
	$page_title = 'Crypto';
?>

<?php include_once '../common/top/top.php'; ?>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="https://d3v3cbxkdlyonc.cloudfront.net/stocks/favicon.ico">
	<link rel="stylesheet" type="text/css" href="crypto-style.css">
</head>

<div class="f-island">
	<!-- Title -->
	<h1 style="margin-bottom 10px; font-size: 20px; width: 100%; text-align: center;">Cryptocurrency Rates</h1>

	<!-- This is what holds all the Stocks and the Prices -->
	<div class="stocks-container"></div>

	<!-- This is the footer of the the whole body -->
	<p class="updated-timestamp"></p>
</div>

<?php include_once 'crypto-js.php'; ?>

<?php include_once '../common/bottom/bottom.php'; ?>
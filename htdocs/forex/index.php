<!-- Forex Page -->
<?php 
	include_once '../../private/common/initialization.php';
	$page_title = 'Forex';
?>

<?php include_once '../common/top/top.php'; ?>

<div class="f-island">
	<h1 style="margin-bottom 10px; font-size: 20px; width: 100%; text-align: center;">Foreign Exchange Rates</h1>

	<!-- Trading view widget container -->
	<div style="margin: 0 auto;">
		<!-- TradingView Widget BEGIN -->
		<div class="tradingview-widget-container" style="margin: 0 auto;">
		<div class="tradingview-widget-container__widget"></div>
		<div class="tradingview-widget-copyright">
		<a href="https://www.tradingview.com/markets/currencies/forex-cross-rates/" rel="noopener" target="_blank">
		<span class="blue-text">Forex Rates</span></a> by TradingView</div>
		<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-forex-cross-rates.js" async>
		{
		"width": 770,
		"height": 400,
		"currencies": [
			"EUR",
			"USD",
			"JPY",
			"GBP",
			"CHF",
			"AUD",
			"CAD",
			"NZD",
			"CNY"
		],
		"locale": "en"
		}
		</script>
		</div>
		<!-- TradingView Widget END -->
	</div>
</div>

<?php include_once '../common/bottom/bottom.php'; ?>
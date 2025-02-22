<div class="trading-view-widget" id="tradingview_61cac"></div>
<div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/symbols/<?php echo $tickerSearch ?>/" rel="noopener" target="_blank"><span class="blue-text"><?php echo $tickerSearch ?> chart</span></a> by TradingView</div>
<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<script type="text/javascript">
	new TradingView.widget(
		{
			"autosize": true,
			"symbol": "<?php echo $tickerSearch ?>",
			"interval": "D",
			"timezone": "Etc/UTC",
			"theme": "Light",
			"style": "1",
			"locale": "en",
			"toolbar_bg": "rgba(242,242,242, 0.8)",
			"enable_publishing": false,
			"hide_legend": true,
			"allow_symbol_change": true,
			"save_image": false,
			"container_id": "tradingview_61cac"
		}
	);
</script>
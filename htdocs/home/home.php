
<div class="home-flex-container">
	<!-- Main Content --> 
	<div class="home-main-content">
		<!-- Indexes -->
		<table class="home-index-container">
			<td>
				<div class="f-island home-index-chart-container" style="margin-left: 0;">
					<div class="home-chart-img-container">
						<?php include_once 'IndexCharts/spyChart.php'; ?>
					</div>

					<div></div>
				</div>
			</td>

			<td>
				<div class="f-island home-index-chart-container">
					<div class="home-chart-img-container">
						<?php include_once 'IndexCharts/diaChart.php'; ?>
					</div>

					<div></div>
				</div>
			</td>

			<td>
				<div class="f-island home-index-chart-container" style="margin-right: 0;">
					<div class="home-chart-img-container" style="height: 100%;">
						<?php include_once 'IndexCharts/qqqChart.php'; ?>
					</div>

					<div></div>
				</div>
			</td>
		</table>

		<!-- News -->
		<div class="f-island home-news-container" id="home-news-container"><h1 style="font-size: 1em;">News<h1></div>
	</div>

	<!-- Side Bar -->
	<div class="home-side-bar">
		<div class="f-island">
			<a href="/crypto" style="text-decoration: none;">
				<div id="crypto-container" class="stocks-container">
					<table style="width: 100%;">
						<thead>
							<tr>
								<th></th>
								<th class="home-stock-price">Last</th>
							</tr>
						</thead>
	
						<tbody>
							<tr class="home-crypto-odd">
								<td class="home-stock-price" id="btc">BTC</td>
								<td class="home-stock-price" id="btcusdtPrice" ></td>
							</tr>
							<tr>
								<td class="home-stock-price" id="xrp">XRP</td>
								<td class="home-stock-price" id="xrpusdtPrice"></td>
							</tr>
							<tr class="home-crypto-odd">
								<td class="home-stock-price" id="eth">ETH</td>
								<td class="home-stock-price" id="ethusdtPrice"></td>
							</tr>
							<tr>
								<td class="home-stock-price"id="eos">EOS</td>
								<td class="home-stock-price" id="eosusdtPrice"></td>
							</tr>
							<tr class="home-crypto-odd">
								<td class="home-stock-price"id="xlm">XLM</td>
								<td class="home-stock-price"id="xlmusdtPrice"></td>
							</tr>
							<tr>
								<td class="home-stock-price"id="ltc">LTC</td>
								<td class="home-stock-price" id="ltcusdtPrice"></td>
							</tr>
							<tr class="home-crypto-odd">
								<td class="home-stock-price"id="ada">ADA</td>
								<td class="home-stock-price"id="adausdtPrice"></td>
							</tr>
						</tbody>    
					</table>
				</div> 
			</a>
		</div>

		<div class="f-island" style="margin-top: 20px; padding: 0px;">
			<!-- Google Adsense -->
			<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- Front Page -->
			<ins class="adsbygoogle"
				style="display:block"
				data-ad-client="ca-pub-7448431637079222"
				data-ad-slot="6330169123"
				data-ad-format="auto"
				data-full-width-responsive="true"></ins>
			<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
			
			<h6 style="text-align: center; color: grey;">Sponsor</h6>
		</div>
	</div>
</div>

<?php
	include_once 'home/home-news-js.php';
	include_once 'home/home-crypto-js.php';
?>
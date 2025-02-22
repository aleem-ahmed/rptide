<div class="f-island">
	<!-- TradingView Widget BEGIN -->
	<div class="tradingview-widget-container">
		<div class="tradingview-widget-container__widget"></div>
		<div class="tradingview-widget-copyright"><a href="https://www.tradingview.com" rel="noopener" target="_blank"><span class="blue-text">Ticker Tape</span></a> by TradingView</div>
		<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
		{
		"symbols": [
			{
			"title": "S&P 500",
			"proName": "OANDA:SPX500USD"
			},
			{
			"title": "Nasdaq 100",
			"proName": "OANDA:NAS100USD"
			},
			{
			"title": "EUR/USD",
			"proName": "FX_IDC:EURUSD"
			},
			{
			"title": "BTC/USD",
			"proName": "BITSTAMP:BTCUSD"
			},
			{
			"title": "ETH/USD",
			"proName": "BITSTAMP:ETHUSD"
			}
		],
		"colorTheme": "light",
		"isTransparent": false,
		"displayMode": "adaptive",
		"locale": "en"
		}
		</script>
	</div>
	<!-- TradingView Widget END -->
	
	<h1 style="margin-bottom 10px; font-size: 20px; width: 100%; text-align: center;">Key Equities</h1>
	
	<body>
		<!-- This is what holds all the Stocks and the Prices -->
		<div class="stocks-container"></div>
	</body>
</div>

<script>
	'use strict';

	/*
	===========================
		Variables
	===========================
	*/
	/* Array of arrays */
	const DEFAULT_PORTFOLIOS = [
		/* Array [ property: ______, property: Array[ticker, ticker,..] ] */
		{'name': 'Market ETFs', 'symbols': ['SPY', 'DIA', 'QQQ', 'IWM']},
		{'name': 'Banks', 'symbols': ['GS', 'MS', 'JPM', 'WFC', 'C', 'BAC', 'BCS', 'DB', 'CS', 'RBS']},
		{'name': 'Tech', 'symbols': ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'FB', 'TWTR', 'NFLX', 'SNAP', 'SPOT', 'DBX', 'SQ', 'SFIX', 'BABA', 'INTC', 'AMD', 'NVDA', 'ORCL']},
		{'name': 'Other Large Companies', 'symbols': ['XOM', 'WMT', 'JNJ', 'GE', 'T', 'KO', 'DIS', 'MCD', 'PG']},
		{'name': 'Autos', 'symbols': ['F', 'GM', 'FCAU', 'TM', 'HMC', 'TSLA']},
		{'name': 'Mortgage REITs', 'symbols': ['EFC', 'EARN', 'NLY', 'AGNC', 'CIM', 'TWO', 'NRZ']},
		{'name': 'Bond ETFs', 'symbols': ['BND', 'BIV', 'JNK']},
		{'name': 'Other ETFs', 'symbols': ['VOO', 'VTI', 'VGK', 'VPL', 'VWO', 'VDE', 'XOP', 'VFH', 'VHT', 'VIG', 'VYM', 'VAW', 'REM', 'XHB', 'XRT', 'GLD', 'SHV', 'FLOT', 'BKLN', 'MJ']},
		{'name': 'Sector ETFs', 'symbols': ['XLF', 'XLK', 'XLC', 'XLV', 'XLP', 'XLY', 'XLE', 'XLB', 'XLI', 'XLU', 'XLRE']}
	];

	/* 
	* Choose tickers from query(run function) OR("||") choose preset ones
	* Refresh Time
	* How large is each batch (how many stocks can you add)
	* Base URL for api data
	* What ticker is the image based on (up and down stock badge)
	* Badge badge symbol URL
	* Symbols = spy temporarily
	* ContainerDiv = div that holds table
	* updatedDiv = div that holds time stamp 
	*/
	const PORTFOLIOS = DEFAULT_PORTFOLIOS; 
	const REFRESH_SECONDS = 10; 
	const BATCH_SIZE = 100;
	const BASE_URL = 'https://cloud.iexapis.com/stable/stock/market/batch';
	const FAVICON_SYMBOL = 'SPY';
	const FAVICON_BASE_URL = 'https://d3v3cbxkdlyonc.cloudfront.net/stocks';
	let symbols = [FAVICON_SYMBOL];
	let containerDiv = document.querySelector('.stocks-container');
	let updatedDiv = document.querySelector('.updated-timestamp');

	/*
	===========================
		Calls and Sends
	===========================
	*/
	//LOOP For each (parameter, integer) send each to "addPortfolio". Array of Arrays
	PORTFOLIOS.forEach( (p, i) => addPortfolio(p, i === 0) );

	//set "symbols" to array of Tickers. Array of Tickers
	symbols = symbols.filter( (s, i) => symbols.indexOf(s) === i );
	
	//CALL updateData function send 'addTitle'
	updateData('addTitle'); 

	//repeat timer
	//setInterval(updateData, REFRESH_SECONDS * 1000);

	/*
	===========================
		Functions
	===========================
	*/
	/* FUNCTION that creats the table to put into '<div class="stocks-container">' */
	function addPortfolio(portfolio, includeHeader) {
		
		/* CREATE a variable "tableHeaderHtml" that holds.. */
		let tableHeaderHtml = '';
		
		if (includeHeader) {
			/* The header HTML code */
			tableHeaderHtml = ` 
				<thead>
					<tr>
						<th></th>
						<th class="stock-price">Last</th>
						<th class="stock-change">Change</th>
						<th class="stock-change-pct">Change%</th>
						<th class="stock-mkt-cap">Mkt Cap</th>
					</tr>
				</thead>
			`
		}

		/* CREATE a variable "tableBodyHTML" that holds.. */
		let tableBodyHtml = portfolio.symbols.map( symbol => {
			symbol = symbol.toUpperCase();
			symbols.push(symbol);

			let html = `
				<tr data-symbol="${symbol}">
					<td class="stock-symbol"><a href="${symbolUrl(symbol)}" target="_blank">${symbol}</a></td>
					<td class="stock-price"></td>
					<td class="stock-change"></td>
					<td class="stock-change-pct"></td>
					<td class="stock-mkt-cap"></td>
				</tr>
			`
			return html;
			
		/* Repeat and add */
		}).join('');

		/* Assemble the whole table here */
		let portfolioDiv = document.createElement('div');

		portfolioDiv.innerHTML = `
			<details open>
				<summary><h2>${portfolio.name}</h2></summary>
				<table>${tableHeaderHtml}<tbody>${tableBodyHtml}</tbody></table>
			</details>
		`;
		
		/* add ^ to whatever is already in "ContainerDiv" */
		containerDiv.appendChild(portfolioDiv);
	}

	/* FUNCTION Update the data */
	function updateData(addTitle) {
		/* numberOfBatches = (symbols.length / 100). Math.ceil rounds up to the neared integer */
		let numberOfBatches = Math.ceil(symbols.length / BATCH_SIZE);
		
		/* loop for each item */
		for (let i = 0; i < numberOfBatches; i++) {
			let symbolsBatch = symbols.slice(i * BATCH_SIZE, (i + 1) * BATCH_SIZE);
			updateDataForBatch(symbolsBatch, addTitle);
		}

		/* Time stamp of update time */
		updatedDiv.innerHTML = `Data updated at ${(new Date()).toLocaleString()}`;
	}

	/* FUNCTION get the Data */
	function updateDataForBatch(symbols, addTitle) {
		let filters = ['latestPrice', 'change', 'changePercent', 'marketCap'];
		if (addTitle) {
			filters.push('companyName');
		}
		let url = `${BASE_URL}?types=quote&symbols=${symbols.join(',')}&filter=${filters.join(',')}&token=pk_e5ed27ba56d44b68879cbf359767b91c`;

		fetch(url).then(response => response.json()).then(json => {
			/* Loop */
			symbols.forEach(symbol => {
				let data = json[symbol];
				if (typeof(data) === 'undefined') return;

				/* Format the numbers ans set the colors */
				let formattedPrice = formatQuote(data.quote.latestPrice); //Price 
				let formattedChange = data.quote.change.toLocaleString('en', {'minimumFractionDigits': 2}); //Change
				let formattedChangePercent = (data.quote.changePercent * 100).toFixed(1) + '%'; //Change %
				let formattedMarketCap = formatMarketCap(data.quote.marketCap); //Market cap format
				let rgbColor = data.quote.changePercent > 0 ? '0,255,0' : '255,0,0'; //color (red to green)
				let rgbOpacity = Math.min(Math.abs(data.quote.changePercent) * 20, 1); // change in color (red to green)

				/* UPDATE Change the data in the table to g^ */
				document.querySelectorAll(`[data-symbol="${symbol}"] .stock-price`).forEach(e => {
					e.innerHTML = formattedPrice;
					e.setAttribute('style', `background-color: rgba(${rgbColor}, ${rgbOpacity})`);
				});

				document.querySelectorAll(`[data-symbol="${symbol}"] .stock-change`).forEach(e => {
					e.innerHTML = formattedChange;
					e.setAttribute('style', `background-color: rgba(${rgbColor}, ${rgbOpacity})`);
				});

				document.querySelectorAll(`[data-symbol="${symbol}"] .stock-change-pct`).forEach(e => {
					e.innerHTML = formattedChangePercent;
					e.setAttribute('style', `background-color: rgba(${rgbColor}, ${rgbOpacity})`);
				});

				document.querySelectorAll(`[data-symbol="${symbol}"] .stock-mkt-cap`).forEach(e => {
					e.innerHTML = formattedMarketCap;
					e.setAttribute('style', `background-color: rgba(${rgbColor}, ${rgbOpacity})`);
				});

				if (addTitle) {
					document.querySelectorAll(`[data-symbol="${symbol}"] .stock-symbol a`).forEach(e => {
						e.setAttribute('title', data.quote.companyName);
					});
				}
			});
		});
	}

	/* FUNCTION Base URL for single ticker. link to iex website. */
	function symbolUrl(symbol) {
		return `/search/?s=${symbol}`;
	}

	/* FUNCTION Format the price so that it does not have alot of fraction digits */
	function formatQuote(value) {
		let options = {
			'minimumFractionDigits': 2,
			'style': 'currency',
			'currency': 'USD'
		};
		return value.toLocaleString('en', options);
	}

	/* FUNCTION Format the marketcap to have either M, B, OR T to represent size of company */
	function formatMarketCap(marketCap) {
		if (marketCap === null) return '';

		let value, suffix;
		if (marketCap >= 1e12) {
			value = marketCap / 1e12;
			suffix = 'T';
		} else if (marketCap >= 1e9) {
			value = marketCap / 1e9;
			suffix = 'B';
		} else {
			value = marketCap / 1e6;
			suffix = 'M';
		}

		let digits = value < 10 ? 1 : 0;

		return '$' + value.toFixed(digits) + suffix;
	}
</script>
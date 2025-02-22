<script>
	'use strict';
	/*
	 * ========= *
	 * Variables *
	 * ========= *
	 */
	const DEFAULT_PORTFOLIOS = [
		/* Array [ property: ______, property: Array[ticker, ticker,..]] */
		{
			'symbols': [
				'BTCUSDT',
				'EOSUSDT',
				'ETHUSDT',
				'BNBUSDT',
				'ONTUSDT',
				'ADAUSDT', 
				'XRPUSDT',
				'TUSDUSDT',
				'TRXUSDT',
				'LTCUSDT',
				'ETCUSDT',
				'IOTAUSDT',
				'ICXUSDT',
				'NEOUSDT',
				'XLMUSDT',
				'QTUMUSDT'
			]
		}
		
	];

	/* 
	* Array of Tickers
	* Base URL for api data
	* What ticker is the image based on (up and down stock badge)
	* Badge badge symbol URL
	* Symbols = spy temporarily
	* ContainerDiv = div that holds table
	*/
	const PORTFOLIOS = DEFAULT_PORTFOLIOS; 
	const BASE_URL = 'https://cloud.iexapis.com/stable/stock/market/batch';
	const FAVICON_SYMBOL = 'SPY';
	const FAVICON_BASE_URL = 'https://d3v3cbxkdlyonc.cloudfront.net/stocks';
	let symbols = [FAVICON_SYMBOL];
	let containerDiv = document.querySelector('.stocks-container');

	/*
	 * =============== *
	 * Calls and Sends *
	 * =============== *
	 */
	//LOOP For each (parameter, integer) send each to "addPortfolio". Array of Arrays
	PORTFOLIOS.forEach( (p, i) => addPortfolio(p, i === 0) );

	/* set "symbols" to array of Tickers. Array of Tickers */
	symbols = symbols.filter( (s, i) => symbols.indexOf(s) === i );

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
		let tableBodyHtml = portfolio.symbols.map(symbol => {
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
		}).join('');

		/* Assemble the whole table here */
		let portfolioDiv = document.createElement('div');

		portfolioDiv.innerHTML = `
			<table>${tableHeaderHtml}<tbody>${tableBodyHtml}</tbody></table>
		`;
		
		/* add ^ to whatever is already in "ContainerDiv" */
		containerDiv.appendChild(portfolioDiv);
	}

	/* FUNCTION Updates the data */
	function updateDataForBatch(symbols, addTitle) {
		let filters = ['latestPrice', 'change', 'changePercent', 'marketCap'];
		
		if (addTitle) { 
			filters.push('companyName'); 
		}

		let url = `${BASE_URL}?types=quote&symbols=${symbols.join(',')}&filter=latestPrice,change,changePercent,marketCap&token=pk_e5ed27ba56d44b68879cbf359767b91c`;

		fetch(url).then(response => response.json()).then(json => {
			symbols.forEach(symbol => {
				let data = json[symbol];
				if (typeof(data) === 'undefined') {
					return "";
				}

				/* CALL(s) */
				let formattedPrice = formatQuote(data.quote.latestPrice); /* Price */
				let formattedChange = data.quote.change.toLocaleString('en', {'minimumFractionDigits': 2}); /* Change */
				let formattedChangePercent = (data.quote.changePercent * 100).toFixed(1) + '%'; /* Change % */
				let formattedMarketCap = formatMarketCap(data.quote.marketCap); /* Market cap format */
				let rgbColor = data.quote.changePercent > 0 ? '0,255,0' : '255,0,0'; /* color (red to green) */
				let rgbOpacity = Math.min(Math.abs(data.quote.changePercent) * 20, 1); /*  change in color (red to green) */

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
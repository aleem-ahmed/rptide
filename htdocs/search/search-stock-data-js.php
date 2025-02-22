<script>
	var symbol = "<?php echo $tickerSearch ?>";

	/* Set the title of the document and empty the search field */
	document.title = symbol; 
	document.getElementById("sf").value = "";
	
	symbol = symbol.trim(); //remove spaces
	var containerDiv = document.getElementById('search-data-div');
	var newsContainer = document.getElementById('search-news-container');
	
	/* JSON Reading */
	var request = new XMLHttpRequest();
	request.open ('GET', `https://cloud.iexapis.com/stable/stock/${symbol}/batch?types=company,quote,stats,earnings,news,dividends&token=pk_e5ed27ba56d44b68879cbf359767b91c`)
	request.send()

	/* Request Onload */     
	request.onload = function() {
		var iexData = JSON.parse(request.responseText); //Convert to readable file
		var articleAmount = iexData.news.length;
		var listHTML = '';

		/* Check if search is good or bad */
		if (request.status == 200) {
			createStockData();
			outputToHTML(iexData);
			/* inject into the html */
			newsContainer.innerHTML = newsContentCreator(articleAmount, listHTML);

			for (var i = 0; i < iexData.news.length; i++) {
				newsOutputToHTML(iexData.news, i);
			}
		}
	};

	/* === FUNCTION createStockData() Creats the table to put into html' */
	function createStockData() {
		/* Assemble the whole table here */
		let assembler = document.createElement('div');
		
		assembler.innerHTML = `
			<?php include 'search-stock-data-js-table.php'; ?>
		`;

		/* SEND to HTML "assembler" */
		containerDiv.appendChild(assembler);
	}
	
	/* === FUNCTION OutputToHtml() Get Elements -> RETRIEVE DATA FROM IEX -> INJECT */
	function outputToHTML(data) {
		/* --- GET ELEMENTS (Website Order) -------------------------------- */
		/* Title and Header Div */
		var companyName = document.getElementById("search-title-company-name");
		var titleExchange = document.getElementById("search-title-exchange");
		var website = document.getElementById("search-title-company-website");
		var titleLast = document.getElementById("search-title-last");
		var titleChangePct = document.getElementById("search-title-change-pct");

		/* Sector div */
		var sector = document.getElementById("search-sector-td")

		/* Technicals Table */
		var last = document.getElementById("search-last-td");
		var change = document.getElementById("search-change-td");
		var changePct = document.getElementById("search-change-pct-td");
		var volume = document.getElementById("search-volume-td");
		var mrktCp = document.getElementById("search-mkt-cp-td");
		var shrsOutstanding = document.getElementById("search-shrs-outstanding-td");
		var pe = document.getElementById("search-pe-td");
		var avgVolume = document.getElementById("search-avg-volume-td");
		var dividend = document.getElementById("search-dividend-td");
		var dividendPct = document.getElementById("search-dividend-pct-td");
		var exDate = document.getElementById("search-ex-date-td");
		var paymentDate = document.getElementById("search-payment-date-td");

		/* Ratio Table */
		var eps = document.getElementById("search-eps-td");
		var week52H = document.getElementById("search-week52H-td");
		var week52L = document.getElementById("search-week52L-td");
		var float = document.getElementById("search-float-td");
		var sma50 = document.getElementById("search-50ma-td");
		var sma200 = document.getElementById("search-200ma-td");

		/* Other Info Table */
		var ceo = document.getElementById("search-ceo-td");
		var earningsDate = document.getElementById("search-earnings-date-td");

		/* Description */
		var description = document.getElementById("search-descrption-div");

		/* --- RETRIEVE DATA FROM IEX (IEX JSON order) -------------------------------- */
		/* IEX Data Source: Company */
		var jsonCompanyName = data.company.companyName;
		var jsonExchange = data.company.exchange;
		var jsonWebsite = data.company.website;
		var jsonCeo = data.company.CEO;
		var jsonSector = data.company.sector;

		/* IEX Data Source: Quote */
		var jsonLast = data.quote.latestPrice;
		var jsonVolume = formatNumber(data.quote.volume);
		var jsonChange = data.quote.change;
		var jsonChangePctColor = (data.quote.changePercent * 100).toFixed(2);
		var jsonChangePct = ((data.quote.changePercent * 100).toFixed(2) + '%');  
		var jsonAvgVolume = formatNumber(data.quote.avgTotalVolume);
		var jsonMrktCp = ('$'+ formatNumber(data.quote.marketCap));
		var jsonPe = data.quote.peRatio;
		var jsonWeek52H = data.quote.week52High;
		var jsonWeek52L = data.quote.week52Low;

		/* IEX Data Source: Stats */
		var jsonShrsOutstanding = formatNumber(data.stats.sharesOutstanding);
		var jsonFloat = formatNumber(data.stats.float);
		var jsonEps = (data.stats.ttmEPS).toFixed(3);
		var json200ma = (data.stats.day200MovingAvg).toFixed(3);
		var json50ma = (data.stats.day50MovingAvg).toFixed(3);
		var jsonDividend = data.stats.ttmDividendRate;
		var jsonDividendPct = data.stats.dividendYield;

		/* IEX Data Source: Dividends */
		var jsonDividends = data.dividends;

		/* IEX Data Source: Earnings */
		var jsonEarningsDate = data.earnings.earnings[0].EPSReportDate;

		/* --- FORMAT THEN INJECT INTO HTML (Website Order) -------------------------------- */
		/* Title */
		companyName.insertAdjacentHTML('beforeend', jsonCompanyName);
		titleExchange.insertAdjacentHTML('beforeend', jsonExchange);
		titleLast.innerHTML = jsonLast;
		titleChangePct.innerHTML = ('(' + jsonChangePct + ')');

		/* Sector Div */
		sector.innerHTML = jsonSector;

		/* Technical Table */
		last.innerHTML = jsonLast;
		change.innerHTML = jsonChange;
		changePct.innerHTML = jsonChangePct;

		if (jsonVolume == null) {
			jsonVolume = "No Volume";
		}

		volume.innerHTML = jsonVolume;
		mrktCp.innerHTML = jsonMrktCp;
		shrsOutstanding.innerHTML = jsonShrsOutstanding;
		pe.innerHTML = jsonPe;
		avgVolume.innerHTML = jsonAvgVolume;

		if (jsonDividend == null) {
			jsonDividend = "0";
			jsonDividendPct = "0%";			
		} else {
			jsonDividendPct = (data.stats.dividendYield) * 100;
			jsonDividendPct = ((jsonDividendPct).toFixed(3) + '%');
		}

		dividend.innerHTML = jsonDividend;
		dividendPct.innerHTML = jsonDividendPct;

		if (jsonDividends.length > 0) {
			var index = (jsonDividends.length - 1);

			exDate.innerHTML = data.dividends[index].exDate;
			paymentDate.innerHTML = data.dividends[index].paymentDate;
		} else {
			exDate.innerHTML = "NA";
			paymentDate.innerHTML = "NA";
		}
		
		/* Ratio Table */
		eps.innerHTML = jsonEps;
		week52H.innerHTML = jsonWeek52H;
		week52L.innerHTML = jsonWeek52L;
		float.innerHTML = jsonFloat;
		sma50.innerHTML = json50ma;
		sma200.innerHTML = json200ma;
		
		/* Other Table */
		ceo.innerHTML = jsonCeo;
		earningsDate.innerHTML = jsonEarningsDate;
		website.href = jsonWebsite;
		website.target = "_blank";
		website.rel = "noopener noreferrer";
		

		/* --- Change Color for "titleLast" Broswer Tab Format-------------------------------- */
		if (jsonChangePctColor < 0) {
			titleLast.setAttribute('style', `font-size: 25px; display: inline; font-weight: bold; color: red;`);
			titleChangePct.setAttribute('style', `font-size: 25px; display: inline; color: red;`);
		} else {
			titleLast.setAttribute('style', `font-size: 25px; display: inline; font-weight: bold; color: green;`);
			titleChangePct.setAttribute('style', `font-size: 25px; display: inline; color: green;`);  
		}

		document.title = ("[" + document.title + " - " + jsonLast + "]");
	}

	/* === FUNCTION formatNumber() Format the number to have either M, B, OR T */
	function formatNumber(number) {
		if (number === null) { return null; }
		let value, suffix;
		if (number >= 1e12) {
			value = number / 1e12;
			suffix = 'T';
		} else if (number >= 1e9) {
			value = number / 1e9;
			suffix = 'B';
		} else {
			value = number / 1e6;
			suffix = 'M';
		}
		let digits = value < 10 ? 3 : 0;
		return value.toFixed(digits) + suffix;
	}

	/* === FUNCTION newsContentCreator() Create News Table */
	function newsContentCreator(articleAmount, listHTML) {
		//loop for the amount of objects in the JSON
		for (var i = 0; i < articleAmount; i++) {
			/* "Assembler" inside is "createTable()" */
			listHTML = listHTML + ` 
				<a id="url${i}" href="http://www.rptide.com">
					<div class="search-news-item">
						<p id="headline${i}" style="float: left; margin: 0;"></p>
						<p id="dateTime${i}" style="float: right; margin: 0;"></p>
					</div> 
				</a>
			`;
		}
		return listHTML;
	}

	/* === FUNCTION newsOutputToHTML() Output News */
	function newsOutputToHTML(data, i) {
		/* Get current variables in this HTML page x3 */
		var dateTime = document.getElementById(`dateTime${i}`);
		var headline = document.getElementById(`headline${i}`);
		var source = document.getElementById(`source${i}`);
		var url = document.getElementById(`url${i}`);
		
		/* Get Content From the JSON file ex: ".latestPrice" */
		var jsonDateTime = data[i].datetime;
		var jsonHeadline = data[i].headline;
		var jsonSource = data[i].source;
		var jsonUrl = data[i].url;

		/* Inject data into HTML */
		headline.innerHTML = jsonHeadline;
		dateTime.innerHTML = (jsonSource + " - " + jsonDateTime);
		url.href = `${jsonUrl}`
	}
</script>
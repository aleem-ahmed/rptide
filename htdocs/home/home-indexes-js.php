<script>
	const baseURL = 'https://cloud.iexapis.com'
	const token = 'pk_e5ed27ba56d44b68879cbf359767b91c'
	const symbols = 'spy,qqq,dia'
	/* --- JavaScript (Indexes) --------- */
	/* Variables */
	var indexsRequest = new XMLHttpRequest()

	/* Use IEX API funmction "GET" to recieve data and send */
	indexsRequest.open (
		'GET',
		`${baseURL}/stable/stock/market/batch?symbols=${symbols}&types=quote&token=${token}`
	)
	indexsRequest.send()
	
	/* 
	 * Var "IEXData" converts JSON response to readable data for this code
	 *  Call "spyOutputToHTML" function
	 */         
	indexsRequest.onload = function() {
		var IEXData = JSON.parse(indexsRequest.responseText)
		indexOutputToHTML(IEXData)
	}
				
	/*
	=======================
		FUNCTIONS
	=======================
	*/
	function indexOutputToHTML(data) {
		//get current variables in this HTML page x3
		var spyTitle = document.getElementById("spyTitle")
		var spyLast = document.getElementById("spyLast")
		var spyChange = document.getElementById("spyChange")
		var spyChangePct = document.getElementById("spyChangePct")

		var diaLast = document.getElementById("diaLast")
		var diaChange = document.getElementById("diaChange")
		var diaChangePct = document.getElementById("diaChangePct")

		var qqqLast = document.getElementById("qqqLast")
		var qqqChange = document.getElementById("qqqChange")
		var qqqChangePct = document.getElementById("qqqChangePct")

		//Get Content From the JSON file ex: ".latestPrice"
		var spyJsonLast = data.SPY.quote.latestPrice
		var spyJsonChange = data.SPY.quote.change
		var spyJsonChangePct = ((data.SPY.quote.changePercent*100).toFixed(2))
		var spyJsonChangePctF = ('('+ spyJsonChangePct + '%)')

		var diaJsonLast = data.DIA.quote.latestPrice
		var diaJsonChange = data.DIA.quote.change
		var diaJsonChangePct = ((data.DIA.quote.changePercent*100).toFixed(2))
		var diaJsonChangePctF = ('('+ spyJsonChangePct + '%)')

		var qqqJsonLast = data.QQQ.quote.latestPrice
		var qqqJsonChange = data.QQQ.quote.change
		var qqqJsonChangePct = ((data.QQQ.quote.changePercent*100).toFixed(2))
		var qqqJsonChangePctF = ('(' + qqqJsonChangePct + '%)')

		//Inject data into HTML 
		spyLast.innerHTML = spyJsonLast
		spyChange.innerHTML= spyJsonChange
		spyChangePct.innerHTML = spyJsonChangePctF

		diaLast.innerHTML = diaJsonLast
		diaChange.innerHTML= diaJsonChange
		diaChangePct.innerHTML = diaJsonChangePctF

		qqqLast.innerHTML = qqqJsonLast
		qqqChange.innerHTML= qqqJsonChange
		qqqChangePct.innerHTML = qqqJsonChangePctF

		//change color for "titleLast"
		if (spyJsonChangePct < 0) {
			spyTitle.setAttribute('style', `color: red;`)
			spyLast.setAttribute('style', `color: red;`)
			spyChange.setAttribute('style', `color: red;`)
			spyChangePct.setAttribute('style', `color: red;`)

		} else {
			spyTitle.setAttribute('style', `color: green;`)
			spyLast.setAttribute('style', `color: green;`)
			spyChange.setAttribute('style', `color: green;`)
			spyChangePct.setAttribute('style', `color: green;`)
		}

		if (diaJsonChangePct < 0) {
			diaTitle.setAttribute('style', `color: red;`)
			diaLast.setAttribute('style', `color: red;`)
			diaChange.setAttribute('style', `color: red;`)
			diaChangePct.setAttribute('style', `color: red;`)

		} else {
			diaTitle.setAttribute('style', `color: green;`)
			diaLast.setAttribute('style', `color: green;`)
			diaChange.setAttribute('style', `color: green;`)
			diaChangePct.setAttribute('style', `color: green;`)
		}

		if (qqqJsonChangePct < 0) {
			qqqTitle.setAttribute('style', `color: red;`)
			qqqLast.setAttribute('style', `color: red;`)
			qqqChange.setAttribute('style', `color: red;`)
			qqqChangePct.setAttribute('style', `color: red;`)

		} else {
			qqqTitle.setAttribute('style', `color: green;`)
			qqqLast.setAttribute('style', `color: green;`)
			qqqChange.setAttribute('style', `color: green;`)
			qqqChangePct.setAttribute('style', `color: green;`)
		}
	}
</script>
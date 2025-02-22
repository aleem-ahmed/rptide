<script> 
	/* --- JavaScript (crypto) --------- */
	/* Variables */
	var myCryptoRequest = new XMLHttpRequest(); 
	
	/* IEX API requests open and send to recieve data */
	myCryptoRequest.open ('GET', 'https://cloud.iexapis.com/stable/stock/market/batch?symbols=btcusdt,xrpusdt,ethusdt,eosusdt,xlmusdt,ltcusdt,adausdt&types=quote&token=pk_e5ed27ba56d44b68879cbf359767b91c') //use API function "GET" to recieve data 
	myCryptoRequest.send()

	/* On load of the Data */
	myCryptoRequest.onload = function() {
		var IEXData = JSON.parse(myCryptoRequest.responseText); //var IEXData converts JSON response to readable data for this code
		cryptoOutputToHTML(IEXData); //output it into this HTML file         
	}

	/*
	=======================
		FUNCTIONS
	=======================
	*/
	/* Fuction that gets the data amd outputs it to the HTML */
	function cryptoOutputToHTML(data) {
		/* Get Elements. [BTC, XRP, ETH, BCH, EOS, XLM, LTC, ADA] follow iex order */
		var btc = document.getElementById("btc");
		var xrp = document.getElementById("xrp");
		var eth = document.getElementById("eth");
		var eos = document.getElementById("eos");
		var xlm = document.getElementById("xlm");
		var ltc = document.getElementById("ltc");
		var ada = document.getElementById("ada");

		var btcusdtLast = document.getElementById("btcusdtPrice"); 
		var xrpusdtLast = document.getElementById("xrpusdtPrice");
		var ethusdtLast = document.getElementById("ethusdtPrice");
		var eosusdtLast = document.getElementById("eosusdtPrice");
		var xlmusdtLast = document.getElementById("xlmusdtPrice");
		var ltcusdtLast = document.getElementById("ltcusdtPrice");
		var adausdtLast = document.getElementById("adausdtPrice");

		//Get data. follow iex order
		var btcusdtJSONLast = data.BTCUSDT.quote.latestPrice
		var xrpusdtJSONLast = data.XRPUSDT.quote.latestPrice
		var ethusdtJSONLast = data.ETHUSDT.quote.latestPrice
		var eosusdtJSONLast = data.EOSUSDT.quote.latestPrice
		var xlmusdtJSONLast = data.XLMUSDT.quote.latestPrice
		var ltcusdtJSONLast = data.LTCUSDT.quote.latestPrice
		var adausdtJSONLast = data.ADAUSDT.quote.latestPrice

		//Inject into HTML follow yieldsync order
		btcusdtLast.innerHTML = Number(btcusdtJSONLast).toFixed(3);
		xrpusdtLast.innerHTML = Number(xrpusdtJSONLast).toFixed(3);
		ethusdtLast.innerHTML = Number(ethusdtJSONLast).toFixed(3);
		eosusdtLast.innerHTML = Number(eosusdtJSONLast).toFixed(3);
		xlmusdtLast.innerHTML = Number(xlmusdtJSONLast).toFixed(3);
		ltcusdtLast.innerHTML = Number(ltcusdtJSONLast).toFixed(3);
		adausdtLast.innerHTML = Number(adausdtJSONLast).toFixed(3);
	}
</script>
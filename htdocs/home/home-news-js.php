<script>
	/* --- JavaScript (News) --------- */
	var request = new XMLHttpRequest();
	request.open ('GET', 'https://cloud.iexapis.com/stable/stock/spy/news?token=pk_e5ed27ba56d44b68879cbf359767b91c')
	request.send()

	/* On request load */
	request.onload = function() {
		/* VARIABLES */
		var newsContainer = document.getElementById('home-news-container');
		var jsonData = JSON.parse(request.responseText);
		var articleAmount = jsonData.length;
		var listHTML = '';

		/* inject into the html */
		newsContainer.innerHTML = newsContentCreator(articleAmount, listHTML);
		
		/* Output data to html */
		for (var i = 0; i < jsonData.length; i++) {
			spyOutputToHTML(jsonData, i);
		}
	};

	/* --- NEWS CONTAINER CREATOR --------- */
	function newsContentCreator(articleAmount, listHTML) {
		/* loop for the amount of objects in the JSON */
		for (var i = 0; i < articleAmount; i++) {
			/* "Assembler" inside is "createTable()" */
			listHTML = listHTML + `
			<a id="url${i}" href="" style="text-decoration: none;">
				<div class="home-div-border">
					<div style="float: left; width: 26%;">
						<img id="news-image${i}" class="home-news-img" style="width: 100%;">
					</div>
					
					<div style="float: right; width: 72%;">
						<h3 id="headline${i}"></h3>
						<p id="summary${i}"></p>
					</div>

					<div style="float: left; width: 100%;">
						<p id="source${i}" style="display: inline;"></p>
						<p id="dateTime${i}" style="display: inline;"></p>
					</div>
				</div>
			</a>
			`;
		}

		return listHTML;
	}

	/* --- OUTPUT TO HTML --------- */
	function spyOutputToHTML(data, i) {
		/* Get current variables in this HTML page x3 */
		var url = document.getElementById(`url${i}`);
		var news_image = document.getElementById(`news-image${i}`);
		var headline = document.getElementById(`headline${i}`);
		var summary = document.getElementById(`summary${i}`);
		var source = document.getElementById(`source${i}`);
		var dateTime = document.getElementById(`dateTime${i}`);
		
		/* Get Content From the JSON file ex: ".latestPrice" */
		var jsonDateTime = data[i].datetime;
		var jsonHeadline = data[i].headline;
		var jsonSource = data[i].source;
		var jsonUrl = data[i].url;
		
		/* Get summary -> limit characters */
		var jsonSummary = data[i].summary;
		var jsonSummary = jsonSummary.length > 400 ? jsonSummary.substring(0, 400 - 3) + "..." : jsonSummary;

		var jsonNewsImage = data[i].image;

		/* Inject data into HTML */
		dateTime.innerHTML = jsonDateTime;
		headline.innerHTML = jsonHeadline;
		source.innerHTML = jsonSource;
		url.href = `${jsonUrl}`
		summary.innerHTML = jsonSummary;
		news_image.src = `${jsonNewsImage}`;
	}
</script>
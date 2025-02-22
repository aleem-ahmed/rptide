<!-- Search Page -->
<?php
	include_once '../../private/common/initialization.php';
?>

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/common/top/top.php'; ?>

<?php 
	// Get the Search Query from URL -> Remove spaces, set all to uppercase -> Sanitize
	$tickerSearch = $_GET["s"];
	$tickerSearch = str_replace(' ', '', $tickerSearch);
	$tickerSearch = strtoupper($tickerSearch);
	$tickerSearch = filter_var($tickerSearch, FILTER_SANITIZE_STRING);
?>

<div class="f-island" style="width: 100%;">
	<!-- Stock Search Header -->
	<div id="search-stock-header" style="margin-bottom: 6px;"> 
		<!-- Company Name Container -->
		<div class="search-company-name-container" id="search-title-exchange">
			<a id="search-title-company-website">
				<b style="color: #9bdc93;" id="search-title-company-name">[<?php echo $tickerSearch ?>] </b>
			</a> -			
		</div>

		<!-- Title Last and Change Container -->
		<div class="search-title-last-change-container">
			<p class="search-title-last-and-change" id="search-title-last" style="font-weight: bold;"></p>
			<p class="search-title-last-and-change" id="search-title-change-pct"></p>
		</div>

		<!-- Note Button Container -->
		<div class="search-note-button-container">
			<form>
				<input type="hidden" name="tickerSymbol" value="<?php echo $tickerSearch ?>">
				<button class="btn btn-primary search-note-button">Create Note</button>
			</form>
		</div>		
	</div>
					
	<!-- Trading View Widget -->
	<?php include_once 'search-trading-view-widget.php'; ?>
			
	<!-- Sector label, Data Table, News Containers -->
	<div id="search-data-div"></div>

	<!-- News -->
	<h6 style="margin: 0px; margin-top: 20px; font-weight: bold;">News</h6>
	<div class="search-news-container" id="search-news-container"></div>

	<!-- Company Description -->
	<div class="Stock-data-description" id="search-description-div"></div>
</div>

<?php include_once 'search-stock-data-js.php'; ?>

<?php include_once $_SERVER['DOCUMENT_ROOT'].'/common/bottom/bottom.php'; ?>
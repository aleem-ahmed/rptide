<button class="mobile-menu-button" onclick="openNav()">☰</button>

<div class="side-nav" id="mySidenav">
	<a class="close-btn" href="javascript:void(0)" onclick="closeNav()"> [ x ]</a>

	<a href="/">⌂ Home</a>
	<a href="/stocks">⇅ Stocks</a>
	<a href="/forex">$ Forex</a>
	<a href="/crypto">Ƀ Crypto</a>

	<?php
		// If the user is signed in display:
		if (isset($_SESSION['username'])):
	?>

		<a id="user-sign-out-button-mobile">↦ Sign Out</a>
		<a id="show-user-account-button-mobile">➤ Your Account</a>

	<?php
		else:
	?>    

		<a id="show-sign-up-page-button-mobile">✎ Sign Up</a>
		<a id="show-sign-in-page-button-mobile">➤ Sign In</a>

	<?php
		endif;
	?>
	
</div>
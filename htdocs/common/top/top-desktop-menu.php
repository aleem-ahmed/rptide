<ul class="desktop-menu-list">
	<li><a href="/">⌂ Home</a></li>
	<li><a href="/stocks">⇅ Stocks</a></li>
	<li><a href="/forex">$ Forex</a></li>
	<li><a href="/crypto">Ƀ Crypto</a></li>

	<?php
		// If the user is signed in display:
		if (isset($_SESSION['username'])):
	?>

		<li>
			<a id="user-sign-out-button">↦ Sign Out</a>
		</li>
		<li>
			<a
				id="show-user-account-button"
				style="border-style: solid; border-width: 1px; Border-color: #9bdc94;"
			>➤ Your Account</a>
		</li>

	<?php
		else:
	?>    
		<li>
			<a id="show-sign-up-page-button">✎ Sign Up</a>
		</li>
		<li>
			<a
				id="show-sign-in-page-button"
				style="border-style: solid; border-width: 1px; Border-color: #9bdc94;"
			>➤ Sign In</a>
		</li>

	<?php
		endif;
	?>
</ul>
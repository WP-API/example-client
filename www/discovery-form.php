<form action="" method="GET">
	<input type="hidden" name="step" value="discover" />

	<h2>Step 1: Find the API</h2>
	<p>
		<label>
			Address to start discovery:
			<input type="url" name="uri" class="uri-input" required />
		</label>
		<button type="submit">Begin Discovery</button>
		<!--
		<label class="check-legacy">
			<input type="checkbox" name="legacy"
				<?php if ( $legacy ) echo 'checked' ?> />
			Check for legacy (v2 plugin, pre-WP 4.4) API?
		</label>
		-->
	</p>
</form>

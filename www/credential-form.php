<form action="" method="GET">
	<input type="hidden" name="step" value="preauth" />

	<h2>Step 2: Input Credentials</h2>
	<p>API discovered at <code><?php echo $_SESSION['site_base'] ?></code>.
		<a class="reset" href="?step=reset">Reset?</a></p>

	<p>
		<label>
			Client Key
			<input type="text" name="client_key" required />
		</label>
		<label>
			Client Secret
			<input type="text" name="client_secret" required />
		</label>

		<button type="submit">Begin Authorization</button>
	</p>

	<div class="extra-detail">
		<h3>OAuth endpoints discovered</h3>
		<dl>
			<dt>Request Token Endpoint</dt>
			<dd><code><?php echo $_SESSION['site_auth_urls']->request ?></code></dd>
			<dt>Authorize Endpoint</dt>
			<dd><code><?php echo $_SESSION['site_auth_urls']->authorize ?></code></dd>
			<dt>Access Token Endpoint</dt>
			<dd><code><?php echo $_SESSION['site_auth_urls']->access ?></code></dd>
		</dl>
	</div>
</form>

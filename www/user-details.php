<?php

/** @var \League\OAuth1\Client\Server\User */
$user = $args['user'];

/** @var array */
$auth_urls = $_SESSION['site_auth_urls'];

/** @var \League\OAuth1\Client\Credentials\TokenCredentials */
$access_token = $args['tokenCredentials'];

?>
<h2>Step 3: Connected</h2>
<p>Connected to <code><?php echo htmlspecialchars( $_SESSION['site_base'] ) ?></code>.
	<a class="reset" href="?step=reset">Reset?</a></p>

<h3>User Details</h3>
<div class="user-card">
	<div class="avatar"><img src="<?php echo htmlspecialchars( $user->imageUrl ) ?>" /></div>

	<div class="details">
		<h4><?php echo htmlspecialchars( $user->name ) ?>
			(<code><?php echo htmlspecialchars( $user->nickname ) ?></code>)</h4>

		<p><a href="<?php echo htmlspecialchars( $user->urls['permalink'] ) ?>">View posts</a></p>

		<dl>
			<?php if ( $user->firstName ): ?>
				<dt>First Name</dt>
				<dd><?php echo htmlspecialchars( $user->firstName ) ?></dd>
			<?php endif ?>

			<?php if ( $user->lastName ): ?>
				<dt>Last Name</dt>
				<dd><?php echo htmlspecialchars( $user->lastName ) ?></dd>
			<?php endif ?>

			<dt>Email</dt>
			<dd><code><?php echo htmlspecialchars( $user->email ) ?></code></dd>

			<?php if ( $user->description ): ?>
				<dt>Description</dt>
				<dd><?php echo htmlspecialchars( $user->description ) ?></dd>
			<?php endif ?>
		</dl>
	</div>
</div>

<div class="extra-detail">
	<h3>OAuth endpoints</h3>
	<dl>
		<dt>Request Token Endpoint</dt>
		<dd><code><?php echo htmlspecialchars( $auth_urls->request ) ?></code></dd>
		<dt>Authorize Endpoint</dt>
		<dd><code><?php echo htmlspecialchars( $auth_urls->authorize ) ?></code></dd>
		<dt>Access Token Endpoint</dt>
		<dd><code><?php echo htmlspecialchars( $auth_urls->access ) ?></code></dd>
	</dl>

	<h3>OAuth credentials</h3>
	<dl>
		<dt>Client Key</dt>
		<dd><code><?php echo htmlspecialchars( $_SESSION['client_key'] ) ?></code></dd>
		<dt>Client Secret</dt>
		<dd><code><?php echo htmlspecialchars( $_SESSION['client_secret'] ) ?></code></dd>

		<dt>Access Token</dt>
		<dd><code><?php echo htmlspecialchars( $access_token->getIdentifier() ) ?></code></dd>
		<dt>Access Token Secret</dt>
		<dd><code><?php echo htmlspecialchars( $access_token->getSecret() ) ?></code></dd>
	</dl>
</div>


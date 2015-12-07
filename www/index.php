<?php

namespace WP_REST\ExampleClient\WebDemo;

use Exception;
use WordPress\Discovery;

require_once dirname( __DIR__ ) . '/vendor/autoload.php';
require_once __DIR__ . '/helpers.php';

// Start session
session_start();

// Handle CSS on local server.
if ( $_SERVER['REQUEST_URI'] === '/style.css' ) {
	header( 'Content-Type: text/css; charset=utf-8' );
	readfile( __DIR__ . '/style.css' );
	return;
}

// Work out where we are.
$here = get_requested_url();

// What should we show?
$step = isset($_GET['step']) ? $_GET['step'] : '';
switch ( $step ) {
	// Step 0: Pre-Discovery
	case '':
		return output_page( load_template( 'discovery-form' ) );

	// Step 1: Discovery
	case 'discover':
		if ( empty( $_GET['uri'] ) ) {
			return output_page( load_template( 'discovery-form' ) );
		}

		try {
			$site = Discovery\discover( $_GET['uri'] );
		}
		catch (Exception $e) {
			$error = sprintf( "Error while discovering: %s.", htmlspecialchars( $e->getMessage() ) );
			return output_page( load_template( 'discovery-form' ), 'Discover', $error );
		}
		if ( empty( $site ) ) {
			$error = sprintf( "Couldn't find the API at <code>%s</code>.", htmlspecialchars( $_GET['uri'] ) );
			return output_page( load_template( 'discovery-form' ), 'Discover', $error );
		}
		if ( ! $site->supportsAuthentication( 'oauth1' ) ) {
			$error = "Site doesn't appear to support OAuth 1.0a authentication.";
			return output_page( load_template( 'discovery-form' ), 'Discover', $error );
		}

		$_SESSION['site_base'] = $site->getIndexURL();
		$_SESSION['site_auth_urls'] = $site->getAuthenticationData( 'oauth1' );

		return output_page( load_template( 'credential-form' ) );

	// Step 2: Pre-Authorization
	case 'preauth':
		if ( empty( $_GET['client_key'] ) || empty( $_GET['client_secret']) ) {
			return output_page( load_template( 'discovery-form' ) );
		}

		$_SESSION['client_key'] = $_GET['client_key'];
		$_SESSION['client_secret'] = $_GET['client_secret'];

		$server = get_server();

		// First part of OAuth 1.0 authentication is retrieving temporary credentials.
		// These identify you as a client to the server.
		try {
			$temporaryCredentials = $server->getTemporaryCredentials();
		} catch ( Exception $e ) {
			$error = $e->getMessage();
			return output_page( load_template( 'credential-form' ), 'Discover', $error );
		}

		// Store the credentials in the session.
		$_SESSION['temporary_credentials'] = serialize($temporaryCredentials);
		session_write_close();

		// Second part of OAuth 1.0 authentication is to redirect the
		// resource owner to the login screen on the server.
		$server->authorize($temporaryCredentials);
		return;

	// Step 3: Upgrade Credentials
	case 'authorize':
		$server = get_server();

		// Retrieve the temporary credentials from step 2
		$temporaryCredentials = unserialize($_SESSION['temporary_credentials']);

		// Third and final part to OAuth 1.0 authentication is to retrieve token
		// credentials (formally known as access tokens in earlier OAuth 1.0
		// specs).
		$tokenCredentials = $server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);

		// Now, we'll store the token credentials and discard the temporary
		// ones - they're irrelevant at this stage.
		unset($_SESSION['temporary_credentials']);
		$_SESSION['token_credentials'] = serialize($tokenCredentials);
		session_write_close();

		// Redirect to the user page
		header("Location: {$here}?step=user-details");
		return;

	// Step 4: Retrieve details
	case 'user-details':
		$server = get_server();

		// Check somebody hasn't manually entered this URL in,
		// by checking that we have the token credentials in
		// the session.
		if ( ! isset($_SESSION['token_credentials'])) {
			echo 'No token credentials.';
			exit(1);
		}

		// Retrieve our token credentials. From here, it's play time!
		$tokenCredentials = unserialize($_SESSION['token_credentials']);

		// // Below is an example of retrieving the identifier & secret
		// // (formally known as access token key & secret in earlier
		// // OAuth 1.0 specs).
		// $identifier = $tokenCredentials->getIdentifier();
		// $secret = $tokenCredentials->getSecret();

		// Some OAuth clients try to act as an API wrapper for
		// the server and it's API. We don't. This is what you
		// get - the ability to access basic information. If
		// you want to get fancy, you should be grabbing a
		// package for interacting with the APIs, by using
		// the identifier & secret that this package was
		// designed to retrieve for you. But, for fun,
		// here's basic user information.
		$user = $server->getUserDetails($tokenCredentials);

		return output_page( load_template( 'user-details', compact( 'user', 'tokenCredentials' ) ) );

	// Reset session data
	case 'reset':
		session_destroy();

		// Redirect back to the start
		header("Location: {$here}");
		return;
}

# Example OAuth Client

This is an example client for the REST API, using OAuth 1.0a to talk to your WordPress site.

This is *also* the repository for a WordPress provider for the [OAuth 1.0a client library][league-oauth] by the League of Extraordinary Packages.

[league-oauth]: https://github.com/thephpleague/oauth1-client

## Setup (Server)

On your server, you need the latest [REST API plugin][] and [the OAuth server plugin][oauth] plugins installed.

[REST API plugin]: https://wordpress.org/plugins/rest-api/
[oauth]: https://github.com/WP-API/OAuth1

## Setup (Demo Client)

To run the demo client here, you need to grab this repo, then install the dependencies via Composer. You can then run the example client via PHP's built-in server:

```
# Clone down this repo
git clone https://github.com/WP-API/example-client
cd example-client

# Install dependencies
composer install

# Run the client on port 8080
php -S 0.0.0.0:8080 www/index.php
```

Then open up http://localhost:8080/ in your browser to get started.

## Usage

The demo client is split into four stages, mirroring how typical applications work (with the exception of the second step).

### Step 1: Discovery

The first step is finding the REST API. We start out here by asking the user (in this case, you) for the site address.

<img src="http://i.imgur.com/m03qws1.png" />

We use this URL to find the API by checking for a `Link` header with the link relation (`rel`) set to `https://api.w.org/`. We use [the existing library][discovery-php] for this to simplify the code.

Once we have the API URL, we request it, which gives us back the index for the site's API. We then check the index to make sure OAuth 1.0a is supported (by checking `authentication.oauth1`), and get the links from there. (These are displayed in the footer of step 2 to help debugging and developing.)

[discovery-php]: https://github.com/WP-API/discovery-php


### Step 2: Input Credentials

The next step is asking for your client credentials. Typically this won't be required, as your client credentials should be part of your application. (We're working on solutions to get this working across all WP sites on the internet with a single key/secret.)

<img src="http://i.imgur.com/COQZrDW.png" />

Plug these in and start the authorization process.

In the background, after clicking "Begin Authorization", we kick off the process by asking for temporary credentials (a request token). Once we have these, we then redirect to the authorization page on the site itself to authorize the credentials.


### Step 3: Authorization

You'll be redirected to the site itself to authorize the token. This is where we link the request token to the user's account, and authorize it for the final step.

<img src="http://i.imgur.com/E1uwSNw.png" />

Once the user clicks "authorize", the site will do a few things:

1. Links your request token to the user
2. Marks the token as authorized, allowing it to be upgraded to permanent credentials (access token)
3. Generates a verifier token to avoid CSRF
4. Finally, redirect back to your callback URL with the verifier token

The demo client will then complete the process by exchanging the temporary credentials (request token) for permanent credentials (access token). Congratulations, the client is now linked to the site!


### Step 4: Display User Details

Your newly gained credentials are only useful if you can use them for something! For this demo application, we display your user details, which should let you verify that we're getting the real details from the site.

<img src="http://i.imgur.com/K7B0cmi.png" />

You'll also see in the footer the OAuth credentials we're using, including the permanent credentials. Note that you shouldn't display these to a user in a real application, these are only shown for development and debugging purposes.

## License

This project is licensed under the MIT license. See [LICENSE.md](LICENSE.md) for the full license.

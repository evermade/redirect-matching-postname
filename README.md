# Redirect Matching Postname

Plugin to redirect 404s to the post with the same name as the requested URL. WordPress does this natively to some degree but not reliably.

## When to use this plugin?

* When changing permalink structure from `/%postname%/` to something else like `/%category%/%postname%/`
* When you cannot do redirects in other ways like using for example [Redirection plugin](https://wordpress.org/plugins/redirection/), NGINX configuration or custom PHP code.

## Installation

* Insert this directory into `/wp-content/plugins/` (you can also download it as zip file through WordPress Plugin view)
* Activate

Notice that this plugin won't save anything to your installation so you need to keep this plugin active to keep these redirections working.

## Customization

You can change what post types are searched by setting filter `em_redirect_matching_postname/post_types`.

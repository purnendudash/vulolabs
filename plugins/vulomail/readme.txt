=== VuloMail ===

Contributors: vulolabs
Tags: smtp, email, sms, email log, wp mail
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

One plugin. Email and SMS. One delivery control center.

== Description ==

VuloMail delivers your WordPress email through an email service you choose and sends text messages through an SMS provider you choose. It adds a backup connection for when the first one fails, a log of every message, SMS alerts for site and store events, and diagnostics that tell you what to fix.

It needs no VuloLabs account, no subscription and no other plugin. You connect providers you already have.

**Activating VuloMail changes nothing.** WordPress keeps sending email as before until you add a connection and set it as primary.

= Email =

* Delivers every email sent with WordPress's standard `wp_mail()` function: WordPress itself, WooCommerce, contact forms and other plugins. Nothing to change in those plugins.
* Providers: any SMTP server (your host, Google Workspace, Microsoft 365, Amazon SES, Zoho and others), SendGrid, Mailgun (US and EU), Brevo and Postmark.
* Primary and backup: if the primary connection fails, the backup is tried.
* Last resort: optionally hand the email to the WordPress default mailer when the primary and backup both fail, instead of dropping it.
* Sender email and sender name, with an option to use them even when a plugin sets its own.
* One switch turns email routing off and returns the site to WordPress's own behaviour, keeping your connections.

= SMS =

* Gateways: Twilio, Vonage, Plivo and Clickatell.
* Primary and backup gateways with the same failover as email.
* Phone numbers are accepted in international format, or in national format once you set a default country code.

= SMS alerts =

Eighteen optional text alerts, each off until you switch it on, each with a message you can rewrite using placeholders such as `{order_number}` and `{customer_name}`.

* Site alerts, sent to you: new user registered, administrator logged in, new comment, an email failed to send.
* Store alerts, sent to you (need WooCommerce): new order, payment failed, order cancelled, order refunded, product low in stock, out of stock, backordered, new product review.
* Customer alerts, sent to the order's billing phone (need WooCommerce): order confirmed, completed, refunded, cancelled, any other status change, and a note you add to the order.

A customer receives one text per order status change. The "email failed" alert is limited to one text every 15 minutes. Without WooCommerce the store and customer alerts are listed but locked.

= Delivery control center =

* Dashboard: sent and failed totals for 7, 30 or 90 days, what each channel is sending through, and the most recent failures.
* Logs: every email and text message with recipient, time, provider and status. Filter by channel, status and date range, search by recipient or subject, open an entry to see each delivery attempt and the provider's error, and resend it.
* Tools: send a test email or SMS through the live routing or through one specific connection.
* Diagnostics: checks email routing, what actually handles email on the site, recent delivery, the sender address, SPF and DMARC records, other email plugins, SMS routing, incomplete connections, the server and the log. A finding that needs action says what to do.
* Settings save by themselves as you change them. A search box finds any screen or setting.
* Dates follow the date format, time format and timezone set in WordPress.

= Privacy and security =

* Passwords and API keys are encrypted in the database and are never shown again or sent back to the browser.
* Message content is not stored unless you turn that on. Recipients can be masked in the log.
* Log entries are deleted automatically after a number of days you choose.
* Every screen and every request is limited to administrators.
* No tracking. VuloMail contacts no server other than the providers you connect.
* Deleting the plugin keeps your data unless you choose "Delete everything" first.

= For developers =

* `vulomail_send_email()` and `vulomail_send_sms()` send through the site's connections and return `true` or a `WP_Error`.
* `vulomail_is_email_ready()` and `vulomail_is_sms_ready()` report whether a connection is set up.
* Filters add your own email provider, SMS gateway, SMS alert or diagnostics check; actions fire when a message is sent or fails.
* Any plugin can hand its own email and SMS delivery to VuloMail this way, with no code in VuloMail that knows about that plugin.

The API is versioned (`vulomail_api_version()`). Full documentation is in `docs/developer/` in the plugin's source repository.

== External services ==

VuloMail only contacts a service after you add a connection for it and a message is sent through that connection. It then sends that message (recipients, sender, subject, body, attachments, or the phone number and text) and your credentials for that service.

* SendGrid, api.sendgrid.com - [Terms](https://www.twilio.com/en-us/legal/tos), [Privacy](https://www.twilio.com/en-us/legal/privacy)
* Mailgun, api.mailgun.net / api.eu.mailgun.net - [Terms](https://www.mailgun.com/legal/terms/), [Privacy](https://www.mailgun.com/legal/privacy-policy/)
* Brevo, api.brevo.com - [Terms](https://www.brevo.com/legal/termsofuse/), [Privacy](https://www.brevo.com/legal/privacypolicy/)
* Postmark, api.postmarkapp.com - [Terms](https://postmarkapp.com/terms-of-service), [Privacy](https://postmarkapp.com/privacy-policy)
* Twilio, api.twilio.com - [Terms](https://www.twilio.com/en-us/legal/tos), [Privacy](https://www.twilio.com/en-us/legal/privacy)
* Vonage, rest.nexmo.com - [Terms](https://www.vonage.com/legal/), [Privacy](https://www.vonage.com/legal/privacy-policy/)
* Plivo, api.plivo.com - [Terms](https://www.plivo.com/legal/tos/), [Privacy](https://www.plivo.com/legal/privacy/)
* Clickatell, platform.clickatell.com - [Terms](https://www.clickatell.com/legal/), [Privacy](https://www.clickatell.com/legal/privacy-notice/)
* Your own SMTP server, at the host you enter.

The Diagnostics screen looks up the public DNS TXT records (SPF and DMARC) of your sender domain through your server's DNS resolver. No data about your site is sent.

== Installation ==

1. Install and activate VuloMail.
2. Go to VuloMail → Settings. In the Email section click "Add email connection", choose your provider and enter its details.
3. Click "Set as primary" on the new connection. Until you do, WordPress keeps using its default mailer.
4. Open the Email sub-tab and set the sender email to an address your provider has verified.
5. Go to VuloMail → Tools and send a test email.

For text messages: add an SMS connection on the same Connections screen, set your admin phone number under Settings → SMS, then switch on the alerts you want under VuloMail → SMS Alerts.

== Frequently Asked Questions ==

= Does activating VuloMail change how my site sends email? =

No. Nothing changes until you add an email connection and set it as primary. You can switch routing off again at any time under Settings → Email without losing your connections.

= Which emails does it cover? =

Every email sent with WordPress's standard `wp_mail()` function, which is how WordPress, WooCommerce and most plugins send. A plugin that talks to an email service directly is not affected.

= What happens if my email provider is down? =

VuloMail tries the backup connection. If that also fails and "Fall back to the WordPress default mailer" is on, WordPress sends the message itself. The log shows the message once, with every attempt.

= The log says "Sent". Was the email delivered? =

"Sent" means your provider accepted the message. Whether it reached the inbox is reported in your provider's own dashboard; the log entry shows the provider's ID for the message so you can look it up.

= Why does my email go to spam? =

Usually the sending domain is missing SPF, DKIM or DMARC records, or the sender address is not on your own domain. Tools → Diagnostics checks SPF and DMARC. DKIM is set up with your email provider.

= Where are my passwords and API keys stored? =

In your WordPress database, encrypted with a key derived from your site's security keys and a random per-site value. If the security keys in wp-config.php change, saved credentials can no longer be read and must be entered again; the connection is marked "Incomplete" and Diagnostics reports it.

= Does the log store the content of my emails? =

Not by default. Turn on "Store message content" under Settings → Logging & Privacy if you want to read or resend messages.

= Do the SMS alerts cost money? =

VuloMail is free. Your SMS provider charges you for each text message, which is why every alert is off until you switch it on.

= Can I text my customers? =

Yes, with WooCommerce: order confirmed, completed, refunded, cancelled, other status changes and order notes. Only switch these on if your customers have agreed to receive text messages. You are responsible for the rules that apply in your country.

= I already use another SMTP plugin. Can I run both? =

Use one. Two plugins delivering email compete for the same messages, and Diagnostics warns when it finds another. Add the same account to VuloMail, set it as primary, send a test, then deactivate the other plugin.

= What is removed when I delete the plugin? =

Nothing, unless you first choose "Delete everything" under Settings → Data. Deactivating never deletes anything.

== Changelog ==

= 1.0.0 =
* Initial release.

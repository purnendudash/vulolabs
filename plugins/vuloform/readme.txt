=== VuloForm ===

Contributors: vulolabs
Tags: form builder, contact form, forms, drag and drop, conditional logic
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Every form your website needs. One simple builder.

== Description ==

VuloForm is a drag-and-drop form builder for WordPress. Build a form, publish it, add it to a page with a block or a shortcode, and read what people send, all inside your own site.

It needs no VuloLabs account, no subscription and no other plugin. There is no limit on the number of forms or submissions.

= The builder =

* Three panes: field types on the left, your form in the middle, the selected field's settings on the right.
* Drag fields in, drag to reorder, or use the buttons and keyboard instead.
* Duplicate, delete, undo and redo.
* Preview the real form before you publish. Nothing entered in the preview is sent.
* Ten ready-made forms to start from: contact, general inquiry, lead generation, newsletter signup, feedback, survey, event registration, job application, support request, and order or quote inquiry.
* Export a form to a file and import it on another site.

= Fields =

Short text, long text, email, phone, number, website, dropdown, single choice, multiple choice, consent, date, time, file upload, name, address, hidden field, calculation, section heading, text/HTML, divider and page break.

= Conditional logic =

Show, hide or require a field depending on other answers, with "all" or "any" matching. The rules run in the browser as the visitor types and are enforced again on your server.

= Multi-step forms =

Add a page break to split a form into steps, with Next and Back buttons and a step indicator. Each step is validated before the visitor moves on.

= Calculations =

A calculation field works out a number from other answers, for example quantity times price, and shows it live. The result is recomputed on your server.

= Submissions =

* Every submission is stored on your site, unless you switch that off for a form.
* Search, filter by status and date, mark as read, new or spam.
* Export to CSV.
* Delete single submissions, with their uploaded files.
* Optional automatic deletion after a number of days.

= Notifications =

* Email notifications to you or your team, with placeholders for any answer.
* A confirmation email to the person who filled in the form.
* Text message (SMS) notifications when the separate VuloMail plugin is installed with an SMS connection. Without it, SMS notifications are skipped.

Email is sent through WordPress's own `wp_mail()`, or through VuloMail when that plugin is active.

= Webhooks =

Send each submission as JSON to another service over HTTPS. Requests are sent in the background, retried twice on failure, can be signed with a secret, and every attempt is recorded on the submission.

= Embed on another website =

Copy a small snippet from the form's Share tab and paste it into any web page, including sites that do not run WordPress. The form loads from your WordPress site and submissions arrive there.

= File uploads =

Choose the allowed file types, size and number of files per field. Only a fixed list of safe file types can be allowed, content is checked against the file type, and files are stored under random names in a protected folder. Administrators download them from the submission.

= Spam protection =

A hidden trap field, a minimum fill time and a per-visitor rate limit protect every form. No CAPTCHA, and no visitor data is sent to another company.

= Design =

Forms follow your theme. Per form you can set label position, spacing, accent colour, text colour, text size, corner roundness and button alignment, and add your own CSS class.

= Accessibility =

Forms are plain HTML with labels on every control, grouped controls in fieldsets, errors linked to their fields and announced to screen readers, and full keyboard use. The public form does not use React or jQuery, and works without JavaScript on your own site.

= Privacy =

* No telemetry and no connection to VuloLabs.
* Visitor IP addresses are not stored unless you switch that on.
* Works with WordPress's Export Personal Data and Erase Personal Data tools.
* Adds suggested text to the WordPress privacy policy guide.

VuloForm gives you these tools. It does not by itself make a site compliant with GDPR or any other law.

= For developers =

Actions and filters for validation, spam checks, stored values, notification emails, webhook payloads, field types, templates and field rendering. Functions `vuloform_render()`, `vuloform_get_form()` and `vuloform_get_submission()`. See the `docs/developer` folder in the plugin's source repository.

== External services ==

VuloForm does not contact any external service on its own.

It sends data to other servers only when an administrator sets that up:

* **Webhooks.** When you add a webhook to a form, each submission to that form (the answers, form title, submission number and time) is sent to the URL you entered. What that service does with the data is governed by its own terms and privacy policy.
* **Email and SMS notifications.** These are handed to your site's mail system, or to the VuloMail plugin if you use it. Any external email or SMS provider involved is one you configured there.

== Installation ==

1. Install VuloForm from Plugins > Add New, or upload the zip file there.
2. Activate it.
3. Open VuloForm in the WordPress menu and select New form.
4. Publish the form, then add it to a page with the VuloForm block or the shortcode shown on the form's Share tab.

== Frequently Asked Questions ==

= Is there a limit on forms or submissions? =

No.

= Do I need another plugin? =

No. VuloForm works by itself. VuloMail is optional: it makes email delivery more reliable and is required for SMS notifications.

= How do I add a form to a page? =

In the block editor, add the VuloForm block and choose the form. Anywhere else, paste the shortcode from the form's Share tab, for example `[vuloform id="12"]`.

= Can I put a form on a site that is not WordPress? =

Yes. Copy the snippet under "On another website" on the form's Share tab and paste it into that page's HTML. Your WordPress site must stay online, and the form needs JavaScript on the other site.

= My notification emails do not arrive. Why? =

Open the submission. If the notification says "Handed off", VuloForm passed the email to your site's mail system and the problem is delivery: check the spam folder and consider an SMTP plugin such as VuloMail. If it says "Skipped", the recipient address is missing or invalid.

= Does "Handed off" mean the email was delivered? =

No. It means your site's mail system accepted it. VuloForm cannot see whether it reached the inbox.

= Is there a CAPTCHA? =

No. Forms are protected by a hidden trap field, a minimum fill time and a rate limit. Developers can connect another anti-spam service with the `vuloform_spam_check` filter.

= Are uploaded files public? =

They are stored under long random names in a folder that blocks direct access on Apache and LiteSpeed servers, and administrators download them through WordPress. On nginx, ask your host to block access to `wp-content/uploads/vuloform/`.

= Can conditional logic be bypassed? =

The rules are checked again on your server. A field that is hidden by its rules is never stored, and a required field cannot be skipped by turning off JavaScript.

= What is removed when I delete the plugin? =

Nothing, by default, so a reinstall picks up where you left off. To remove forms, submissions and uploaded files, choose "Delete everything" under VuloForm > Settings before deleting the plugin.

== Changelog ==

= 1.0.0 =
* First release.

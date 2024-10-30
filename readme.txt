=== Plugin Name ===
Contributors: chousmith
Tags: contact, form, contact form, wordcount, word, count
Requires at least: 4.3
Tested up to: 4.4
Stable tag: 1.1.1

Adds an option to Contact Form 7's Textarea field for a Max Wordcount, and limits input to max wordcount on the front end.

== Description ==

**NOTE : Due to a lack of time, the latest version of this plugin supports the latest version 4.4 of Contact Form 7, which itself has a minimum requirement of WordPress 4.3. The 1.0.2 version of this plugin only works with older versions of Contact Form 7. It has been verified as working with versions 3.0 - 3.3.3, but with the CF7 update starting at their version 3.4 up through 4.2, this particular plugin may not work.**

The Contact Form 7 Textarea Wordcount plugin inserts some additional functionality into the "Text area" field from the Contact Form 7 plugin. Enabling this plugin adds an additional field when creating a new Text area, where you can specifiy a "Max Wordcount" for that form field. When such a max wordcount is given, extra html/js is inserted on the front end view of your site's contact form(s), which shows the current word count for that textarea, and cuts off the text inside the field after the given number of words.

For more information, check out [http://www.ninthlink.com/2010/12/30/contact-form-7-textarea-wordcount/](http://www.ninthlink.com/2010/12/30/contact-form-7-textarea-wordcount/).

== Installation ==

1. Upload the entire `contact-form-7-textarea-wordcount` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress

This will add the extra field to the "Generate Tag: Text area" screen on the Contact Form editor.

== Frequently Asked Questions ==

= Does this plugin do anything if the Contact Form 7 plugin is not used? =

No, this plugin only adds some more functionality to Contact Form 7, and does nothing if that plugin is not installed/used.

= Where can I post comments/questions/suggestions? =

All comments/questions/suggestions/praise/criticisms should go to [http://www.ninthlink.com/2010/12/30/contact-form-7-textarea-wordcount/](http://www.ninthlink.com/2010/12/30/contact-form-7-textarea-wordcount/) . Thanks.

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png

== Changelog ==

= 1.1.1 =

* updated plugin Description

= 1.1 =

* 2 years later, update this plugin to work with the latest v4.4 version of CF7 rather than only working with CF7 v3.0 - v3.3.3
* Tweaked Description wording
* Updated minimum WordPress requirements to go along with CF7 v4.4 requirements
* Removed the closing php tag at the bottom of the plugin
* Removed the question mark on the last changelog bullet of v1.0.2?

= 1.0.2 =
* updated README to reflect testing up to WP 3.9 with CF7 up to 3.3.3. Working on support for more recent CF7 changes
* fixed PHP WSOD errors in the even that this plugin is activated before Contact Form 7 itself is activated
* moved screenshots into assets folder

= 1.0.1 =
* Verified conditional so Word Count on the front end only appears beneath textareas that have "max wordcount" specified

= 1.0 =
* Initial release of the plugin

== Upgrade Notice ==

= 1.0 =
Initial release of the plugin

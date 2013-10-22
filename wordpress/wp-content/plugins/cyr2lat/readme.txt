=== Cyr-To-Lat ===
Contributors: Atrax, SergeyBiryukov
Tags: l10n, translations, transliteration, slugs, russian, rustolat
Requires at least: 2.3
Tested up to: 3.0.1
Stable tag: 3.2

Converts Cyrillic characters in post and term slugs to Latin characters.

== Description ==

Converts Cyrillic characters in post and term slugs to Latin characters. Useful for creating human-readable URLs.

Based on the original Rus-To-Lat plugin by Anton Skorobogatov.

== Installation ==

1. Upload `cyr2lat` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 3.2 =
* Added transliteration when publishing via XML-RPC
* Fixed Invalid Taxonomy error when viewing the most used tags

= 3.1 =
* Fixed transliteration when saving a draft

= 3.0 =
* Added automatic conversion of existing post, page and term slugs
* Added saving of existing post and page permalinks integrity
* Added transliteration of attachment file names
* Adjusted transliteration table in accordance with ISO 9 standard
* Included Russian, Belarusian, Ukrainian, Bulgarian and Macedonian characters
* Added filter for the transliteration table

= 2.1 =
* Optimized filter call

= 2.0 =
* Added check for existing terms

= 1.0.1 =
* Updated description

= 1.0 =
* Initial release

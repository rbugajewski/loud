# Loud

With *Loud* you easily add audio files (M4A or MP3) from a web site to a feed for listening later. You can keep it private and subscribe to it in your favorite podcast app.

## Installation
*Loud* is written in PHP and should run on almost every server. cURL support for the PHP installation is required. There are no other dependencies.

1. Copy the whole folder to the web server.
2. Change permissions of `urls.yaml` to be either writable by the web-server or world-writable (`chmod 666 urls.yaml`).
3. Visit the just copied folder and drag the bookmarklet to your bookmarks bar.
4. Subscribe to the feed via one of the links on that page.

## Usage

When you visit a web page that has either a link to a M4A or MP3 file you just click on the bookmarklet. *Loud* will parse the visited site and use the first found M4A or MP3 file (in that order) as the media enclosure for the feed.

You can select some text before clicking on the bookmarklet and your selection will be used as the description for the feed item.

*Loud* will detect duplicates and won’t add them to your feed. After the file was successfully added to your feed, you’ll be redirected back to the referring web site.

## Gotchas

There is no error handling. This means that:

1. You’ll have to use the back button of your browser when:
   1. there is no supported media file on the web site, or
   2. the item is already in your feed.
2. When there are links that don’t end with `.m4a` or `.mp3` they won’t be detected (e.g. NPR is not supported).

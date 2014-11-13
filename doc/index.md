# Documentation

In order to ease the writing and presentation of BTEC IT assignment work, I decided to create this platform. It's essentially an over-complicated markdown processor, but that doesn't sound complex enough for my liking.

## File Hierarchy

Below the key directories and files in the repository are listed, and their contents explained.

* **css** &ndash; the lone stylesheet I've created, and any others I add in the future

* **doc** &ndash; the documentation you're presumably reading now

* **file** &ndash; attachments I have added to some assignments, such as spreadsheets, archives and project files

* **img** &ndash; images either created by myself or not hosted on their source domains

* **include** &ndash; php include scripts (currently just `parsedown.php`)

* **markdown** &ndash; all assignment markdown documents

    * **ext** &ndash; extra markdown for particular assignments, often included in the main assignment document (i.e., pointlessness)

* **index.php** &ndash; the magic script, that handles almost all requests (certainly any markdown pages)

* **README.md** &ndash; the repository read-me

* **LICENSE.md** &ndash; the repository license file

## Webserver Configuration

In order for everything to run smoothly when mirroring the repository and hosting it, a few webserver URI rewrites need to be added. I have a phobia of Apache, and have abandoned LightTPD for Nginx, and shall therefore only offer actual configuration for the latter-most.

### As English

The directory of the repository... I have yet to write this.

### As Nginx Configuration

Adding this to a file in `/etc/nginx/sites-available/` and creating a symlink to said file in `/etc/nginx/sites-enabled`, before reloading with `sudo /etc/init.d/nginx reload` should do the trick.

<!--[INCLUDE] doc/localhost.conf -->

Make sure to change the parts between `<>` in order to match your set-up. The directory specified as `root` should have a descendent directory called `btec` in it, in turn containing this repository. The above configuration also relies on a `php-fpm` server running and listening on the local port 9000 (or the specified Unix socket).

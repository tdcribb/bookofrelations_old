=== rootsPress ===
Contributors: Mike Warland
Donate link: http://www.kiva.org/
Tags: family history, genealogy, gedcom, pedigree
Requires at least: 3.3.0
Tested up to: 3.4.1
Stable tag: 2.6.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays information from a Gedcom file in a simple to use format including:
events and facts and links to parents, spouse(s) and children.

== Description ==

rootsPress interprets a Gedcom file and creates pages to display information in a simple to use format.
It displays each persons details along with links to parents, spouse or spouses and children. Multiple marriages are supported.
Events and facts associated with the individual are shown with thumbnails of supporting media.
Source citations are also included for each event.

Features:

* builds a database from a Gedcom file (5.5)
* displays data for each individual with a portrait if available
* displays parents with links to their pages
* displays each family (spouse and children) for every recorded marriage
* displays all events and facts along with image thumbnails and sources for those events.
* clicking on thumbnails shows a full size image using Slimbox2 (Lightbox clone)
* displays a map of all events which have place coordinates using Google Maps V3
* displays photos in the area of an event using the *geograph* (UK only) or *Panoramio* services
* includes an index page to enable finding and navigating to individuals
* displays a scrollable/pannable interactive family tree showing ancestors and descendants of the individual selected as key person
* Supports multiple separate family trees from different gedcom files (subject to space limitations)
* The default (first displayed) individual defaults to the first entry in the database. The administrator can change this.
* Trees can be restricted by the administrator so that only logged on users can see details.
* Supports languages through Wordpress localization

Licenses:

* rootsPress is made available under the GNU General Public License, version 2, as
    published by the Free Software Foundation.
* Google Maps: [http://code.google.com/apis/maps/terms.html] (http://code.google.com/apis/maps/terms.html "Google Maps Terms of Service")
* Geograph: [http://www.geograph.org.uk/help/terms] (http://www.geograph.org.uk/help/terms)
* Panoramio: [http://www.panoramio.com/terms/] (http://www.panoramio.com/terms/ "Panoramio Terms of Service")
* Slimbox2 licensed under the MIT license

== Installation ==
New:

1. Upload the rootsPress folder to the `/wp-content/plugins/` directory
2. Optional: If you are NOT using the Wordpress database to hold rootsPress tables, copy the sample db_config file  and rename it db_config.php. Edit to match the correct database definition.
3. Activate the plugin through the 'Plugins' menu in WordPress. 
   Note: if you receive an error saying rootsPress could not connect to the database (showing database name, user and server), it means you are trying to use a non Wordpress database and the values are incorrect. This must be checked and corrected before proceeding.
4. Set site options using the admin panel
5. Add trees using the admin panel
6. Customize tree options such as media file paths
7. Upload images, documents etc. referenced within the GEDCOM file to the location of your choice. Do not put the data in the rootsPress path otherwise it will be deleted in any future upgrade. 

Upgrades:

1. Deactivate the old version of rootsPress. Do not uninstall data unless you no longer need the existing trees.
2. Optional: if you defined your database outside Wordpress save a copy of db_config.php for re-installation (step 4).
3. Either use the automatic upgrade or manually upgrade by deleting the entire rootsPress plugin folder and replacing with the new version.
4. Re-install and optionally copy db_config.php back to the plugin folder(see step 2).
5. Activate.
6. Use the Upgrade menu option in rootsPress if required.

For more details on installation and plugin usage, see www.mikewarland.com/tekhus

== Frequently Asked Questions ==

= Can I make some people, eg those still living not displayed?  =

Privacy at an individual level is planned for a future release. Most Family History software
permits the creation of gedcom data with, for example, all people deemed living marked private.
You could also restrict the display of rootsPress pages using Wordpress permissions.

= Why do some messages still show in english in my site? =

If the rest of the product is showing translated messages correctly, this is probably because you are running as localhost on Windows. Try uploading to your server.
If the problem persists, please contact support.

= Why are no event maps displayed in my tree? =

It's likely that the events for those people do not have geographic coordinates included and therefore rootsPress has no way of mapping them.
Place coordinates are a feature included with gedcom release 5.5.1. Unless your originating software
supports this you will not be able to display event maps. However rootsPress does have support
for the custom place data that Roots Magic produces.

= Where should I put my family tree data? =

The data that was referenced in your gedcom file (document images, photos etc) will be used by rootsPress (subject to the settings in the Tree Options panel).
You can put this anywhere that is accessible to your Wordpress site. Including it under the Wordpress directory is not recommended because later changes to Wordpress might affect the data.

= Should I put rootsPress in the Wordpress database? =

You can safely install rootsPress tables within the Wordpress structure and this is the default install option. However you can also install it in a separate predefined mysql database if preferred.
You can change from one database to another by de-activating and re-activating the rootsPress plugin. This is not recommended unless you remove the trees from one of the databases completely.
Note that if you have trees from more than one database in your Wordpress site, only those in the current database will be accessible even though the pages may still be visible.

= Are there limitations on the number of individuals a tree may contain? =

There are practical limitations on storage use as with any application. rootsPress has been tested
with over 1500 individuals in a tree with no problems. Note that it may take signicant time to load a large gedcom file.
If you exceed the time limit for the load process, rootsPress may fail due to site limitations.

= Can I use an image handler plugin (like Lightbox) with rootsPress? =

rootsPress includes Slimbox2 code to handle it's own images using the API. If you are using a plugin (eg LightBox Plus) you may find that rootsPress images are expanded twice. To resolve this, set the lightbox Site Option to disabled which still conditions images but does not include the scripts.

= rootsPress did not complete the gedcom file upload =

It's possible that you server is operating in safe mode and the script got cancelled for exceeding the maximum execution time.
Please report this by way of the support site. Unless you can increase the allowed execution time or decrease the gedcom file size, you may not be able to use rootsPress in your situation.

= How can I change the Interactive Tree theme to match my Wordpress theme. =

Since the Interactive Tree layout is highly dependent on not changing CSS you should not change the layout via CSS.
However you can change the color scheme of the chart, the boxes and the lines within the chart through the Admin Tree Options panel.
This includes a preview to allow you to see what your changes will look like.

= I have a problem with rootsPress, how do I get it resolved? =

Please log your problem in the rootsPress forum at:
[www.mikewarland.com/tekhus](www.mikewarland.com/tekhus "rootsPress support") 

== Screenshots ==

1. Personal details including a portrait
2. Family group
3. Events with images and source popup
4. Event map
5. Interactive family tree of ancestors and descendants

== Changelog ==

= 2.6.4 =

* Corrected errors occurring when using php 5.4
* added an option to use a gedcom file uploaded by tools such as ftp

= 2.6.2 =

* Added localization support. Two pot files are included.
* Repackaged text files to support localization
* Changed help functions to use Tinybox script
* Changed mysql functions to improve error handling
* Added an option to define Portraits as always silhouettes, a separate file or from the gedcom file.
* Fixed situation where the database would be updated even with errors in the add process

= 2.6.0 =

* Added the ability to replicate existing tree options such as the home page, media paths when adding a new tree. This could be used to 'replace' an existing tree from a new gedcom file.
* The home page no longer requires editing a separate Wordpress page. You now set up or change it as part of the <i>Tree Options</i> using the Wordpress editor.
* Removed the requirement for a database configuration file (except when using a database outside of Wordpress)
* Removed file access functions which caused problems with some server security setups.
* Added the ability to change the Interactive Tree color theme through the Admin panel.

= 2.5.4 =

* Gedcom load process rewritten to decrease load time
* Added progress indication during tree adds with cancellation option
* Removed dependencies on functions not available in php safe mode

= 2.5.2 =

* Changed gedcom load process to avoid timeout problems
* Changed Interactive Tree to fix problems with Internet Explorer
* Fixed line formatting problems in Interactive Tree

= 2.5.0 =

* Verified up to Wordpress 3.3.1
* Removal of some inline style settings to allow inheritance of theme styles
* Interactive tree reformatted to show spouses separately and also improve the handling of multiple marriages
* Interactive tree will recenter on the key person when changed and also allow recentering on demand. The key person is highlighted.
* Site options added to allow either the Home page, Fact page, Index or Interactive tree to be set as default.
* Site options added to allow changes in the size of the Interactive tree and the Google Event Map.
* With the changes to the Interactive Tree and various problems, the three level ancestor tree on the fact page has been replaced by quick links to three levels of ancestor.

= 2.0.0 =

* Changed the internal navigation to use a single wordpress page for each display mode
* Added navigation links to home, index, fact pages and the new interactive tree
* Added an interactive tree to display ancestor and descendants for each selected individual
* Added a security option to restrict trees to logged on users.

= 1.6.2 =

* Fixed errors with interpreting an Apple Mac gedcom file
* Fixed errors in internal linkages when the Permalink Settings are not default

= 1.6.0 =

* Enhanced functions to support multiple entry gedcom fields (eg notes)
* Changes to the internal functions to eliminate php index errors. 
* Now using larger map icons for visibility.
* Added function to show geograph photos of an event vicinity
* Added function to show Panoramio photos of an event vicinity

= 1.5.4 =
* Changed the file upload process to allow direct file selection from your desktop.
* Changed the database configuration so that rootsPress uses the Wordpress database by default.
* Added an option to allow use of the gedcom file to define individuals portraits
* Added a specific uninstall option so that deactivation does not delete files.

= 1.5.2 =
* Fixed problem where map is not initialized after plugin activation
* Added directory rootspress_data to contain tree data (eg images)
* Limited the map zoomin to 9 on intitial load so that maps with one or two markers are still viewable.
* Changed the gedcom upload process to allow file selection from the client and avoid having to upload files separately.
* Removed surplus images in the repository
* Added tree name to persons title line

= 1.5.0 =
* First general release.

== Upgrade notice ==

Upgrading to this version will give you an Interactive tree of ancestors and descendants, the ability to restrict trees to logged on users and navigation between fact pages, index, home page and interactive tree.

== Upgrading from versions before 2.0.0 ==
* Do NOT use the automatic plugin upgrade feature of Wordpress, it will not activate upgrade code.
* Deactivate the earlier version, do NOT uninstall the data. Delete the old rootsPress code files.
* Copy this version to your plugin file  and reactivate.
* Use the Upgrade menu option. 

== Gedcom support ==
rootsPress targets support of all Gedcom 5.5.1 records and tags except those specifically for the LDS submissions. Gedcom allows multiple field entries (eg notes, sources, multimedia) and although these rarely occur, they are supported. rootsPress recognizes place name extensions from RootsMagic 4 and builds the coordinates in it's database. The repository information is recorded but not currently displayed. Addresses (ADDR tag) are not currently supported.

== Appearance ==
rootsPress should be used with a page template that has no sidebars to allow sufficient space for display.

rootsPress has been tested with a (small) number of themes to see how it integrates. If you encounter appearance issues, please report them.
You can change the appearance of the Interactive tree by changing values in file config.php and class person_box in the file rootspress/pgv/css/pgvstyle.css.
Changing any other values may break the tree layout entirely.

== Image display ==
Slimbox2 code is included with rootsPress to enable expanded display of thumbnail images and groups of images as a slide show.

If you wish to use a Wordpress plugin that supplies the same functionality (eg a Lightbox clone) you should disable the code selection in the site options. You may see differences in the way images are grouped.


== Localization/Translation ==
rootsPress supports localization of all text except internal program errors.
Two language files/domains are required and the two pot files are included with rootsPress in the localization folder.
A sample language file (en_CA) has been included.

Language translation files are contained in the localization folder and contain translations for languages other than US English (the default).
Two sets of files are required with unfortunately different file structures since some of the code is outside the Wordpress loop.
The images that Slimbox uses for navigation are available in different languages and contained in the slimbox subfolder. These images have been sourced from the WP-Slimbox2 plugin and credit should be given to all involved.

Main localization
The pot file rpress_main.pot is located in localization/main.
You should create the localization files for the language you use in this folder named according to Wordpress rules.  For example, for English Canadian the code would be en_CA and the file would be named rpress_main-en_CA.mo.

External to  Wordpress localization
The file structure used for this follows php gettext rules. 
The folder localization/ext will contain sub-folders for each language localized, for example folder en_CA.
Within these sub-folders, create a folder named LC_MESSAGES and within this folder placed the localization files for your language as required by gettext.
The localization files will always be named rpress_ext.mo and rpress_ext.po
For example, for English Canadian you would have:
localization/ext/en_CA/LC_MESSAGES/rpress_ext.mo

Sample localization files, not real language, with a locale of en_CA have been set up to show the file structure supplied and also to provide a test base for localization.
The pot files are located in localization/pot folder

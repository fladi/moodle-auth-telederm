# moodle-auth-telederm

A custom Moodle 2.x authentication plugin for telederm.org.

## Requirements
* Moodle (>= 2.4)
* php-curl

## Installation

The recommended way to install this plugin is through `git submodules`. In the
root of your moodle code tree run the following:

    git submodule add git://github.com/fladi/moodle-auth-telederm.git auth/telederm
    git commit -m "Add telederm authentication plugin."

See [Chapter 6.6 Git Tools -
Submodules](http://git-scm.com/book/en/Git-Tools-Submodules) for further
information on how to handle submodules.

## Configuration

Once the plguin has been installed, you should log in to your Moodle
installation as an administrator to trigger an update of the translations.

You can enable the telederm plugin through "Site administration" -> "Plugins" ->
"Authentication" -> "Manage authentication" where you can find the plugin with
the name "TeleDerm authentication".

There are several settings to be considered before the plugin can be used.

## Credits
Created by DI Michael Fladischer <michael.fladischer@medunigraz.at>, licensed
under BSD license.

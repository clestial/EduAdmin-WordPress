#!/usr/bin/env bash

if [[ -z "$TRAVIS" ]]; then
	echo "Script is only to be run by Travis CI" 1>&2
	exit 1
fi

if [[ -z "$WP_PASSWORD" ]]; then
	echo "WordPress.org password not set" 1>&2
	exit 1
fi

if [[ -z "$TRAVIS_BRANCH" || "$TRAVIS_BRANCH" == "production" ]]; then
	echo "Build branch is required and must not be a production" 1>&2
	exit 0
fi


PLUGIN="eduadmin-booking"
PROJECT_ROOT=$TRAVIS_BUILD_DIR
VERSION="$(cat $PROJECT_ROOT/eduadmin.php | grep Version: | head -1 | cut -d: -f2 | tr -d '[[:space:]]')"

echo "Version: $VERSION of $PLUGIN"

# Remove files not needed in plugin for deployment
rm -f $PROJECT_ROOT/composer.json
rm -f $PROJECT_ROOT/.scrutinizer.yml
rm -f $PROJECT_ROOT/.travis.yml
rm -f $PROJECT_ROOT/CONTRIBUTING.md
rm -f $PROJECT_ROOT/LICENSE.md
rm -f $PROJECT_ROOT/phpunit.xml
rm -f $PROJECT_ROOT/README.md
rm -f $PROJECT_ROOT/.gitignore
rm -fR $PROJECT_ROOT/scripts
rm -fR $PROJECT_ROOT/tests
rm -fR $PROJECT_ROOT/.git
rm -fR $PROJECT_ROOT/wp-tests
rm -fR $PROJECT_ROOT/vendor
rm -fR $PROJECT_ROOT/bin
rm -fR $PROJECT_ROOT/node_modules

# Make sure we are in the project root
cd $PROJECT_ROOT

# Go up one folder
cd ..

# Delete and recreate the deployFolder
rm -fR deployFolder
mkdir deployFolder

# Go into the deployFolder
cd deployFolder

# Clean up any previous svn dir
rm -fR svn

# Checkout the SVN repo
svn co -q "http://svn.wp-plugins.org/$PLUGIN" svn

# Copy our new version of the plugin into trunk
rsync -r -p -v --delete-before $PROJECT_ROOT/* svn/trunk

svn stat svn | grep '^?' | awk '{print $2}' | xargs -I x svn add x@
# Remove deleted files from SVN
svn stat svn | grep '^!' | awk '{print $2}' | xargs -I x svn rm --force x@
svn stat svn

# Commit to SVN
svn ci --no-auth-cache --username $WP_USERNAME --password $WP_PASSWORD svn -m "Committing changes for $VERSION"

# Remove SVN temp dir
rm -fR svn
#!/usr/bin/env bash

if [[ -z "$TRAVIS" ]]; then
	echo "Script is only to be run by Travis CI" 1>&2
	exit 1
fi

if [[ -z "$WP_PASSWORD" ]]; then
	echo "WordPress.org password not set" 1>&2
	exit 1
fi

if [[ -z "$TRAVIS_BRANCH" || "$TRAVIS_BRANCH" != "production" ]]; then
	echo "Build branch is required and must be a release-tag" 1>&2
	exit 0
fi


PLUGIN="eduadmin-booking"
PROJECT_ROOT=$TRAVIS_BUILD_DIR
VERSION="$(cat $PROJECT_ROOT/eduadmin.php | grep Version: | head -1 | cut -d: -f2 | tr -d '[[:space:]]')"

echo "Version: $VERSION of $PLUGIN"

# Check if the tag exists for the version we are building
TAG=$(svn ls "https://plugins.svn.wordpress.org/$PLUGIN/tags/$VERSION")
error=$?
if [ $error == 0 ]; then
    # Tag exists, don't deploy
    echo "Tag already exists for version $VERSION, aborting deployment"
    exit 1
fi

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

# Move out the trunk directory to a temp location
mv svn/trunk ./svn-trunk
# Create trunk directory
mkdir svn/trunk
# Copy our new version of the plugin into trunk
rsync -r -p $PROJECT_ROOT/* svn/trunk

# Copy all the .svn folders from the checked out copy of trunk to the new trunk.
# This is necessary as the Travis container runs Subversion 1.6 which has .svn dirs in every sub dir
cd svn/trunk/
TARGET=$(pwd)
cd ../../svn-trunk/

# Find all .svn dirs in sub dirs
SVN_DIRS=`find . -type d -iname .svn`

for SVN_DIR in $SVN_DIRS; do
    SOURCE_DIR=${SVN_DIR/.}
    TARGET_DIR=$TARGET${SOURCE_DIR/.svn}
    TARGET_SVN_DIR=$TARGET${SVN_DIR/.}
    if [ -d "$TARGET_DIR" ]; then
        # Copy the .svn directory to trunk dir
        cp -r $SVN_DIR $TARGET_SVN_DIR
    fi
done

# Back to builds dir
cd ../

# Remove checked out dir
rm -fR svn-trunk

# Add new version tag
mkdir svn/tags/$VERSION
rsync -r -p $PROJECT_ROOT/* svn/tags/$VERSION

# Add new files to SVN
svn stat svn | grep '^?' | awk '{print $2}' | xargs -I x svn add x@
# Remove deleted files from SVN
svn stat svn | grep '^!' | awk '{print $2}' | xargs -I x svn rm --force x@
svn stat svn

# Commit to SVN
#svn ci --no-auth-cache --username $WP_USERNAME --password $WP_PASSWORD svn -m "Deploy version $VERSION"

# Remove SVN temp dir
rm -fR svn

curl -X POST \
-H 'Content-type: application/json' \
--data '{"username": "Travis CI","channel":"#wordpress-eduadmin", icon_url": "https://a.slack-edge.com/0180/img/services/travis_48.png","text": "EduAdmin Booking plugin version '"$VERSION"' deployed to <https://sv.wordpress.org/plugins/eduadmin-booking/|wp.org> :tada:"}' \
$SLACK_HOOKURL
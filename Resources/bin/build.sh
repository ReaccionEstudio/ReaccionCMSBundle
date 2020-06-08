#!/usr/bin/env bash

################
# Path checker #
################

if [ -z "$1" ]; then
    echo "Error, enter Symfony path as parameter!"
fi

if [ ! -f "$1/config/bundles.php" ]; then
    echo "Error, Symfony path '$1' was nos found!"
    exit
fi

SF_PATH=$1
REACCION_TEMPLATES_PATH=${SF_PATH}/vendor/reaccionestudio/reaccion-cms-bundle/Resources/views
BUNDLES_TEMPLATES_PATH=${SF_PATH}/templates

# install assets
echo "Installing assets ..."
php ${SF_PATH}/bin/console assets:install --symlink

# Generate fos js routing file
php ${SF_PATH}/bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

if [ "$2" ]; then

	# Run Webpack encore
	echo "Compiling assets ..."
	node_modules/@symfony/webpack-encore/bin/encore.js $2 --progress

fi

# Clear cache
php ${SF_PATH}/bin/console cache:clear

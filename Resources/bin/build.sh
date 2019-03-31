# PATHS
__DIR__="`dirname \"$0\"`"
SF_PATH=${__DIR__}/../../../../..
REACCION_TEMPLATES_PATH=${SF_PATH}/vendor/reaccionestudio/reaccion-cms-bundle/Resources/views
BUNDLES_TEMPLATES_PATH=${SF_PATH}/templates

# OVERRIDE THIRD-PARTY BUNDLE VIEWS
echo "Removing ${BUNDLES_TEMPLATES_PATH}/bundles folder ..."

# Removing ReaccionCMSBundle
if [ -d "${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle" ]; then
	rm -R ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle
fi

mkdir ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle
mkdir ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates
mkdir ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes

# Copying ReaccionCMS/Resources/views/emailTemplates
echo "Copying ${REACCION_TEMPLATES_PATH}/emailTemplates in ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates ..."
cp -R ${REACCION_TEMPLATES_PATH}/emailTemplates/* ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates

# copying ReaccionCMS/Resources/views/rocket_theme
echo "Copying ${REACCION_TEMPLATES_PATH}/themes/rocket_theme in ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/rocket_theme ..."
cp -R ${REACCION_TEMPLATES_PATH}/rocket_theme ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes/rocket_theme

# Generate fos js routing file
php ${SF_PATH}/bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json

# install assets
echo "Installing assets ..."
php ${SF_PATH}/bin/console assets:install --symlink

if [ "$1" ]; then

	# Run Webpack encore
	echo "Compiling assets ..."
	node_modules/@symfony/webpack-encore/bin/encore.js $1

fi

# Clear cache
php ${SF_PATH}/bin/console cache:clear
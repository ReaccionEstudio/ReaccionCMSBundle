# PATHS
__DIR__="`dirname \"$0\"`"
SF_PATH=${__DIR__}/../../../../../
REACCION_ADMIN_BUNDLES_TEMPLATES_PATH=./src/ReaccionEstudio/ReaccionCMSAdminBundle/Resources/views/bundles
REACCION_TEMPLATES_PATH=./src/ReaccionEstudio/ReaccionCMSBundle/Resources/views
BUNDLES_TEMPLATES_PATH=./templates

# OVERRIDE THIRD-PARTY BUNDLE VIEWS
echo "Removing ${BUNDLES_TEMPLATES_PATH}/bundles folder ..."

rm -R ${BUNDLES_TEMPLATES_PATH}/bundles

echo "Copying ${REACCION_ADMIN_BUNDLES_TEMPLATES_PATH} in ${BUNDLES_TEMPLATES_PATH}/bundles ..."
cp -R ${REACCION_ADMIN_BUNDLES_TEMPLATES_PATH} ${BUNDLES_TEMPLATES_PATH}/bundles

# Removing ReaccionCMSBundle
rm -R ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates
mkdir ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates
rm -R ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes
mkdir ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes

# copying ReaccionCMS/Resources/views/emailTemplates
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
	yarn encore dev

fi

# Clear cache
php ${SF_PATH}/bin/console cache:clear
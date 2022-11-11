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

#####################################
# OVERRIDE THIRD-PARTY BUNDLE VIEWS #
#####################################

# Copying "templates/emailTemplates" to "ReaccionCMS/Resources/views/emailTemplates"
echo "Copying ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates/* in ${REACCION_TEMPLATES_PATH}/emailTemplates ..."
cp -R ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates/* ${REACCION_TEMPLATES_PATH}/emailTemplates

# Copying "templates/ReaccionCMSBundle" to "ReaccionCMS/Resources/views/rocket_theme"
echo "Copying ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes/rocket_theme/* in ${REACCION_TEMPLATES_PATH}/rocket_theme/ ..."
cp -R ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes/rocket_theme/* ${REACCION_TEMPLATES_PATH}/rocket_theme/

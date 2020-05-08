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

# Removing ReaccionCMSBundle/emailTemplates
if [ -d "${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates" ]; then
    echo "Removing ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates folder ..."
	rm -R ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates
fi

# Removing ReaccionCMSBundle/themes/rocket_theme
if [ -d "${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes/rocket_theme" ]; then
    rm -R ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes/rocket_theme
fi

# Creating required folders
mkdir -p ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates
mkdir -p ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes

# Copying ReaccionCMS/Resources/views/emailTemplates
echo "Copying ${REACCION_TEMPLATES_PATH}/emailTemplates in ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates ..."
cp -R ${REACCION_TEMPLATES_PATH}/emailTemplates/* ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/emailTemplates

# copying ReaccionCMS/Resources/views/rocket_theme
echo "Copying ${REACCION_TEMPLATES_PATH}/themes/rocket_theme in ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/rocket_theme ..."
cp -R ${REACCION_TEMPLATES_PATH}/rocket_theme/ ${BUNDLES_TEMPLATES_PATH}/ReaccionCMSBundle/themes/rocket_theme/

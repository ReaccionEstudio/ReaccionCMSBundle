ReaccionCMSBundle
==================

Currently in development.

# Installation

Install the package with composer

`composer require reaccionestudio/reaccion-cms-bundle`

Enable the bundle in **config/bundles.php** file:

    <?php
    # config/bundles.php
    
    return [
        // ...
        ReaccionEstudio\ReaccionCMSBundle\ReaccionCMSBundle::class => ['all' => true],
    ];
    

Add required parameters in the **config/services.yaml** file

    # config/services.yaml
    encryption_key: 'YOUR_ENCRIPTION_KEY'

Load the Routes of the Bundle:

    # config/routes.yaml
    reaccion_cms:
        resource: "@ReaccionCMSBundle/Resources/config/routing/all.xml"
        prefix:   /

Add new twig config parameters:

    # config/packages/twig.yaml
    twig:

        # ...
        paths:
          '%kernel.project_dir%/vendor/reaccionestudio/reaccion-cms-bundle/Resources/views' : ReaccionCMSBundle
        form_themes:
            - 'bootstrap_4_layout.html.twig'

Create **assets/js/front_app.js**:

    const $ = require('jquery');
    
    // JS
    require('bootstrap');
    require('../../public/bundles/reaccioncms/javascript/Main.js');
    
    // SCSS
    require('../../public/bundles/reaccioncms/stylesheet/global.scss');

Add asset entry in the **webpack.config.js** file with the following options:

    # webpack.config.js
    // ...
    .addEntry('front_app', './assets/js/front_app.js')
    
    .autoProvidejQuery()
    .autoProvideVariables({ Popper: ['popper.js', 'default'] })
    
    // enables Sass/SCSS support
    .enableSassLoader()
    
    .disableSingleRuntimeChunk()

Install all required Javascript vendors:

    npm install
    npm install node-sass@4.10.0 --save
    npm install jquery@3.3.1 --save
    npm install popper.js@1.14.4 --save
    npm install sass-loader@7.1.0 --save
    npm install bootstrap@4.2.1 --save


Build assets:

`sh vendor/reaccionestudio/reaccion-cms-bundle/Resources/bin/build.sh true`

Generate your database url in the **.env** file

`DATABASE_URL=mysql://username:password@host:port/database_name`

Create database schema

`php bin/console doctrine:schema:update --force`

Import default data from **install.sql** file in your database

`vendor/reaccionestudio/reaccion-cms-bundle/Resources/init_data/install.sql`


## Bootstrap 4 dropdown not showing

If your Bootstrap 4 dropdowns are not showing correctly and your browser console reports the following error `SyntaxError: 'javascript:void(0)' is not a valid selector`, you have to edit some lines in the Bootstrap source files.

Let's go:

First, find the `/node_modules/bootstrap/dist/js/bootstrap.js` next line:

`return selector && document.querySelector(selector) ? selector : null;`

And replace it with this one:

    try {
        return document.querySelector(selector) ? selector : null
    } catch (err) {
        return null
    }

Do the same replacement in the `/node_modules/bootstrap/dist/js/bootstrap.bundle.js` file.

And finally recompile your assets with the `build.sh` script:

For development environments:
`sh bin/build.sh dev`

For production environments:
`sh bin/build.sh prod`

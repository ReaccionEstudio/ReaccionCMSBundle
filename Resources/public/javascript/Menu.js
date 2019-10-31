import {APP_CONTEXT} from './AppContext/AppContext';

/**
 * Menu class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */
class Menu {
    /**
     * Constructor
     */
    constructor() {
        this.routeSlug = APP_CONTEXT.request.route_slug;
    }

    /**
     * Menu events
     */
    events() {
        this._routeIsActiveEvent();
    }

    /**
     * Add 'active' class to menu <a> HTML element if the link slug is equal than current route slug
     */
    _routeIsActiveEvent() {
        let _self = this;

        $("nav ul.navbar-nav li.nav-item a").each(function () {
            let menuSlug = $(this).attr("data-slug");

            if (menuSlug && (menuSlug === _self.routeSlug)) {
                $(this).addClass('active');
            }
        });
    }
}

export default Menu;

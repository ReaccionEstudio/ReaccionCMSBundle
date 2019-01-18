/**
 * Common events class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import RoutingManager from './RoutingManager.js';

class CommonEvents
{
  	constructor()
  	{
  		this.router = new RoutingManager();
  	}

  	/**
   	 * Language switcher event
   	 */
  	languageSwitcher()
  	{
  		let _self = this;

    	$('div#language_picker_widget div.dropdown-menu a').on('click', function(e)
    	{
    		let language = $(this).attr('data-language');

		    if( ! language) return;

        let appRoute = $(this).parent().parent().attr("data-app-route");
        let appRouteSlug = $(this).parent().parent().attr("data-app-route-slug");
        let routeParams = '?appRoute=' + appRoute + '&appRouteSlug=' + appRouteSlug;
		    let route = _self.router.generate('change_language', { 'language' : language });
            route += routeParams;

		    window.location = route;
    	});
	}
}

export default CommonEvents;
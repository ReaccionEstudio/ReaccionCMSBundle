// fos-js-routing
const routes = require('../../../../../public/js/fos_js_routes.json');
import Routing from '../../../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

/**
 * Routing manager class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */
class RoutingManager
{
	/**
   	* Constructor
   	*/
	constructor(){ }

	/**
	 * Generate route
	 *
	 * @param  String 	route 	Route name
	 * @param  JSON 	params 	Route params
	 * @return String 	[type] 	Route url
	 */
	generate(route, params)
	{
		if(typeof params == "undefined")
		{
			params = {};
		}

		return Routing.generate(route, params);
	}
}

export default RoutingManager;
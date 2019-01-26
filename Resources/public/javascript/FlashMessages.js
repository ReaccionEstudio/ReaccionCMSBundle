/**
 * Flash messages class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */
class FlashMessages
{
	/**
   	* Constructor
   	*/
  	constructor(){ }

  	/**
   	 * Flash messages events
   	 */
	events()
	{
		this._autoHideFlashMessages();
	}

	/**
	 * Auto hide flash mesasges with 'data-autohide' attribute
	 */
	_autoHideFlashMessages()
	{
		if($("div.fixed-alert").length)
		{
			// fadeIn
			$("div.fixed-alert").addClass("in");

			// fadeOut
			setTimeout(function()
			{
				$("div.fixed-alert").removeClass('in').remove();
			}, 
			5000);
		}
	}
}

export default FlashMessages;
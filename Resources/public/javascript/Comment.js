/**
 * Comment class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */

import Translations from "./translations/Messages.js";

class Comment
{
	/**
   	* Constructor
   	*/
  	constructor(){ }

  	/**
  	 * Comment events
  	 */
  	events()
  	{
  		this._removeCommentClickEvent();
  	}

  	/**
  	 * Handle the remove comment link click event
  	 */
  	_removeCommentClickEvent()
  	{
  		$('[data-remove-comment]').on("click", function(e)
  		{
  			e.preventDefault();

  			let url = $(this).attr("href");

  			// TODO: get current app language
  			if(confirm(Translations['en']['remove_comment_confirmation']))
  			{
  				window.location = url;
  			}
  		});
  	}
}

export default Comment;
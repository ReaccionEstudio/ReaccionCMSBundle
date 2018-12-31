import Translator from './Translator.js';

/**
 * Comment class
 *
 * @author  Alberto Vian - alberto@reaccionestudio.com
 * @website reaccionestudio.com
 */
class Comment
{
	/**
   	* Constructor
   	*/
  	constructor()
    {
      this.translator = new Translator();
    }

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
      let _self = this;

  		$('[data-remove-comment]').on("click", function(e)
  		{
  			e.preventDefault();

  			let url = $(this).attr("href");

        // remove_comment_confirmation
  			if(confirm(_self.translator.trans('remove_comment_confirmation')))
  			{
  				window.location = url;
  			}
  		});
  	}
}

export default Comment;
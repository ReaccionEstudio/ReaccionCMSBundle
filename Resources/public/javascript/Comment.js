import Translator from './Translator.js';
import RoutingManager from './RoutingManager.js';

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
      this.routing = new RoutingManager();
    }

  	/**
  	 * Comment events
  	 */
  	events()
  	{
      this._removeCommentClickEvent();
  		this._replyCommentEvents();
  	}

    /**
     * Handle the reply comment link click event
     */
    _replyCommentEvents()
    {
      let _self = this;

      $("a[data-reply]").on("click", function(e)
      {
        e.preventDefault();

        let commentId = $(this).attr("data-reply");
        _self._generateReplyForm(commentId);
      });

      $("body").on("click", 'form.reply_form button[type="submit"]', function(e)
      {
        if( ! $(this).parent().parent().find('textarea').val().length ) return;

        let spinnerHtml = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        $(this).attr("disabled", "disabled");
        $(this).html(spinnerHtml);
      });
    }

    /**
     * Generate reply form
     *
     * @param  Input   commentId   Comment ID
     * @return void
     */
    _generateReplyForm(commentId)
    {
      $("form.reply_form").remove();

      let commentSelector = '#comment_' + commentId;
      let formHtml   = $("form#post_comment_form").html();
      let formAction = this.routing.generate('post_comment_reply', { 'comment' : commentId });
      let replyForm  =  '<div class="row justify-content-center mb-4">\
                          <div class="col-md-11">\
                            <form id="reply_form_' + commentId + '" action="' + formAction + '" method="post" class="post_comment reply_form">' + 
                              formHtml + '\
                              <input type="hidden" name="parent" value="' + commentId + '" />\
                            </form>\
                          </div>\
                        </div>';

      $(commentSelector).after(replyForm);

      // replace form elements
      let replyPlaceholder = this.translator.trans("write_reply_textarea_placeholder");

      // placeholder
      $("form#reply_form_" + commentId).find("textarea").attr("placeholder", replyPlaceholder);
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
          // show spinner
          $(this).parent().html('<div class="spinner-border text-danger" role="status"></div>');

          // redirect
  				window.location = url;
  			}
  		});
  	}
}

export default Comment;
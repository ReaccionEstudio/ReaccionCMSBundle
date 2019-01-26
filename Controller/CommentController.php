<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Comment;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

	class CommentController extends Controller
	{
		/**
		 * Post a new comment
		 */
		public function post(Request $request, Entry $entry)
		{	
			$comment = $request->request->get("comment");
			$parentComment = 0;
			$newCommentId = $this->get("reaccion_cms.comment")->post($entry, $comment, $this->getUser(), $parentComment);

			// generate redirection url
			$redirectionUrl = $this->get("router")->generate("index_slug", [ 'slug' => $entry->getSlug() ]);
			$redirectionUrl .= "#post_comment";

			return new RedirectResponse($redirectionUrl);
		}

		/**
		 * Post a comment reply
		 */
		public function postReply(Request $request, Comment $comment)
		{
			if( ! $request->request->get("parent") )
			{
				throw new Error("Error, 'parent' parameter is empty.");
			}

			$entry = $comment->getEntry();

			// get request params
			$commentContent	= $request->request->get("comment");
			$parentComment 	= $request->request->get("parent");

			// add new comment
			$newCommentId = $this->get("reaccion_cms.comment")->post($entry, $commentContent, $this->getUser(), $parentComment);

			// generate redirection url
			$redirectionUrl = $this->get("router")->generate("index_slug", [ 'slug' => $entry->getSlug() ]);
			$redirectionUrl .= "#post_comment";

			return new RedirectResponse($redirectionUrl);
		}

		/**
		 * Update comment
		 */
		public function update(Request $request, Comment $comment)
		{
			// TODO: create security voter
			if(empty($this->getUser()))
			{
				// TODO: create custom message
			}

			if(empty($this->getUser()) || ($this->getUser() != $comment->getUser()))
			{
				throw new AccessDeniedHttpException();
			}

			$commentContent = $request->request->get("comment");
		}

		/**
		 * Remove comment
		 */
		public function remove(Request $request, Comment $comment)
		{
			$entry = $comment->getEntry();

			// remove comment
			$this->get("reaccion_cms.comment")->remove($comment);

			// generate redirection url
			$redirectionUrl = $this->get("router")->generate("index_slug", [ 'slug' => $entry->getSlug() ]);
			$redirectionUrl .= "#post_comment";

			return new RedirectResponse($redirectionUrl);
		}
	}
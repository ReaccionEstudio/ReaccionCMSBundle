<?php

	namespace App\ReaccionEstudio\ReaccionCMSBundle\Controller;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Comment;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class CommentController extends Controller
	{
		/**
		 * Post a new comment
		 */
		public function post(Request $request, Entry $entry)
		{	
			$comment = $request->request->get("comment");
			$parentComment = 0; // TODO
			$newCommentId = $this->get("reaccion_cms.comment")->postComment($entry, $comment, $this->getUser(), $parentComment);

			// generate redirection url
			$redirectionUrl = $this->get("router")->generate("index_slug", [ 'slug' => $entry->getSlug() ]);
			$redirectionUrl .= "#post_comment";

			return new RedirectResponse($redirectionUrl);
		}

		/**
		 * Remove comment
		 */
		public function remove(Request $request, Comment $comment)
		{
			$entry = $comment->getEntry();

			// remove comment
			$this->get("reaccion_cms.comment")->removeComment($comment);

			// generate redirection url
			$redirectionUrl = $this->get("router")->generate("index_slug", [ 'slug' => $entry->getSlug() ]);
			$redirectionUrl .= "#post_comment";

			return new RedirectResponse($redirectionUrl);
		}

	}
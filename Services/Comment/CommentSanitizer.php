<?php

	namespace ReaccionEstudio\ReaccionCMSBundle\Services\Comment;

	/**
	 * Formats and sanitizes the comment content.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	final class CommentSanitizer
	{
		/**
		 * @var String
		 *
		 * Comment content
		 */
		private $content;

		/**
		 * Constructor
		 */
		public function __construct(String $content)
		{
			$this->content = $content;
			$this->sanitize();
		}

		/**
		 * Sanitize comment content
		 */
		public function sanitize() : void
		{
			$this->content = nl2br($this->content);
			$this->content = strip_tags($this->content, "<br>");
		}

		/**
		 * Get content var value
		 *
		 * @return String 	$this->content 	Sanitized comment content
		 */
		public function getContent() : String
		{
			return $this->content;
		}
	}
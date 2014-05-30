<?php
namespace Lelesys\Comments\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Lelesys.Comments".      *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

use TYPO3\Flow\Mvc\Controller\ActionController;
use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\I18n\Translator;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeTemplate;

/**
 * Comment controller to create new commnet
 *
 * @Flow\Scope("singleton")
 */
class CommentController extends ActionController {

	/**
	 * The Translator
	 *
	 * @Flow\Inject
	 * @var Translator
	 */
	protected $translator;

	/**
	 * Create new comment
	 *
	 * @Flow\Validate(type="Lelesys\Captcha\Validators\CaptchaValidator", value="captcha")
	 * @Flow\Validate(type="NotEmpty", value="captcha")
	 * @Flow\Validate(type="Lelesys\Comments\Validation\Validators\CommentValidator", value="newComment")
	 * @param NodeInterface $node The node which will contain the new comment
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeTemplate<Lelesys.Comments:Comment> $newComment The new comment
	 * @param string $capthca The captcha
	 * @return void
	 */
	public function createAction(NodeInterface $node, NodeTemplate $newComment, $captcha = NULL) {
			// Get a document node if node is not instance of TYPO3.Neos:Document
		$flowQuery = new FlowQuery(array($node));
		if ($flowQuery->is('[instanceof TYPO3.Neos:Document]') === FALSE) {
			$documentNode = $flowQuery->closest('[instanceof TYPO3.Neos:Document]')->get(0);
		} else {
			$documentNode = $node;
		}

		$commentNode = $node->getNode('comments')->createNodeFromTemplate($newComment);
		$commentNode->setProperty('date', new \DateTime());
		if ($this->settings['moderation'] === 1) {
			$commentNode->setHidden(TRUE);
		}

		$this->addFlashMessage($this->translator->translateById('Lelesys.Comments.NewComment', array(), NULL, NULL, 'Main', $this->settings['translation']['packageKey']), '', Message::SEVERITY_OK);
		$this->emitCommentCreated($node, $commentNode, $documentNode);

		$this->redirect('show', 'Frontend\Node', 'TYPO3.Neos', array('node' => $documentNode));
	}

	/**
	 * Signal which informs about a newly created comment
	 *
	 * @param NodeInterface $node The node which will contain the new comment
	 * @param NodeInterface $commentNode The comment node
	 * @param NodeInterface $documentNode The document node
	 * @return void
	 * @Flow\Signal
	 */
	protected function emitCommentCreated(NodeInterface $node, NodeInterface $commentNode, NodeInterface $documentNode) {}

	/**
	 * Get error flash message
	 *
	 * @return boolean No flash messagen set
	 * @api
	 */
	protected function getErrorFlashMessage() {
		return FALSE;
	}
}
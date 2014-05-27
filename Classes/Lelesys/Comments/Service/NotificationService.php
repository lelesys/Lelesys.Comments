<?php
namespace Lelesys\Comments\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Lelesys.Comments".      *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

use TYPO3\Fluid\View\StandaloneView;
use TYPO3\SwiftMailer\Message;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * Notification Service to send notification to admin when new comment is created
 *
 * @Flow\Scope("singleton")
 */
class NotificationService {

	/**
	 * The settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Inject settings
	 *
	 * @param array $settings The settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Send new comment created notification
	 *
	 * @param NodeInterface $node The node which will contain the new comment
	 * @param NodeInterface $commentNode The comment node
	 * @param NodeInterface $documentNode The document node
	 * @return void
	 */
	public function sendNotification(NodeInterface $node, NodeInterface $commentNode, NodeInterface $documentNode) {
		if ($this->settings['notification']['email'] === '') {
			return;
		}

		$format = $this->settings['notification']['format'];
		$view = new StandaloneView();
		$view->assignMultiple(array('node' => $node, 'commentNode' => $commentNode, 'documentNode' => $documentNode));
		$view->setTemplatePathAndFilename($this->settings['notification']['template'][$format]['templatePathAndFilename']);
		$messageBody = $view->render();

		$newMail = New Message();
		$newMail->setFrom(array($commentNode->getProperty('emailAddress') => $commentNode->getProperty('name')))
				->setTo(array($this->settings['notification']['email'] => $this->settings['notification']['name']))
				->setSubject($this->settings['notification']['subject'])
				->setBody($messageBody, 'text/html')
				->send();
	}
}

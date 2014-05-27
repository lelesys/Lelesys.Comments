<?php
namespace Lelesys\Comments;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Lelesys.Comments".      *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Package\Package as BasePackage;

/**
 * The Lelesys Comments Package
 *
 */
class Package extends BasePackage {

	/**
	 * Invokes custom PHP code directly after the package manager has been initialized.
	 *
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap The current bootstrap
	 * @return void
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$dispatcher = $bootstrap->getSignalSlotDispatcher();
		$dispatcher->connect('Lelesys\Comments\Controller\CommentController', 'commentCreated', 'Lelesys\Comments\Service\NotificationService', 'sendNotification');
	}
}

?>
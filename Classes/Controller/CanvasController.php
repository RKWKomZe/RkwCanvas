<?php

namespace RKW\RkwCanvas\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * CanvasController
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwCanvas
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CanvasController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    const SESSION_KEY = 'rkw_canvas';

    /**
     * canvasRepository
     *
     * @var \RKW\RkwCanvas\Domain\Repository\CanvasRepository
     * @inject
     */
    protected $canvasRepository = null;

    /**
     * frontendUserRepository
     *
     * @var \RKW\RkwRegistration\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRepository = null;

    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

    /**
     * initializeAction
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function initializeAction(): void
    {

        parent::initializeAction();

    }

    /**
     * returns the logged in FrontendUser - to be used in other functions
     *
     * @return int the user id
     */
    protected function getFrontendUserId()
    {
        $userId = $GLOBALS['TSFE']->fe_user->user['uid'];

        return $userId;
        //===
    }

    /**
     * Returns current logged in user object
     *
     * @return \RKW\RkwWebcheck\Domain\Model\FrontendUser|NULL
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    protected function getFrontendUser()
    {

        if (!$this->getFrontendUserId()) {
            $this->addFlashMessage(
                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'webcheckController.warning.notLoggedIn',
                    'rkw_webcheck'
                ),
                null,
                \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR
            );
            $this->redirect('error');
            //===
        }

        /** @var \RKW\RkwWebcheck\Domain\Repository\FrontendUserRepository $frontendUserRepository */
        return $this->frontendUserRepository->findByIdentifier($this->getFrontendUserId());
        //===
    }

    /**
     * action edit
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function editAction(): void
    {
        $translations = [
            'canvasController.message.success.notesSaved' => [
                'value' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'canvasController.message.success.notesSaved',
                    'rkw_canvas'
                )
            ],
            'canvasController.message.success.notesSavingFailed' => [
                'value' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'canvasController.message.success.notesSavingFailed',
                    'rkw_canvas'
                )
            ],
        ];

        $this->view->assign('translations', json_encode($translations));
    }

    /**
     * action jsonGet
     *
     * @return string
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function jsonGetAction(): string
    {

        $returnArray = [];

        $canvases = $this->canvasRepository->findByFeUser($this->getFrontendUser());

        if (count($canvases) > 0) {
            $canvas = $canvases->getFirst();

            $returnArray['message'] = [
                'type'  => 'success',
                'message' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'canvasController.message.success.notesLoaded',
                    'rkw_canvas'
                )
            ];
            $returnArray['data'] = $canvas->getNotes();

            return json_encode($returnArray);
            //===
        }

        $returnArray['message'] = [
            'type'  => 'error',
            'message' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                'canvasController.message.success.notesNotFound',
                'rkw_canvas'
            )
        ];

        return json_encode($returnArray);
    }

    /**
     * action jsonPost
     *
     * @param string $notes
     * @return string
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function jsonPostAction($notes = ''): string
    {

        $returnArray = [];

        $frontendUser = $this->getFrontendUser();

        if ($this->request->hasArgument('notes') && $frontendUser) {

            $notes = trim(stripslashes($this->request->getArgument('notes')), '"');

            $canvases = $this->canvasRepository->findByFeUser($this->getFrontendUser());

            if (count($canvases) > 0) {
                $canvas = $canvases->getFirst();
                $canvas->setNotes($notes);
                $canvas->setFrontendUser($frontendUser);

                $this->canvasRepository->update($canvas);
                $this->persistenceManager->persistAll();

                $returnArray['message'] = [
                    'type' => 'success',
                    'message' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'canvasController.message.success.notesSaved',
                        'rkw_canvas'
                    ),
                ];

            } else {

                // Initialize new canvas
                /** @var \RKW\RkwCanvas\Domain\Model\Canvas $canvas */
                $canvas = GeneralUtility::makeInstance('RKW\\RkwCanvas\\Domain\\Model\\Canvas');
                $canvas->setNotes($notes);
                $canvas->setFrontendUser($this->getFrontendUser());
                $this->canvasRepository->add($canvas);

                // Persist
                $this->persistenceManager->persistAll();

                $returnArray['message'] = [
                    'type' => 'success',
                    'message' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                        'canvasController.message.success.notesSaved',
                        'rkw_canvas'
                    ),
                ];

            }

        } else {

            $returnArray['message'] = [
                'type' => 'error',
                'message' => \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
                    'canvasController.message.success.notesSavingFailed',
                    'rkw_canvas'
                ),
            ];

        }

        return json_encode($returnArray);

    }

}

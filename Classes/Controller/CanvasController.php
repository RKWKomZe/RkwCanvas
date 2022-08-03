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

//    /**
//     * frontendUserRepository
//     *
//     * @var \RKW\RkwRegistration\Domain\Repository\FrontendUserRepository
//     * @inject
//     */
//    protected $frontendUserRepository = null;
//
//    /**
//     * logged in FrontendUser
//     *
//     * @var \RKW\RkwRegistration\Domain\Model\FrontendUser
//     */
//    protected $frontendUser = null;

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
     * action edit
     *
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function editAction(): void
    {
        return;
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

        $canvasId = 1;

        /** @var  \RKW\RkwCanvas\Domain\Model\Canvas $canvasTemp */
        $canvasTemp = $this->canvasRepository->findByIdentifier(intval($canvasId));

        $jsonData = $canvasTemp->getNotes();

        return json_encode($jsonData);
    }

    /**
     * action jsonPost
     *
     * @param string $notes
     * @param string $check
     * @return string
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function jsonPostAction($notes = ''): string
    {

        if ($this->request->hasArgument('notes')) {

            $notes = trim(stripslashes($this->request->getArgument('notes')), '"');

            $canvasId = 1;

            /** @var  \RKW\RkwCanvas\Domain\Model\Canvas $canvasTemp */
            $canvas = $this->canvasRepository->findByIdentifier(intval($canvasId));
            $canvas->setNotes($notes);

            $this->canvasRepository->update($canvas);
            $this->persistenceManager->persistAll();

            return json_encode('success');

        }

        return json_encode('error');

    }

}

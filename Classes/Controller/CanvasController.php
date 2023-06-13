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

use Madj2k\FeRegister\Domain\Model\FrontendUser;
use Madj2k\FeRegister\Domain\Repository\FrontendUserRepository;
use Madj2k\FeRegister\Utility\FrontendUserSessionUtility;
use RKW\RkwCanvas\Domain\Model\Canvas;
use RKW\RkwCanvas\Domain\Repository\CanvasRepository;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3Fluid\Fluid\View\TemplateView;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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

    /**
     * @var \RKW\RkwCanvas\Domain\Repository\CanvasRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected ?CanvasRepository $canvasRepository;


    /**
     * @var \Madj2k\FeRegister\Domain\Repository\FrontendUserRepository
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected FrontendUserRepository $frontendUserRepository;


    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    protected PersistenceManager $persistenceManager;


    /**
     * returns the logged in FrontendUser - to be used in other functions
     *
     * @return int the user id
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     */
    protected function getFrontendUserId(): int
    {
        return FrontendUserSessionUtility::getLoggedInUserId();
    }


    /**
     * Returns current logged in user object
     *
     * @return \Madj2k\FeRegister\Domain\Model\FrontendUser|null
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     */
    protected function getFrontendUser():? FrontendUser
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
        }


        /** @var \RKW\RkwWebcheck\Domain\Repository\FrontendUserRepository $frontendUserRepository */
        return FrontendUserSessionUtility::getLoggedInUser();
    }


    /**
     * Method overrides is base method as base method relies
     * on objectManager to instantiate PageRenderer::class.
     * But PageRenderer::class is initially instantiated by
     * GeneralUtility::makeInstance, so the base method does not
     * append the assets. This function may be removed, if the
     * installation is upgraded to TYPO3 9.5.
     *
     * see https://forge.typo3.org/issues/89445
     *
     * @deprecated
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     * @return void
     */
    protected function renderAssetsForRequest($request): void
    {
        if (!$this->view instanceof TemplateView) {
            return;
        }

        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $variables = ['request' => $request, 'arguments' => $this->arguments];
        $headerAssets = $this->view->renderSection('HeaderAssets', $variables, true);
        $footerAssets = $this->view->renderSection('FooterAssets', $variables, true);
        if (!empty(trim($headerAssets))) {
            $pageRenderer->addHeaderData($headerAssets);
        }
        if (!empty(trim($footerAssets))) {
            $pageRenderer->addFooterData($footerAssets);
        }
    }


    /**
     * action edit
     *
     * @return void
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

        $this->view->assign('readMore', $this->uriBuilder->reset()
            ->setTargetPageUid($this->settings['readMore'])
            ->setCreateAbsoluteUri(true)
            ->build());
        $this->view->assign('translations', json_encode($translations));
    }


    /**
     * action jsonGet
     *
     * @return string
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
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
     * @throws \TYPO3\CMS\Core\Context\Exception\AspectNotFoundException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    public function jsonPostAction(string $notes = ''): string
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
                $canvas = GeneralUtility::makeInstance(Canvas::class);
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

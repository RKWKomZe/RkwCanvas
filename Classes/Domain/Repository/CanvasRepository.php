<?php

namespace RKW\RkwCanvas\Domain\Repository;

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

/**
 * CanvasRepository
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwCanvas
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class CanvasRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Set setRespectStorage on FALSE by default
     */
    public function initializeObject()
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(false); // ignore the storagePid
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * find canvases for a specific user
     * sorted by tstamp
     *
     * @param \RKW\RkwWebcheck\Domain\Model\FrontendUser $feUser
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByFeUser($feUser)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('frontendUser', $feUser)
        );
        $query->setOrderings(array("tstamp" => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));

        return $query->execute();
        //===
    }

}

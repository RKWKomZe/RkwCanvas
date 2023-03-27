<?php

namespace RKW\RkwCanvas\Domain\Model;

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

/**
 * Canvas
 *
 * @author Christian Dilger <c.dilger@addorange.de>
 * @copyright Rkw Kompetenzzentrum
 * @package RKW_RkwCanvas
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Canvas extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var string
     */
    protected string $title ='';


    /**
     * @var string
     */
    protected string $notes = '';


    /**
     * @var \Madj2k\FeRegister\Domain\Model\FrontendUser|null
     */
    protected ?FrontendUser $frontendUser = null;


    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }


    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }


    /**
     * Returns the notes
     *
     * @return string $notes
     */
    public function getNotes(): string
    {
        return $this->notes;
    }


    /**
     * Sets the notes
     *
     * @param string $notes
     * @return void
     */
    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }


    /**
     * Returns the frontendUser
     *
     * @return \Madj2k\FeRegister\Domain\Model\FrontendUser
     */
    public function getFrontendUser():? FrontendUser
    {
        return $this->frontendUser;
    }


    /**
     * Sets the frontendUser
     *
     * @param \Madj2k\FeRegister\Domain\Model\FrontendUser $frontendUser
     * @return void
     */
    public function setFrontendUser(FrontendUser $frontendUser): void
    {
        $this->frontendUser = $frontendUser;
    }
}

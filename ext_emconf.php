<?php

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

$EM_CONF[$_EXTKEY] = array(
    'title' => 'ADFC Page not Found Handler',
    'description' => 'Errorhandler for 401 and 404.',
    'category' => 'fe',
    'author' => 'Jan Bartels, Daniel Siepmann',
    'author_email' => 'j.bartels@adfc-nrw.de, d.siepmann@web-vision.de',
    'state' => 'beta',
    'author_company' => 'ADFC NRW e. V.',
    'version' => '1.0.1',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2',
        ),
    ),
);

<?php
namespace Adfc\AdfcPagenotfound\Userfunction;

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

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;

/**
 * (c) 2011 Jan Bartels (j.bartels@adfc-nrw.de)
 * (c) 2013 Lorenz Ulrich <lorenz.ulrich@phz.ch>
 *
 * @author Daniel Siepmann <d.siepmann@web-vision.de>
 */
class PageNotFound
{
    /**
     * Entry point to handle 404 page not found by TYPO3.
     *
     * Used to provide further feature for 404 based on access restrictions.
     *
     * @param array $parameter Parameter by TYPO3.
     * @param TypoScriptFrontendController $parentObject Current instance.
     *
     * @return string
     */
    public function handle(array &$parameter, TypoScriptFrontendController $parentObject)
    {
        if ($this->isAuthenticatedButNotAuthorized($parameter['pageAccessFailureReasons'])) {
            return $this->handleUnauthorizedAccess(false);
        } else if ($this->isUnauthorized($parameter['pageAccessFailureReasons'])) {
            return $this->handleUnauthorizedAccess();
        }

        return $this->handleNotFound($parameter, $parentObject);
    }

    /**
     * Check whether the reason for 404 was access restriction.
     *
     * @param array $accessReason The reason provided by TYPO3.
     *
     * @return bool
     */
    protected function isUnauthorized(array $accessReason)
    {
        if (isset($accessReason['fe_group']) && $accessReason['fe_group'][''] !== 0) {
            return true;
        }

        return false;
    }

    /**
     * Check whether user is logged in, but the page is restricted to another fe group
     *
     * @param array $accessReason
     * @return bool
     */
    protected function isAuthenticatedButNotAuthorized(array $accessReason)
    {
        return isset($accessReason['fe_group']) && !isset($accessReason['fe_group']['']);
    }

    /**
     * Handle 401 requests, to pages where access is not allowed.
     *
     * Will redirect to the configured url and exit.
     *
     * @param bool $addRedirectParam
     * @return void
     */
    protected function handleUnauthorizedAccess($addRedirectParam = true)
    {
        HttpUtility::redirect($this->getRedirectUrlForUnauthorized($addRedirectParam), HttpUtility::HTTP_STATUS_401);
    }

    /**
     * @param array $parameter Parameter by TYPO3.
     * @param TypoScriptFrontendController $parentObject Current instance.
     *
     * @return void
     */
    protected function handleNotFound(array $parameter, TypoScriptFrontendController $parentObject)
    {
        $domainConfiguration = $this->getDomainConfiguration();

        $parentObject->pageNotFoundHandler(
            $domainConfiguration['pageNotFound_handling'],
            $GLOBALS['TYPO3_CONF_VARS']['FE']['pageNotFound_handling_statheader'],
            $parameter['reasonText']
        );
    }

    /**
     * Generate url for redirect of unauthorized page access.
     *
     * @param bool $addRedirectParam
     * @return string
     */
    protected function getRedirectUrlForUnauthorized($addRedirectParam)
    {
        $domainConfiguration = $this->getDomainConfiguration();
        $redirectUrl = $addRedirectParam ?
            $domainConfiguration['pageNotAuthorized_Url'] :
            $domainConfiguration['pageNotAuthenticatedNotAuthorized_Url'];

        // Prefix with current site url if relative.
        if (substr($redirectUrl, 0, 1) === '/') {
            $redirectUrl = GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . substr($redirectUrl, 1);
        }

        if ($addRedirectParam) {
            $redirectParam = 'redirect_url=' . rawurlencode(GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));

            // Use '?' or '&' as concat for parameter
            if (strpos($redirectUrl, '?') === false) {
                $redirectUrl .= '?';
            } else {
                $redirectUrl .= '&';
            }

            $redirectUrl .= $redirectParam;
        }

        return $redirectUrl;
    }

    /**
     * Returns the related configuration for domain
     *
     * @return array
     */
    protected function getDomainConfiguration()
    {
        $config = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['adfc_pagenotfound'];
        $domainKey = '_DEFAULT';
        $domain = GeneralUtility::getIndpEnv('HTTP_HOST');

        if (is_array($config) && isset($config[$domain])) {
            $domainKey = $domain;
        }

        if (is_array($config[$domainKey])) {
            return $config[$domainKey];
        }

        return array();
    }
}

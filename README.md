# adfc_pagenotfound

## About

Page not found handling for TYPO3 - redirects to a different page depending on
the type of error (FE login required or 404). Originally developed by the ADFC,
this is a heavily changed version of this (non-published) extension.

## Configuration

Use this in `AdditionalConfiguration.php` to configure the extension:

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['adfc_pagenotfound'] = array(
        '_DEFAULT' => array(
            'pageNotFound_handling' => 'http://www.tld.dev/index.php?id=257', # insert the SpeakingURL path segment of the error page here
            'pageNotAuthorized_Url' => 'http://www.tld.dev/index.php?id=256', # insert the SpeakingURL path segment of the error page here with login-form
            'pageNotAuthenticatedNotAuthorized_Url' => 'http://www.tld.dev/index.php?id=258', # insert the SpeakingURL path segment of the access-denied page here
        ),
        'otherdomain.tld' => array(
            'pageNotFound_handling' => 'http://otherdomain.tld/index.php?id=301', # insert the SpeakingURL path segment of the error page here
            'pageNotAuthorized_Url' => 'http://otherdomain.tld/index.php?id=301', # insert the SpeakingURL path segment of the error page here with login-form
            'pageNotAuthenticatedNotAuthorized_Url' => 'http://otherdomain.tld/index.php?id=258', # insert the SpeakingURL path segment of the access-denied page here
        ),
    );


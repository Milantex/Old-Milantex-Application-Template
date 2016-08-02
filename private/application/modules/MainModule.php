<?php
namespace Application\Modules;

class MainModule {
    public static function getPage(\Old\Milantex\Core\Context &$context) {
        $pageLink = $context->getUrlArgument(1);

        if ($pageLink === '') { # Ako nema linka, podrazumevamo da je home
            $pageLink = 'home';
        }

        $pageDataSet = new \Application\DataSets\PageDataSet($context->getDatabase());
        $page = $pageDataSet->getByLink($pageLink);

        if (!$page) { # Ako je postoji trazena stranica, preusmeravamo na home
            $context->setResponseHeader('Location', $context->getBaseUrl() . 'page/home');
            $context->setTemplate('');
            return;
        }

        $context->setData('page', $page);

        $context->setTemplate('Main/page');
    }
}

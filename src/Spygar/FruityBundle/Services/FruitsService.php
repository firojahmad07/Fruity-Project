<?php
namespace Spygar\FruityBundle\Services;

use Spygar\FruityBundle\Services\OauthService;

class FruitsService
{
    /** @var OauthService */
    public $oauthService;

    /** 
     * @param OauthService $oauthService
     **/
    public function __construct(OauthService $oauthService)
    {
        $this->oauthService = $oauthService;
    }

    public function fruitsFetch()
    {
        return $this->oauthService->request();
    }
}
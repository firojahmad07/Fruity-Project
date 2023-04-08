<?php

namespace Spygar\FruityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spygar\FruityBundle\Repository\FruitsRepository;
use Spygar\FruityBundle\Repository\FamilyRepository;

class IndexController extends AbstractController
{
    /** @var FruitsRepository */
    protected $fruitsRepository;

    /** @var FamilyRepository */
    protected $familyRepository;

    /** @var array */
    public $familyCollection;

    /**
     * @param FruitsRepository  $fruitsRepository
     * @param FamilyRepository  $familyRepository
     */
    public function __construct(
        FruitsRepository $fruitsRepository,
        FamilyRepository $familyRepository
        ) 
    {
        $this->fruitsRepository = $fruitsRepository;
        $this->familyRepository = $familyRepository;
    }
    public function index(): Response
    {
        return $this->render('@SpygarFruity/index.html.twig');
    }

    public function list(Request $request) 
    {
        $response['fruits'] = $this->fruitsRepository->fetchFruits($request);
        $currentPage = !empty($request->get('page')) ? $request->get('page') : 1;

        if (!$this->familyCollection) {
            $response['familyFilter'] = $this->familyRepository->fetchFamilies();
        }
        $totalResults = $this->fruitsRepository->totalFruits($request);
        $response['pagination'] = $this->generatePagination($totalResults);
        $response['totalResults'] = $totalResults;
        $response['currentPage'] = $currentPage;
        
        return new JsonResponse($response);
    }

    /** generatePagination */
    public function generatePagination($totalResults) 
    {
        $pagination = [];
        $numberOfPosiblePages = ceil((int) $totalResults / 10);
        for ($i=1; $i <= $numberOfPosiblePages; $i++) {
            $pagination[] = $i;
        }

        return $pagination;
    }
    public function addToFavorite(Request $request)
    {
        $params = json_decode($request->getContent(), true);
        $params = is_array($params) ? $params : [];
        if (!empty($params)) {
            $this->fruitsRepository->updateFavoriteStatus($params,  true);
        }

        return new JsonResponse($params);
    }

    public function removeFromFavorite(Request $request)
    {
        $params = json_decode($request->getContent(), true);
        $params = is_array($params) ? $params : [];
        if (!empty($params)) {
            $this->fruitsRepository->updateFavoriteStatus($params, false);
        }

        return new JsonResponse($params);
    }

    

    public function fetchFavoriteFruits(Request $request)
    {
        $favoriteFruites = $this->fruitsRepository->fetchFavorite();

        $response['favoriteFruites'] = $favoriteFruites;
        $response['sumofnutritions'] = $this->calculateNutritions($favoriteFruites);

        return new JsonResponse($response);
    }

    public function calculateNutritions(array $favoriteFruites)
    {
        $nutritions = [];

        foreach($favoriteFruites as $favoriteFruite) 
        {
            if (!empty($nutritions)) {
                $fruiteNutritions = $favoriteFruite['nutritions'];
                foreach($nutritions as $nutritionName => $nutritionValue) {
                    if (array_key_exists($nutritionName, $nutritions)) {
                        $updateValue = $nutritionValue + $fruiteNutritions[$nutritionName];
                        $nutritions[$nutritionName] = round($updateValue, 2); 
                    }
                }
            } else {
                $nutritions = $favoriteFruite['nutritions'];
            }
        }

        return $nutritions;
    }
}
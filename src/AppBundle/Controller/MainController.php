<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Helper\MapHelper;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;

class MainController extends Controller
{
    /**
     * @Route("/", name="getHTML5Geolocation")
     * @Method("GET")
     */
    public function getHTML5GeolocationAction()
    {
        return $this->render('AppBundle:Main:getHTML5Geolocation.html.twig');
    }

    /**
     * @Route("/", name="setUserGeolocation")
     * @Method("POST")
     */
    public function setUserGeolocation(Request $request)
    {
        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');

        if (!$this->get('session')->isStarted()) {
            $session = new Session();
            $session->start();
        }
        $this->get('session')->set('latitude', $latitude);
        $this->get('session')->set('longitude', $longitude);
        $return = json_encode(array("success" => true));

        return new Response($return, 200, array('Content-Type' => 'application/json'));
    }

    /**
     * @Route("/main", name="main")
     */
    public function mainAction()
    {
        //No session, we need to retrieve user geolocation
        if (!$this->get('session')->has('latitude')) {
            return $this->redirect($this->generateUrl('getHTML5Geolocation'));
        }

        $map = $this->createMap();

        return $this->render('AppBundle:Main:main.html.twig', array('map' => $map));
    }

    private function createMap()
    {
        $latitude = $this->get('session')->get('latitude');
        $longitude = $this->get('session')->get('longitude');

        $map = new Map();
        $map->setPrefixJavascriptVariable('map_');
        $map->setHtmlContainerId('map-canvas');
        $map->setAsync(false);
        $map->setAutoZoom(false);
        $map->setCenter($latitude, $longitude, true);
        $map->setMapOptions(
            array(
                'disableDefaultUI' => true,
                'disableDoubleClickZoom' => true,
                'zoom' => 18,
                'mapTypeId' => 'roadmap'
            )
        );
        $map->setLanguage('es');

        $marker = new Marker();
        $marker->setPrefixJavascriptVariable('marker_');
        $marker->setPosition($latitude, $longitude, true);
        $marker->setAnimation(Animation::DROP);
        $marker->setOptions(
            array(
                'clickable' => false,
                'flat' => true,
            )
        );
        $map->addMarker($marker);

        return $map;
    }
}
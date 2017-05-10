<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
 
class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number")
     */
    public function numberAction()
    {
        $number = rand(0, 100);
    
        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
 
    /**
     * @Route("/lucky/api/number")
     */
    public function apiNumberAction()
    {
        $data = array(
            'lucky_number' => rand(0, 100),
        );

        /**
        // Manually calls json_encode and sets the Content-Type header
        return new Response(
            json_encode($data),
            200,
            array('Content-Type' => 'application/json')
        );*/
 
        // Auto calls json_encode and sets the Content-Type header
        return new JsonResponse($data);
    }

    /**
     * @Route("/lucky/number/{count}")
     */
    public function numberCountAction($count)
    {
        $numbers = array();
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = rand(0, 100);
        }
        $numbersList = implode(', ', $numbers);
    
        return new Response(
            '<html><body>Lucky numbers: '.$numbersList.'</body></html>'
        );
    }

    /**
     * @Route("/lucky/number-twig/{count}")
     */
    public function numberTwigAction($count)
    {
        $numbers = array();
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = rand(0, 100);
        }
        $numbersList = implode(', ', $numbers);
        
        /**
        $html = $this->container->get('templating')->render(
            'lucky/number.html.twig',
            array('luckyNumberList' => $numbersList)
        );
    
        return new Response($html);
        */
       
        // render: a shortcut that does the same as above 快捷方法
        return $this->render(
            'lucky/number.html.twig',
            array('luckyNumberList' => $numbersList)
        );
    }
}

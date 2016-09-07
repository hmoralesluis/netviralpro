<?php

namespace App\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class ProductController extends Controller
{
    /**
     * @Route("/{_locale}/page/product/{id}", name="product", defaults={"_locale": "es", "id" : null})
     */
    public function productAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $affiliate = $session->get('affiliate');
        if (isset($affiliate) && !is_null($affiliate)) {
            return $this->redirect($this->generateUrl('affiliate_product', array('username' => $affiliate)));
        }
        $product = $em->getRepository('AdminBundle:Module')->find($id);
        if(!$product){
            throw $this->createNotFoundException('Módulo no encontrado');
        }
        return $this->render('FrontBundle:Products:Product.html.twig', array('product' => $product ));
    }

    /**
     * @Route("/{_locale}/{username}/page/product/{id}", name="affiliate_product", defaults={"_locale": "es", "username" : null, "id" : null})
     */
    public function affiliateProductAction($username, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $affiliate = $session->get('affiliate');
        if (is_null($username) && isset($affiliate)) {
            return $this->redirect($this->generateUrl('affiliate_product', array('username' => $affiliate)));
        } elseif(is_null($username) && !isset($affiliate)){
            return $this->redirect($this->generateUrl('product'));
        }
        if (!is_null($username)) {
            $user = $em->getRepository('AdminBundle:User')->findBy(array('username' => $username));
            if (!$user) {
                throw $this->createNotFoundException('Usuario de afiliado no válido');
            }
        }
        $session->set('affiliate', $username);
        $product = $em->getRepository('AdminBundle:Module')->find($id);
        if(!$product){
            throw $this->createNotFoundException('Módulo no encontrado');
        }
        return $this->render('FrontBundle:Products:Product.html.twig', array('product' => $product ));
    }
}

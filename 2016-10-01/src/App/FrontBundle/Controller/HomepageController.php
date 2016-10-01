<?php

namespace App\FrontBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class HomepageController extends Controller
{
    /**
     * @Route("/{_locale}", name="homepage", defaults={"_locale": "es"})
     */
    public function homepageAction($_locale)
    {
        $session = $this->get('session');
        if(!is_null($_locale)){
            $session->set('_locale', $_locale);
        } else {
            $session->set('_locale', 'es');
        }
        $affiliate = $session->get('affiliate');
        if (isset($affiliate) && !is_null($affiliate)) {
            return $this->redirect($this->generateUrl('affiliate_homepage', array('username' => $affiliate)));
        } else {
            $affiliate = null;
        }
        $em = $this->getDoctrine()->getManager();
        //$locales = $em->getRepository('AdminBundle:Country')->findAll();
        $locales = array('es','en','fr','ae','in','it','kr','pt','jp','ru','th','id');
        $session->set('locales', $locales);
        $blocks = $em->getRepository('ContentManagementBundle:ContentBlock')->findBy(array('active' => true),
            array('position' => 'ASC'));
        $modules = $em->getRepository('AdminBundle:Module')->findToShow();
        return $this->render('FrontBundle:Homepage:homepage.html.twig', array(
            'blocks' => $blocks, 'modules' => $modules
        ));
    }

    /**
     * @Route("/{_locale}/{username}", name="affiliate_homepage", defaults={"_locale": "es", "username" : null})
     */
    public function affiliateHomepageAction($username, $_locale)
    {
        $session = $this->get('session');
        if(!is_null($_locale)){
            $session->set('_locale', $_locale);
        } else {
            $session->set('_locale', 'es');
        }
        $em = $this->getDoctrine()->getManager();
        //$locales = $em->getRepository('AdminBundle:Country')->findAll();
        $locales = array('es','en','fr','ae','in','it','kr','pt','jp','ru','th','id');
        $session->set('locales', $locales);
        $affiliate = $session->get('affiliate');
        if (is_null($username) && isset($affiliate)) {
            return $this->redirect($this->generateUrl('affiliate_homepage', array('username' => $affiliate)));
        } elseif(is_null($username) && !isset($affiliate)){
            return $this->redirect($this->generateUrl('homepage'));
        }
        if (!is_null($username) && $username != 'page') {
            $user = $em->getRepository('AdminBundle:User')->findBy(array('username' => $username));
            if (!$user) {
                throw $this->createNotFoundException('Usuario de afiliado no válido');
            }
        }
        $session->set('affiliate', $username);
        $blocks = $em->getRepository('ContentManagementBundle:ContentBlock')->findBy(array('active' => true),
            array('position' => 'ASC'));
        $modules = $em->getRepository('AdminBundle:Module')->findToShow();
        return $this->render('FrontBundle:Homepage:homepage.html.twig', array(
            'blocks' => $blocks, 'modules' => $modules
        ));
    }
}

<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Entity\Option;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * Class HomeController
 * @package App\AdminBundle\Controller
 * @Route("/")
 *
 */
class HomeController extends Controller
{
    public static function UserOptions($context, $em, $user)
    {
        $sesion = $context->get('session');
        $options = $em->getRepository('AdminBundle:Option')->findAll(); //by default is admin [full access]
        if (!$context->isGranted('ROLE_ADMIN')) {
            $options = $user->getRole()->getOptions();
        }
        $option_list = array();
        foreach ($options as $option) {
            $option_list[] = $option;
        }
        foreach ($user->getUserModule() as $um) {
            $associated = false;
            $name = $um->getModule()->getName();
            $path = $um->getModule()->getOptionPath();
            foreach ($option_list as $option) {
                if ($option->getName() == $name) {
                    $associated = true;
                    break;
                }
            }
            if ($um->getModule()->getId() > 2 && !$associated) {
                $op = $em->getRepository('AdminBundle:Option')->findOneBy(array('name' => $name));
                if (!$op) {
                    $op = new Option();
                    $op->setName($name);
                    $op->setPath($path);
                    $op->setIcon('icon-folder-close');
                    $em->persist($op);
                    $em->flush();
                }
                $option_list[] = $op;
            }
        }
        $sesion->set('options_to_show', $option_list);
    }

    /**
     * @Route("/management", name="management")
     */
    public function indexAction()
    {
        $sesion = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AdminBundle:User')->findOneBy(array('username' => $this->getUser()->getUsername()));

        $userNotifications = array();
        foreach ($user->getNotifications() as $n) {
            $userNotifications[] = $n->getId();
        }
        $notificationsInLastWeek = $em->getRepository('AdminBundle:Notification')->findNotificationsInLastWeek($userNotifications);
        //update amount information in NotificationType
        foreach ($user->getNotificationTypes() as $nt) {
            $amount = 0;
            foreach ($notificationsInLastWeek as $lwn) {
                foreach ($lwn->getNotificationTypes() as $lwnt) { //2 always [warning, any]
                    if ($lwnt->getId() == $nt->getId()) {
                        $amount++;
                        $nt->setAmount($amount);
                    }
                }
            }
        }
        $em->flush();
        $sesion->set('associatedNotificationTypes', $user->getNotificationTypes());
        $sesion->set('notificationsInLastWeek', $notificationsInLastWeek);
        $sesion->set('modules', $em->getRepository('AdminBundle:Module')->findAll());
        $sesion->set('associatedModules', $user->getUserModule());
        $sesion->set('earning', $user->getBankAccount()->getMoney());
        $this->UserOptions($this, $em, $user);
        return $this->render('AdminBundle:Home:home.html.twig', array('entity' => $user));
    }

    /**
     * @Route("/management/get_sales", name="management_get_sales")
     * @Method("POST")
     */
    public function getSalesAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $daily_sales = $em->getRepository('AdminBundle:Transaction')->findDailyTransaction($id);
        $weekly_sales = $em->getRepository('AdminBundle:Transaction')->findWeeklyTransaction($id);
        $user = $em->getRepository('AdminBundle:User')->findOneBy(array('username' => $this->getUser()->getUsername()));
        $result = array('daily' => $daily_sales, 'weekly' => $weekly_sales, 'earning' => $user->getBankAccount()->getMoney());
        die(json_encode($result));
    }
}

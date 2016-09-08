<?php

namespace App\ContentManagementBundle\Controller;

use App\PclZip\PclZip;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\ContentManagementBundle\Entity\ContentBlock;
use Symfony\Component\HttpFoundation\Request;

/**
 * ContentBlock controller.
 *
 * @Route("/management/contentblock")
 */
class ContentBlockController extends Controller
{

    /**
     * Lists all ContentBlock entities.
     *
     * @Route("/", name="management_contentblock")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ContentManagementBundle:ContentBlock')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * return ContentBlock creation view
     *
     * @Route("/new", name="management_contentblock_new")
     */
    public function newAction()
    {
        return $this->render('ContentManagementBundle:ContentBlock:new.html.twig', array());

    }

    /**
     * create a ContentBlock
     *
     * @Route("/create", name="management_contentblock_create")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $name = $request->get('name');
        $position = $request->get('position');
        $active = $request->get('active');
        if ($name != '' && $position != '') {
            $image = $_FILES["image"]['name'];
            $ipath = 'bundles/admin/blocks/img/' . $image;
            if (copy($_FILES['image']['tmp_name'], $ipath)) {
                $file = $_FILES["file"]['name'];
                $temp = explode('.', $file);
                $type = $temp[count($temp) - 1];
                if ($type != 'zip') {
                    /*$session->set('error_actualizando', 'Fichero de actualización no válido');
                    return $this->redirect($this->generateUrl('admin_actualizar'));*/
                } else {
                    $bpath = 'bundles/admin/blocks/' . $file;
                    if (copy($_FILES['file']['tmp_name'], $bpath)) {
                        $archive = new PclZip($bpath); //Execute extract function
                        if (($list = $archive->listContent()) == 0) {
                            /*$session->set('error_actualizando', $archive->errorInfo(true));
                            return $this->redirect($this->generateUrl('admin_actualizar'));*/
                        }
                        $files = array();
                        for ($i = 0; $i < sizeof($list); $i++) {
                            $properties = array();
                            for (reset($list[$i]); $key = key($list[$i]); next($list[$i])) {
                                if ($key == 'filename' || $key == 'size') {
                                    $properties[] = $list[$i][$key];
                                }
                            }
                            $files[] = $properties;
                        }
                        /*$session->set('ficheros_actualizados', $files);*/
                        if ($archive->extract(PCLZIP_OPT_PATH, '../', PCLZIP_OPT_REMOVE_PATH, 'temp_install') == 0) {
                            /*$session->set('error_actualizando', $archive->errorInfo(true));*/
                        }

                        $blocks = $em->getRepository('ContentManagementBundle:ContentBlock')->createQueryBuilder('b')
                            ->select('b')
                            ->where('b.position >= :position')
                            ->andWhere('b.active = :active')
                            ->setParameters(array('position' => $position, 'active' => true))
                            ->getQuery()
                            ->getResult();

                        $flag = false;
                        foreach ($blocks as $b) {
                            if (intval($b->getPosition()) == intval($position)) {
                                $flag = true;
                            }
                        }

                        if ($flag) {
                            foreach ($blocks as $b) {
                                $b->setPosition(intval($b->getPosition()) + 1);
                                $em->persist($b);
                            }
                        }

                        $block = new ContentBlock();
                        $block->setName($name);
                        $block->setPosition(intval($position));
                        $block->setActive($active ? true : false);
                        $block->setFile($file);
                        $block->setImage($image);

                        $em->persist($block);
                        $em->flush();
                    }
                }
            }
        }
        return $this->redirect($this->generateUrl('management_contentblock_new'));
    }

    /**
     * update a ContentBlock position
     *
     * @Route("/update/entity/{id}/position/{p}", name="management_contentblock_position_update")
     */
    public function UpdatePositionAction($id = null, $p = null){
        $em = $this->getDoctrine()->getManager();
        $block = $em->getRepository('ContentManagementBundle:ContentBlock')->find($id);
        $blocks = $em->getRepository('ContentManagementBundle:ContentBlock')->createQueryBuilder('b')
            ->select('b')
            ->where('b.position >= :position')
            ->andWhere('b.active = :active')
            ->setParameters(array('position' => $p, 'active' => true))
            ->getQuery()
            ->getResult();

        $flag = false;
        foreach ($blocks as $b) {
            if (intval($b->getPosition()) == intval($p)) {
                $flag = true;
            }
        }

        if ($flag) {
            foreach ($blocks as $b) {
                $b->setPosition(intval($b->getPosition()) + 1);
                $em->persist($b);
            }
        }

        $block->setPosition(intval($p));
        $em->persist($block);
        $em->flush();
        return $this->redirect($this->generateUrl('management_contentblock'));
    }
}

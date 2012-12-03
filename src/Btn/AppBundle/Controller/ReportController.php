<?php

namespace Btn\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Btn\AppBundle\Controller\Controller as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Btn\AppBundle\Entity\Time;
use Btn\AppBundle\Form\TimeType;
use Btn\AppBundle\Form\TimeFilterType;

/**
 * @Route("/report")
 */
class ReportController extends BaseController
{
    public function preExecute()
    {
       $this->getRequest()->request->set('control_nav', 'report');
    }

    /**
     * @Route("/", name="report")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        //get time reports for current user
        $manager = $this->container->get('btn.time_manager')
            ->setNs('reports')
            ->createForm(new TimeFilterType())
            ->setQueryMethod('getReportQuery')
            ->enablePageSession()
            ->filter()
            ->paginate()
        ;

        //@TODO: refactor this one - lame code just for fast results of sum in view
       $query = $this->container->get('btn.time_manager')
            ->setNs('reports')
            ->createForm(new TimeFilterType())
            ->setQueryMethod('getReportQuery')
            ->enablePageSession()
            ->filter()
            ->setQuery()
            ->getQuery()
        ;
        $results = $query->getResult();
        $total = 0;
        foreach ($results as $i => $time) {
            $total += $time->getTime();
        }


        return array(
            'pagination' => $manager->getPagination(),
            'form'       => $manager->getForm()->createView(),
            'total'      => $total,
        );
    }

    /**
     * @Route("/download", name="download_report")
     */
    public function downloadAction(Request $request)
    {
       $query = $this->container->get('btn.time_manager')
            ->setNs('reports')
            ->createForm(new TimeFilterType())
            ->setQueryMethod('getReportQuery')
            ->enablePageSession()
            ->filter()
            ->setQuery()
            ->getQuery()
        ;

        $results = $query->getResult();

        $sep = '";"';
        $eol = '"';
        $csv_output = '';

        $csv_header = array('Date', 'Hours', 'Project', 'User', 'Description', 'Billable');

        $csv_output .= $eol.implode($sep, $csv_header).$eol ."\n";

        foreach ($results as $i => $time) {
            $csv_content = array(
                $time->getCreatedAt()->format('d-m-Y H:i'),
                $time->getTime(),
                $time->getProject(),
                $time->getUser(),
                $time->getDescription(),
                $time->getBillable() ? 'yes' : 'no',
            );
            $csv_output .= $eol. implode($sep, $csv_content) .$eol."\n";
        }

        //dump it!
        header("Content-Type: application/octet-stream");
        header('Content-Encoding: utf-8');
        header("Content-Disposition: attachment; filename=time_report_".date('d-m-Y').".csv");
        header("Content-Transfer-Encoding: binary ");
        header("Connection: Close");
        echo $csv_output;
        exit();
    }
}

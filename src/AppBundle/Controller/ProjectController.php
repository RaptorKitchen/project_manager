<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class ProjectController extends Controller
{
    /**
     * @Route("/projects/dashboard", name="dashboard")
     */
    public function showDashboard()
    {
        $projects = $this->getDoctrine()->getRepository('AppBundle:Project')->findAll();
        //dump($projects);
        if (!$projects) {
            throw $this->createNotFoundException(
                'No project found for project '.$projectId
            );
        }
        return $this->render(':projects:dashboard.html.twig', [
            'projects' => $projects
        ]);
    }
    /**
     * @Route("/projects/{projectId}")
     */
    public function showProject($projectId)
    {
         $project = $this->getDoctrine()->getRepository('AppBundle:Project')->find($projectId);
         dump($project);
        if (!$project) {
            throw $this->createNotFoundException(
                'No project found for project '.$projectId
            );
        }
         return $this->render(':projects:show.html.twig', ['project' => $project]);
    }

    /**
     * @Route("/projects/create/{name}", name="create")
     */
    public function createProject($name)
    {
        $em = $this->getDoctrine()->getManager();
        $project = new Project();
        $project->setName($name);
        $project->setStatus("incomplete");
        $project->setStatusId(3);
        $em->persist($project);
        $em->flush();
        return $this->redirectToRoute('dashboard');
    }
    /**
    /**
     * @Route("/projects/update/{id}/{statusId}", name="update")
     */
    public function updateProject($id,$statusId)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Project')->find($id);
        if(!$post) {
            throw $this->createNotFoundException('no matching record found.');
        }
        /** @var $post Project */
        $post->setStatusId($statusId);
        switch ($statusId) {
            case '3':
                $post->setStatus("Incomplete");
                break;
            case '2':
                $post->setStatus("In Progress");
                break;
            case '1':
                $post->setStatus("Complete");
                break;
            default:
                throw $this->createNotFoundException(
                    'Unknown status id: ' . $statusId
                );
        }

        $em->flush();
        return $this->redirectToRoute('dashboard');
    }
    /**
     * @Route("/projects/delete/{id}", name="delete")
     */
    public function deleteProject($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Project')->find($id);
        $em->remove($post);
        $em->flush();
        return $this->redirectToRoute('dashboard');
    }
}

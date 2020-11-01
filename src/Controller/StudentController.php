<?php

namespace App\Controller;

use App\Entity\Information;
use App\Form\StudentType;
use App\Repository\InformationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param InformationRepository $informationRepository
     * @return Response
     */
    public function index(InformationRepository $informationRepository): Response
    {
        $students = $informationRepository->findAll();

        return $this->render('index.html.twig', [
            'students' => $students
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $student = new Information();

        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            /**
             * @var UploadedFile $file
             */

            $file = $request->files->get('student')['photoName'];

            if($file){
                $filename = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
                $file->move($this->getParameter('uploads_dir'), $filename);

                $student->setPhoto($filename);
                $em->persist($student);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('index'));
        }
        return $this->render('create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/show/{id}", name="show")   *
     * @param $id
     * @param InformationRepository $informationRepository
     * @return Response
     */
    public function show($id, InformationRepository $informationRepository)
    {
        $student = $informationRepository->find($id);

        return $this->render('show.html.twig', [
            'student' => $student
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")     *
     * @param InformationRepository $informationRepository
     * @param $id
     * @return Response
     */
    public function edit(InformationRepository $informationRepository, $id, Request $request)
    {
        $student = $informationRepository->find($id);
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            /**
             * @var UploadedFile $file
             */

            $file = $request->files->get('student')['photoName'];

            if($file){
                $filename = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
                $file->move($this->getParameter('uploads_dir'), $filename);

                $student->setPhoto($filename);
            }
            $em->persist($student);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        return $this->render('edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")     *
     * @param $id
     * @param InformationRepository $informationRepository
     * @return RedirectResponse
     */
    public function delete($id, InformationRepository $informationRepository)
    {
        $student = $informationRepository->find($id);
        unlink($this->getParameter('uploads_dir') . '/' . $student->getPhoto());
        $em = $this->getDoctrine()->getManager();
        $em->remove($student);
        $em->flush();
        $this->addFlash('success', 'Record removed!');

        return $this->redirect($this->generateUrl('index'));
    }
}

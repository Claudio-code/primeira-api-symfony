<?php

namespace App\Controller;

use App\Entity\Course;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/course", name="course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $course = $this
            ->getDoctrine()
            ->getRepository(Course::class)
            ->findAll()
        ;

        return $this->json([
            'data' => $course
        ]);
    }

    /**
     * @Route("/{id}", name="course_show", methods={"GET"})
     */
    public function show(Course $course): JsonResponse
    {
        return $this->json($course);
    }

    /**
     * @Route("/new", name="course_create", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $data = $request->request->all();
        $course = new Course();
        $course
            ->setName($data["name"])
            ->setDescription($data["description"])
            ->setSlug($data["slug"])
            ->setCreatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")))
            ->setUpdatedAt(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")))
        ;

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($course);
        $doctrine->flush();

        return $this->json([ "newCourse" => $course ]);
    }

    /**
     * @Route("/{id}", name="course_update", methods={"PUT"})
     */
    public function update(Request $request, Course $course): JsonResponse
    {
        $data = $request->request->all();

        if ($request->request->has("name"))
            $course->setName($data["name"]);

        if ($request->request->has("description"))
            $course->setDescription($data["description"]);

        if ($request->request->has("slug"))
            $course->setSlug($data["slug"]);

        $course->setUpdatedAt(
            new \DateTime("now", new \DateTimeZone("America/Sao_Paulo"))
        );

        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->flush();

        return $this->json($course);
    }

    /**
     * @Route("/{id}", name="course_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Course $course): JsonResponse
    {
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->remove($course);
        $doctrine->flush();

        return $this->json([ "removed" => true ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\Persona1Type;
use App\Repository\PersonaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

# Clase controladora del flujo dentro de la aplicaciones

#[Route('/personas')]
class PersonasController extends AbstractController
{
    # Metodo empleado para la visualizacion de los datos en primera entancia
    #[Route('/', name: 'app_personas_index', methods: ['GET'])]
    public function index(PersonaRepository $personaRepository): Response
    {
        return $this->render('personas/index.html.twig', [
            'personas' => $personaRepository->findAll(),
        ]);
    }

    # Metodo la renderizacion del la vista para el registro de nuestos usuarios, creacion del 
    # formulario (Persona1Type) y validacion de los datos enviados
    #[Route('/new', name: 'app_personas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PersonaRepository $personaRepository): Response
    {
        $persona = new Persona();
        $form = $this->createForm(Persona1Type::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personaRepository->add($persona, true);

            return $this->redirectToRoute('app_personas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personas/new.html.twig', [
            'persona' => $persona,
            'form' => $form,
        ]);
    }

    # Metodo para la visualizacion detalla de los datos de las personas
    # Se para por parametro el ID de la persona
    #[Route('/{id}', name: 'app_personas_show', methods: ['GET'])]
    public function show(Persona $persona): Response
    {
        return $this->render('personas/show.html.twig', [
            'persona' => $persona,
        ]);
    }

    # Metodo para la modificacion de la informacion, pasando por paramentro el ID de la persona
    #[Route('/{id}/edit', name: 'app_personas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Persona $persona, PersonaRepository $personaRepository): Response
    {
        $form = $this->createForm(Persona1Type::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personaRepository->add($persona, true);

            return $this->redirectToRoute('app_personas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personas/edit.html.twig', [
            'persona' => $persona,
            'form' => $form,
        ]);
    }

    #Metodo empleado para la eliminacion de la informacion de las personas
    #[Route('/{id}', name: 'app_personas_delete', methods: ['POST'])]
    public function delete(Request $request, Persona $persona, PersonaRepository $personaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $persona->getId(), $request->request->get('_token'))) {
            $personaRepository->remove($persona, true);
        }

        return $this->redirectToRoute('app_personas_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\FirstEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\FirstEntityType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/lucky/number/{max}", name="lucky_number", defaults={"max":100}, requirements={"max":"\d*"})
    
     */
    public function numberAction($max)
    {
        // génération d'un nombre aléatoire
        
        $number = mt_rand(0, $max);
        //ici on va chercher le template et on lui transmet la variable
        return $this->render('AppBundle:Default:number.html.twig', array(
            // pour fournir des variables au template
            // a gauche, le nom qui sera utilisé dans le template
            // a droite, la valeur
            'number' => $number
        ));
    }
    
    
    /**
    *@Route("/blog/{title}/{year}",
    * name="autreblog", 
     * defaults={"_locale":"fr"},
     * requirements={ 
     *    "year":"[0-9]{4}", 
     *    "title":"[a-z]+"}
     *)
     * @Route("/blog/{_locale}/{title}/{year}", 
     * name="blog", 
     * defaults={"_locale":"fr"},
     * requirements={
     *    "_locale":"en|fr", 
     *    "year":"\d{4}", 
     *    "title":"\w+"}
     *)

      */
     public function textAction($title, $year, $_locale)
     {  
        return $this->render('AppBundle:Default:blog.html.twig', array(
            'title' => $title,
            'year' => $year,
            '_locale' => $_locale,
        ));
     }
     // [...]
      /**
     * @Route("/layout", name="layout")
     */
    public function layout()
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle::layout.html.twig', array(

        ));
    }
     /**
     * @Route("/show", name="show")
     */
     public function listArticleAction()
     {
        $database = $this->getDoctrine()->getManager()->getRepository('AppBundle:FirstEntity'::class);
        $tables = $database->findAll(); 
        return $this->render('AppBundle::firstentity.html.twig', array(
            'tables'=>$tables
         ));
     }

     /**
     * @Route("/showone/{id}", name="showone")
     */
     public function showArticleAction($id)
     {
        $database = $this->getDoctrine()->getManager()->getRepository('AppBundle:FirstEntity'::class);
        $numero = $database->findOneBy(array('id' => $id));
        
        $form_delete = $this->createDeleteForm($numero);

         return $this->render('AppBundle::firstentityid.html.twig', array(
            'numero'=>$numero,
            'form_delete' => $form_delete->createView()
         ));
     }
    /**
    * @Route("myentity/new", name="formulaire")
    */
    public function createFormAction(Request $request) 
    {
    $firstEntity = new FirstEntity();
    $form = $this->createForm(FirstEntityType::class, $firstEntity);      
    /*$firstEntity = new FirstEntity();
    $form = $this->createFormBuilder($firstEntity)
    ->add('title', TextType::class)
    ->add('content', TextType::class)
    ->add('created_at', DateType::class)
    ->add('updated_at', DateType::class)
    ->add('is_enabled', IntegerType::class)
    ->add('likes', IntegerType::class)
    ->add('save', SubmitType::class, array('label' => 'Create Post'))
    ->getForm();
*/
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $article = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
    
        return $this->redirectToRoute('showone', array('id' => $article->getId()));
    }

    return $this->render('AppBundle::formulairenew.html.twig', array(
       'form' => $form->createView(),
    ));
}
 /**
    * @Route("myentity/update/{id}", name="update")
    */
    public function updateArticleAction($id, Request $request){
        $database = $this->getDoctrine()->getManager()->getRepository('AppBundle:FirstEntity'::class);
        $numero = $database->findOneBy(array('id' => $id));
        $form = $this->createForm(FirstEntityType::class, $numero); 
        
    $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('showone', array('id' => $article->getId()));
        }
        return $this->render('AppBundle::formulairenew.html.twig', array(
            'form' => $form->createView(),
         ));
    }
     /**
     * Crée un formulaire pour supprimer un Article
     */
     private function createDeleteForm(FirstEntity $firstEntity)
     {
         //on crée un formulaire
         return $this->createFormBuilder()
             ->setAction($this->generateUrl('article_delete', array('id' => $firstEntity->getId())))
             ->setMethod('DELETE')
             ->add('delete', SubmitType::class)
             ->getForm()
         ;
     }
     /**
    * @Route("myentity/delete/{id}", name="article_delete")
    */
    public function deleteArticleAction($id, Request $request){
        $database = $this->getDoctrine()->getManager()->getRepository('AppBundle:FirstEntity'::class);
        $numero = $database->findOneBy(array('id' => $id));
        $form = $this->createDeleteForm($numero);  
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->remove($numero);
          $em->flush();
}
return $this->redirectToRoute('show'); }
//
    }
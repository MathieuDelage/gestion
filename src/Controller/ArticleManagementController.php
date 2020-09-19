<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Stock;
use App\Entity\Warehouse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleManagementController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/admin", name="menu")
     */
    public function menu()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/article/add", name="add_article")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function addArticle(Request $request, EntityManagerInterface $entityManager)
    {
        $content = $request->request->all();
        if ($content){
            if (!empty($content['reference']) && !empty($content['name']) && !empty($content['price'])){
                if (preg_match('/[0-9]/', $content['reference']) ){
                    if(preg_match('/[0-9,]/', $content['price']) ){
                        $repo = $this->getDoctrine()->getRepository(Article::class);
                        $articleRef = $repo->findOneBy([ 'reference' => $content['reference'] ]);
                        $articleName = $repo->findOneBy([ 'name' => $content['name'] ]);
                        if($articleRef){
                            return $this->json([ "message" => "La référence existe déjà !"], 200);
                        } else {
                            if($articleName){
                                return $this->json([ "message" => "Le nom existe déjà !"], 200);
                            } else {
                                $article = new Article();
                                $article->setReference($content['reference'])
                                    ->setName($content['name'])
                                    ->setPrice($content['price']);
                                $entityManager->persist($article);
                                $entityManager->flush();
                                return $this->json([ "message" => "Article ajouté avec succès !"], 200);
                            }
                        }
                    }else {
                        return $this->json([ "message" => "Le champs 'Prix de l'article' ne contient pas un nombre décimal ! "], 200);
                    }
                } else {
                    return $this->json([ "message" => "Le champs 'Référence de l'article' ne contient pas un entier ! "], 200);
                }
            }else {
                return $this->json([ "message" => "Tous les champs n'ont pas été remplis !"], 200);
            }
        }
        return $this->render('article/add_article.html.twig', [
        ]);
    }

    /**
     * @Route("/article/get_article", name="get_article")
     */
    public function getArticle()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        return $this->render('article/get_article.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article/get_article_ref", name="get_article_ref")
     * @param Request $request
     * @return Response
     */
    public function getArticleByRef(Request $request)
    {
        $content = $request->query->all();
        if($content){
            if (!empty($content['reference'])){
                if (preg_match('/[0-9]/', $content['reference']) ){
                    $repo = $this->getDoctrine()->getRepository(Article::class);
                    $article = $repo->findOneBy([ 'reference' => $content['reference'] ]);
                    if(!$article){
                        return $this->json([ "message" => "Cette référence n'existe pas !"], 200);
                    } else {
                        return $this->json([
                            'reference' => $article->getReference(),
                            'name' => $article->getName(),
                            'price' => $article->getPrice(),
                        ]);
                    }
                } else {
                return $this->json([ "message" => "Le champs 'Référence de l'article' ne contient pas un entier ! "], 200);
                }
            }else {
                return $this->json([ "message" => "Vous n'avez pas entré de référence !"], 200);
            }
        }
        return $this->render('article/get_article_ref.html.twig');
    }

    /**
     * @Route("/article/edit", name="edit_article")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function editArticle(Request $request, EntityManagerInterface $entityManager)
    {
        $content = $request->request->all();
        if ($content){
            if (!empty($content['reference']) && !empty($content['name']) && !empty($content['price'])){
                if(preg_match('/[0-9,]/', $content['price']) ) {
                    $repo = $this->getDoctrine()->getRepository(Article::class);
                    $articleTest = $repo->findOneBy(['name' => $content['name']]);
                    if (!$articleTest) {
                        $repo = $this->getDoctrine()->getRepository(Article::class);
                        $article = $repo->findOneBy(['reference' => $content['reference']]);
                        $article->setName($content['name'])
                            ->setPrice($content['price']);
                        $entityManager->persist($article);
                        $entityManager->flush();
                    }else {
                        return $this->json([ "message" => "Ce nom d'article existe déjà !"], 200);
                    }
                    return $this->json([ "message" => "Article modifié avec succès !"], 200);
                } else {
                    return $this->json([ "message" => "Le prix entré n'est pas un nombre décimal !"], 200);
                }
            }else {
                return $this->json([ "message" => "Tous les champs n'ont pas été remplis !"], 200);
            }
        }
        return $this->json([ "message" => "Requête vide !"], 204);
    }

    /**
     * @Route("/article/get_article_name", name="get_article_name")
     * @param Request $request
     * @return Response
     */
    public function getArticleByName(Request $request)
    {
        $content = $request->query->all();
        if($content){
            if (!empty($content['name'])){
                $repo = $this->getDoctrine()->getRepository(Article::class);
                $article = $repo->findOneBy([ 'name' => $content['name'] ]);
                if (!$article || empty($article)){
                    return $this->json([ "message" => "Ce nom d'article n'existe pas ! "], 200);
                }else {
                    return $this->json([
                        'reference' => $article->getReference(),
                        'name' => $article->getName(),
                        'price' => $article->getPrice(),
                    ]);
                }
            }else {
                return $this->json([ "message" => "Vous n'avez pas entré de nom !"], 200);
            }
        }
        return $this->render('article/get_article_name.html.twig');
    }

    /**
     * @Route("/article/get_article_interval", name="get_article_interval")
     * @param Request $request
     * @return Response
     */
    public function getArticleByInterval(Request $request)
    {
        $content = $request->query->all();
        if($content){
            if (!empty($content['min']) && !empty($content['max'])){
                if(preg_match('/[0-9,]/', $content['min']) ){
                    if(preg_match('/[0-9,]/', $content['max']) ){
                        $repo = $this->getDoctrine()->getRepository(Article::class);
                        $tmp = $repo->findAll();
                        for ($i = 0; $i < count($tmp); $i++){
                            if ( $tmp[$i]->getPrice() >= $content['min'] && $tmp[$i]->getPrice() <= $content['max']){
                                $articles[] = $tmp[$i];
                            }
                        }
                        if (!$articles){
                            return $this->json([ "message" => "Aucun article n'a été trouvé dans cet interval"], 200);
                        }else {
                            return $this->json($articles,200);
                        }
                    } else {
                        return $this->json([ "message" => "Le prix maximum entré n'est pas un nombre décimal !"], 200);
                    }
                } else {
                    return $this->json([ "message" => "Le prix minimum entré n'est pas un nombre décimal !"], 200);
                }
            }else {
                return $this->json([ "message" => "Vous n'avez pas pas rempli tous les champs !"], 200);
            }
        }
        return $this->render('article/get_article_interval.html.twig');
    }

    /**
     * @Route("/article/get_warehouse", name="get_warehouse")
     * @return JsonResponse
     */
    public function getWarehouse()
    {
        $repo = $this->getDoctrine()->getRepository(Warehouse::class);
        $warehouses = $repo->findAll();
        return $this->json($warehouses, 200);
    }

    /**
     * @Route("/article/add_article_stock", name="add_article_article")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function addToStock(Request $request, EntityManagerInterface $entityManager)
    {
        $content = $request->request->all();
        if($content){
            if (!empty($content['reference']) && !empty($content['warehouse']) && !empty($content['amount'])){
                if (preg_match('/[0-9,]/', $content['amount'])){
                    if (preg_match("/[a-zA-Z'\-\b]/", $content['warehouse'])){
                            $repoWarehouse = $this->getDoctrine()->getRepository(Warehouse::class);
                            $warehouse = $repoWarehouse->findOneBy([ 'name' => $content['warehouse'] ]);
                            if ($warehouse){
                                $repoArticle = $this->getDoctrine()->getRepository(Article::class);
                                $article = $repoArticle->findOneBy([ 'reference' => $content['reference'] ]);
                                $repoStock = $this->getDoctrine()->getRepository(Stock::class);
                                $stock = $repoStock->findOneBy( [
                                    'article' => $article,
                                    'warehouse' => $warehouse
                                ]);
                                if (!$stock){
                                    $stock = new Stock;
                                    $stock->setArticle($article)
                                        ->setWarehouse($warehouse)
                                        ->setAmount($content['amount']);
                                    $entityManager->persist($stock);
                                    $entityManager->flush();
                                    return $this->json([ "message" => "Le stockage de l'article à bien été fait ! "], 200);
                                }else {
                                    return $this->json([ "message" => "Cet article est déjà dans l'entrepôt concerné !"], 200);
                                }
                            } else {
                                return $this->json([ "message" => "Cet entrepôt n'existe pas !"], 200);
                            }
                    } else {
                        return $this->json([ "message" => "Le nom d'entrepôt entré n'est pas valide !"], 200);
                    }
                }else {
                    return $this->json([ "message" => "La quantité entrée n'est pas un entier !"], 200);
                }
            } else {
            return $this->json([ "message" => "Vous n'avez pas pas rempli tous les champs !"], 200);
            }
        }
        return $this->json([ "message" => "Requête vide !"], 204);
    }
}

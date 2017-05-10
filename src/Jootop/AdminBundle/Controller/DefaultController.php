<?php

namespace Jootop\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Jootop\AdminBundle\Entity\Product;

class DefaultController extends Controller
{
    /**
     * @Route("admin/dashboard/index")
     */
    public function indexAction()
    {
        return $this->render('JootopAdminBundle:Default:index.html.twig');
    }

    /**
     * @Route("admin/product/create")
     */
    public function createAction()
    {
        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(19.99);
        $product->setDescription('Ergonomic and stylish!');
     
        $em = $this->getDoctrine()->getManager();
     
        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        // 告诉Doctrine你希望（最终）存储Product对象（还没有语句执行）
        $em->persist($product);
     
        // actually executes the queries (i.e. the INSERT query)
        // 真正执行语句（如，INSERT 查询）
        $em->flush();
     
        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * @Route("admin/product/show/{productId}")
     */
    public function showAction($productId)
    {

        /**
        $product = $this->getDoctrine()
            ->getRepository('JootopAdminBundle:Product')
            ->find($productId);
        
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }
        */

        /* ORM */
        $repository = $this->getDoctrine()->getRepository('JootopAdminBundle:Product');
         
        // query for a single product by its primary key (usually "id")
        // 通过主键（通常是id）查询一件产品
        $product = $repository->find($productId);

        // dynamic method names to find a single product based on a column value
        // 动态方法名称，基于字段的值来找到一件产品
        $product = $repository->findOneById($productId);
        $product = $repository->findOneByName('Keyboard');
         
        // dynamic method names to find a group of products based on a column value
        // 动态方法名称，基于字段值来找出一组产品
        $products = $repository->findByPrice(19.99);
         
        // find *all* products / 查出 *全部* 产品
        $products = $repository->findAll();
        
        // query for a single product matching the given name and price
        // 查询一件产品，要匹配给定的名称和价格
        $product = $repository->findOneBy(
            array('name' => 'Keyboard', 'price' => 19.99)
        );

        // query for multiple products matching the given name, ordered by price
        // 查询多件产品，要匹配给定的名称和价格
        $products = $repository->findBy(
            array('name' => 'Keyboard'),
            array('price' => 'ASC')
        );

        /* ORM End */

        /* Query Builder */
        $repository = $this->getDoctrine()
            ->getRepository('JootopAdminBundle:Product');
         
        // createQueryBuilder() automatically selects FROM AppBundle:Product
        // and aliases it to "p"
        // createQueryBuilder() 自动从 AppBundle:Product 进行 select 并赋予 p 假名
        $query = $repository->createQueryBuilder('p')
            ->where('p.price > :price')
            ->setParameter('price', '19.99')
            ->orderBy('p.price', 'ASC')
            ->getQuery();
        
        // Many Result 
        $products = $query->getResult();

        // To get just one result
        $product = $query->setMaxResults(1)->getOneOrNullResult();

        /* Query Builder End */

        /* DB SQL */
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM JootopAdminBundle:Product p
            WHERE p.price > :price
            ORDER BY p.price ASC'
        )->setParameter('price', 19.99);
         
        // $products = $query->getResult();// getSingleResult
        $products = $query->setMaxResults(1)->getOneOrNullResult();
        
        /* DB SQL End */
        return new Response('Current product with id '.$product->getId());
    }

    /**
     * @Route("admin/product/update/{productId}")
     */
    public function updateAction($productId)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('JootopAdminBundle:Product')->find($productId);
     
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$productId
            );
        }
     
        $product->setName('New product name!');
        // $em->remove($product);
        $em->flush();
     
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("admin/product/new")
     */
    public function newAction(Request $request)
    {
        // Create a product and give it some dummy data for this example
        $product = new Product();
        $product->setName('New Product Name 2');
        $product->setPrice('88.88');
        $product->setDescription('Hello World');
        
        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class)
            ->add('price', MoneyType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Product'))
            ->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
        
            // $form->getData() holds the submitted values
            // But, the original `$task` variable has also been updated
            $product = $form->getData();
        
            // Perform some action, such as saving the task to the database
            // For example, if Task is a Doctrine entity, save it!
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $logger = $this->get('logger');
            $logger->info('I just got the logger');
        
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('default/new.html.twig', array(
            'form' => $form->createView()
        ));
    }
}

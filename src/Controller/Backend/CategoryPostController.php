<?php

namespace App\Controller\Backend;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Category;
use App\Form\TaxonomyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route({"pl": "/panel/blog/kategorie"})
 * @IsGranted("ROLE_EDITOR")
 */
class CategoryPostController extends AbstractController
{
    /**
     * @var int
     */
    private $defaultItemPage;

    public function __construct(int $defaultItemPage)
    {
        $this->defaultItemPage = $defaultItemPage;
    }

    /**
     * List of Categories.
     *
     * @Route(
     *      "/{page}",
     *      name="panel_categories",
     *      requirements={"page"="\d+"},
     *      defaults={"page"=1}
     * )
     *
     * @param PaginatorInterface $paginator
     * @param int                $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categories(PaginatorInterface $paginator, int $page): Response
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $queryParams = [
            'orderBy' => 't.name',
            'orderDir' => 'ASC',
        ];
        $qb = $categoryRepository->getQueryBuilder($queryParams);
        $categories = $paginator->paginate($qb, $page, $this->defaultItemPage);

        return $this->render('backend/blog/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Add and Edit page Category.
     *
     * @Route(
     *      {"pl": "/kategoria/{slug}"},
     *      name="panel_category",
     *      defaults={"slug"=NULL}
     * )
     *
     * @param Request             $request
     * @param string|null         $slug
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function category(Request $request, string $slug = null, TranslatorInterface $translator): Response
    {
        if (null === $slug) {
            $category = new Category();
            $newCategoryForm = true;
        } else {
            $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['slug' => $slug]);
        }

        if (!$category) {
            throw new Exception('Invalid category slug');
        }

        $form = $this->createForm(TaxonomyType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', $translator->trans('Changes have been saved'));

            return $this->redirectToRoute('panel_category', ['slug' => $category->getSlug()]);
        } elseif ($form->isSubmitted() && false === $form->isValid()) {
            $this->addFlash('error', $translator->trans('Corrects form'));
        }

        return $this->render('backend/blog/category.html.twig', [
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    /**
     * @Route(
     *      {"pl": "/usun/{slug}/{token}"},
     *      name="panel_category_delete",
     * )
     *
     * @param string              $slug
     * @param string              $token
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(string $slug, string $token, TranslatorInterface $translator): Response
    {
        if (!$this->isCsrfTokenValid('delete-item', $token)) {
            throw new Exception($translator->trans('Incorrect share token'));
        }

        $category = $this->getDoctrine()->getRepository(Category::class)->findoneBy(['slug' => $slug]);

        if (!$category) {
            throw new Exception($translator->trans('Incorrect category slug'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $category = $this->getDoctrine()->getRepository(Category::class)->findoneBy(['slug' => $slug]);

        if ($category) {
            throw new Exception($translator->trans('An error occured. The object could not be deleted'));
        }
        $this->addFlash('success', $translator->trans('Correctly removed').' '.$translator->trans('Category'));

        return $this->redirectToRoute('panel_categories');
    }
}

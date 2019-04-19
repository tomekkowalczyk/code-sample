<?php

namespace App\Controller\Backend;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Tag;
use App\Form\TaxonomyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/panel/blog/tagi")
 * @IsGranted("ROLE_EDITOR")
 */
class TagPostController extends AbstractController
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
     * List of Tags.
     *
     * @Route(
     *      "/{page}",
     *      name="panel_tags",
     *      requirements={"page"="\d+"},
     *      defaults={"page"=1}
     * )
     *
     * @param PaginatorInterface $paginator
     * @param int                $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tags(PaginatorInterface $paginator, int $page): Response
    {
        $tagRepository = $this->getDoctrine()->getRepository(Tag::class);
        $queryParams = [
                'orderBy' => 't.name',
                'orderDir' => 'ASC',
            ];
        $qb = $tagRepository->getQueryBuilder($queryParams);
        $tags = $paginator->paginate($qb, $page, $this->defaultItemPage);

        return $this->render('backend/blog/tags.html.twig', [
            'tags' => $tags,
         ]);
    }

    /**
     * Add and Edit page Tag.
     *
     * @Route(
     *      "/tag/{slug}",
     *      name="panel_tag",
     *      defaults={"slug"=NULL}
     * )
     *
     * @param Request             $request
     * @param string|null         $slug
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function tag(Request $request, string $slug = null, TranslatorInterface $translator): Response
    {
        if (null === $slug) {
            $tag = new Tag();
        } else {
            $tag = $this->getDoctrine()->getRepository(Tag::class)->findOneBy(['slug' => $slug]);
        }

        if (!$tag) {
            throw new Exception($translator->trans('Invalid tag slug'));
        }

        $form = $this->createForm(TaxonomyType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', $translator->trans('Changes have been saved'));

            return $this->redirectToRoute('panel_tag', ['slug' => $tag->getSlug()]);
        } elseif ($form->isSubmitted() && false === $form->isValid()) {
            $this->addFlash('error', $translator->trans('Corrects form'));
        }

        return $this->render('backend/blog/tag.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }

    /**
     * @Route(
     *      "/usun/{slug}/{token}",
     *      name="panel_tag_delete",
     * )
     *
     * @param string $slug
     * @param string $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(string $slug, string $token, TranslatorInterface $translator): Response
    {
        if (!$this->isCsrfTokenValid('delete-item', $token)) {
            throw new Exception($translator->trans('Incorrect share token'));
        }

        $tag = $this->getDoctrine()->getRepository(Tag::class)->findOneBy(['slug' => $slug]);
        if (!$tag) {
            throw new Exception($translator->trans('Invalid tag slug'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($tag);
        $em->flush();
        $tag = $this->getDoctrine()->getRepository(Tag::class)->findOneBy(['slug' => $slug]);

        if ($tag) {
            throw new Exception($translator->trans('An error occured. The object could not be deleted'));
        }
        $this->addFlash('success', $translator->trans('Correctly removed').' '.$translator->trans('Tag'));

        return $this->redirectToRoute('panel_tags');
    }
}

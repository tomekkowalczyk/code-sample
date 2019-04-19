<?php

namespace App\Controller\Backend;

use DateTime;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use App\Entity\Post;
use App\Form\PostType;
use App\Form\SearchPostType;
use App\DTO\SearchPost;

/**
 * @Route({"pl": "/panel/blog"})
 * @IsGranted("ROLE_EDITOR")
 */
class PostController extends AbstractController
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
     * List of articles and a simple search engine after the category, status, tag and search field
     * after title and introductionContent.
     *
     * @Route(
     *      "/{page}",
     *      name="panel_posts",
     *      requirements={"page"="\d+"},
     *      defaults={"page"=1}
     * )
     *
     * @param Request            $request
     * @param PaginatorInterface $paginator
     * @param int                $page
     *
     * @return Response
     */
    public function posts(Request $request, PaginatorInterface $paginator, int $page): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $searchPost = new SearchPost();

        $form = $this->createForm(SearchPostType::class, $searchPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $queryParams = [
                'search' => $searchPost->search,
                'category' => $searchPost->category,
                'status' => $searchPost->status,
                'tags' => $searchPost->tags,
                'orderBy' => 'post.publishedDate',
                'orderDir' => 'DESC',
            ];
            $qb = $postRepository->getQueryBuilder($queryParams);
            $posts = $paginator->paginate($qb, $page, $this->defaultItemPage);
        } else {
            $qb = $postRepository->getQueryBuilder(['orderBy' => 'post.publishedDate', 'orderDir' => 'DESC']);
            $posts = $paginator->paginate($qb, $page, $this->defaultItemPage);
        }

        return $this->render('backend/blog/posts.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
         ]);
    }

    /**
     * Add and Edit page Post.
     *
     * @Route(
     *      {"pl": "/artykul/{slug}"},
     *      name="panel_post",
     *      defaults={"slug"=NULL}
     * )
     *
     * @param Request             $request
     * @param string|null         $slug
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \Exception
     */
    public function post(Request $request, string $slug = null, TranslatorInterface $translator): Response
    {
        if (null === $slug) {
            $post = new Post();
            $post->setCreateDate(new DateTime());
        } else {
            $post = $this->getDoctrine()->getRepository(Post::class)->findOneBy(['slug' => $slug]);
            if (!$post) {
                throw new NotFoundHttpException($translator->trans('Incorrect slug post'));
            }
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($post->getSlug())) {
                $post->setSlug($post->getTitle());
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', $translator->trans('Changes have been saved'));

            return $this->redirectToRoute('panel_posts');
        } elseif ($form->isSubmitted() && false === $form->isValid()) {
            $this->addFlash('danger', $translator->trans('Corrects form'));
        }

        return $this->render('backend/blog/post.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * Delete post.
     *
     * @Route(
     *      {"pl": "/usun/{slug}/{token}"},
     *      name="panel_post_delete",
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
        try {
            if (!$this->isCsrfTokenValid('delete-item', $token)) {
                throw new Exception($translator->trans('Incorrect share token'));
            }
            $post = $this->getDoctrine()->getRepository(Post::class)->findOneBy(['slug' => $slug]);

            if (!$post) {
                throw new Exception($translator->trans('Invalid slug post'));
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
            $post = $this->getDoctrine()->getRepository(Post::class)->findOneBy(['slug' => $slug]);

            if ($post) {
                throw new Exception($translator->trans('An error occured. The object could not be deleted'));
            }
            $this->addFlash('success', $translator->trans('Correctly removed').' '.$translator->trans('Article'));

            return $this->redirectToRoute('panel_posts');
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('panel_posts');
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}

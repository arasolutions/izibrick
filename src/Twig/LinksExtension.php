<?php

namespace App\Twig;

use App\Entity\Page;
use App\Entity\Post;
use App\Helper\StringHelper;
use App\Repository\PageRepository;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LinksExtension extends AbstractExtension
{
    private $router;

    /** @var PageRepository $pageRepository */
    private $pageRepository;

    /** @var PostRepository $postRepository */
    private $postRepository;

    public function __construct(UrlGeneratorInterface $router, PageRepository $pageRepository, PostRepository $postRepository)
    {
        $this->router = $router;
        $this->pageRepository = $pageRepository;
        $this->postRepository = $postRepository;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('links', [$this, 'generateLinks']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('generateLinks', [$this, 'generateLinks']),
        ];
    }

    public function generateLinks($value, $siteId, $internalName, $requestUri)
    {
        preg_match_all("/iziButton:\/\//", $value, $matches, PREG_OFFSET_CAPTURE);
        if (!empty($matches[0])) {
            foreach (array_reverse($matches[0]) as $match) {
                $beforeLink = substr($value, 0, $match[1]);
                $link = substr($value, $match[1]);

                preg_match("/\"/", $link, $matchEnd, PREG_OFFSET_CAPTURE);
                $afterLink = substr($link, $matchEnd[0][1]);
                $link = substr($link, 0, $matchEnd[0][1]);

                // Lecture des infos du lien
                preg_match("/iziButton:\/\/(.*)\?id=(\d*)((\&|\&amp\;)postId=(\d*))?/", $link, $infos, PREG_OFFSET_CAPTURE);
                $type = $infos[1][0];

                /** @var Page $page */
                $page = null;
                if ($infos[2]) {
                    $pageId = $infos[2][0];
                    $page = $this->pageRepository->getBySiteAndId($siteId, $pageId);
                }
                /** @var Post $post */
                $post = null;
                $postId = null;
                if (isset($infos[5])) {
                    $postId = $infos[5][0];
                    $post = $this->postRepository->find($postId);
                }

                $isRec = preg_match("/\/site\//", $requestUri);
                if ($isRec) {
                    if ($post) {
                        $value = $beforeLink . $this->router->generate('site_' . $type, ['siteName' => $internalName, 'name' => $page->getNameMenuUrl(),
                                'post' => $postId, 'postTitle' => StringHelper::cleanUrl($post->getTitle())]) . $afterLink;
                    } else {
                        $value = $beforeLink . $this->router->generate('site_' . $type, ['siteName' => $internalName, 'name' => $page->getNameMenuUrl()]) . $afterLink;
                    }
                } else {
                    if ($post) {
                        $value = $beforeLink . $this->router->generate($type, ['name' => $page->getNameMenuUrl(),
                                'post' => $postId, 'postTitle' => StringHelper::cleanUrl($post->getTitle())]) . $afterLink;
                    } else {
                        $value = $beforeLink . $this->router->generate($type, ['siteName' => $internalName, 'name' => $page->getNameMenuUrl()]) . $afterLink;
                    }
                }

            }
        }
        return $value;
    }
}

<?php 

namespace App\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use App\MdExt\CombinedExtensions;

class TwigExtension extends AbstractExtension
{
    private $combinedExtensions;
    
    public function __construct(CombinedExtensions $combinedExtensions)
    {
        $this->combinedExtensions = $combinedExtensions;
    }
    public function getFilters()
    {
        return [
            new TwigFilter('markdown', [$this, 'parseMarkdown'], ['is_safe' => ['html']])
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('is_active', [$this, 'isActive']),
        ];
    }

    public function isActive($paths): bool
    {
        $uri = str_replace('/admin', '', $_SERVER['REQUEST_URI']);
        $uri = $uri === '' ? '/' : $uri;
        if ($uri === '/') {
            return $paths === '/';
        }

        if (is_array($paths)) {
            foreach ($paths as $path) {
                if (strpos($uri, $path) === 0) {
                    return true;
                }
            }
            return false;
        }
        return strpos($uri, $paths) === 0 && $paths !== '/';
    }

    public function parseMarkdown($markdown)
    {
        return $this->combinedExtensions->text($markdown);
    }
}
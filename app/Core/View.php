<?php

namespace App\Core;

class view
{
    protected $file;
    protected $data;
    protected $cacheFile;
    protected $basePath;
    protected $cachePath;

    public function __construct($file, $data = [])
    {
        $this->basePath = app()->getBasePath() . '/app/views/';
        $this->cachePath = app()->getBasePath() . '/cache/';
        $this->file = $file;
        $this->data = $data;

        $file = $this->basePath . $this->file . '.html';
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $this->parse($content);
        } else {
            throw new \Exception('View ' . $file . ' not found!');
        }
    }

    protected function parse($content)
    {
        // single variables
        $content = preg_replace('/{{\s+?(.*)\s+?}}/', '<?php echo htmlentities(\1) ?>', $content);
        // print raw content
        $content = preg_replace('/{{{\s+?(.*)\s+?}}}/', '<?php echo \1 ?>', $content);

        // @TODO include sub view
        /*
        $content = preg_replace('/\@include\s+?\((.*)\)/', '<?php include(\1) ?>', $content);
        */

        // loop
        $content = preg_replace('/\@foreach\s+?\((.*)\)/', '<?php foreach (\1): ?>', $content);
        $content = preg_replace('/\@endforeach/', '<?php endforeach; ?>', $content);

        // conditions
        $content = preg_replace('/\@if\s+?\((.*)\)/', '<?php if (\1): ?>', $content);
        $content = preg_replace('/\@elseif\s+?\((.*)\)/', '<?php elseif (\1): ?>', $content);
        $content = preg_replace('/\@else/', '<?php else: ?>', $content);
        $content = preg_replace('/\@endif/', '<?php endif; ?>', $content);

        $path = $this->cachePath . $this->file . '.php';
        file_put_contents($path, $content);

        $this->cacheFile = $path;
    }

    public function render()
    {
        extract($this->data);
        ob_start();
        include $this->cacheFile;
        $html = ob_get_clean();

        return new Http\Response($html);
    }
}

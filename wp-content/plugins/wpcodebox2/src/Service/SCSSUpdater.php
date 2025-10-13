<?php

namespace Wpcb2\Service;


use Wpcb2\Repository\SnippetRepository;
use Wpcb2\Service\Minify\MinifyFactory;

class SCSSUpdater
{
    public function recompileCode($snippet) {

        $snippetRepository = new SnippetRepository();

        $code = $snippet['original_code'];
        $minify = $snippet['minify'];


        $compiler = new \Wpcb2\Compiler();
        $code = $compiler->compileCode($code, 'scss');

        if(isset($minify) && $minify) {
            $minifyFactory = new MinifyFactory();
            $minifyService = $minifyFactory->createMinifyService('scss');
            $code = $minifyService->minify($code);
        }

        $snippetRepository->updateSnippet($snippet['id'], ['code' => $code]);

        $renderType = $snippet['renderType'];

        if($renderType === 'external') {
            $externalFileService = new ExternalFile();
            $externalFileService->writeContentToFile($snippet['id'] . '.' . 'css', $code);
        }
    }

}

<?php

namespace Wpcb2\Service\Minify;


class MinifyFactory {


    public function createMinifyService($fileType)
    {
        if($fileType === 'css' || $fileType === 'scss' || $fileType === 'less') {
            return new MinifyCss();
        } else if ($fileType === 'js') {
            return new MinifyJs();
        } else {
            return new MinifyNull();
        }
    }

}

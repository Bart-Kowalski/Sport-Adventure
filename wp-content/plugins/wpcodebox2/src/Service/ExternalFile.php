<?php

namespace Wpcb2\Service;


class ExternalFile {


    public function writeContentToFile($filename, $content)
    {

        $wpcodeboxDir = $this->getUploadsDir();

        if(!is_dir($wpcodeboxDir)) {
            mkdir($wpcodeboxDir);
            file_put_contents($wpcodeboxDir . DIRECTORY_SEPARATOR . 'index.php', '<?php die();');
        }

        file_put_contents($wpcodeboxDir . DIRECTORY_SEPARATOR . $filename, $content );
    }

    public function deleteFile($postId) {

        $wpcodeboxDir = $this->getUploadsDir();

        if(@file_exists($wpcodeboxDir . DIRECTORY_SEPARATOR . $postId . '.css')) {
            @unlink($wpcodeboxDir . DIRECTORY_SEPARATOR . $postId . '.css');
        }

        if(@file_exists($wpcodeboxDir . DIRECTORY_SEPARATOR . $postId . '.js')) {
            @unlink($wpcodeboxDir . DIRECTORY_SEPARATOR . $postId . '.js');
        }
    }

    /**
     * @return string
     */
    private function getUploadsDir()
    {
        $dir = wp_upload_dir();
        $wpcodeboxDir = $dir['basedir'] . DIRECTORY_SEPARATOR . 'wpcodebox';
        return $wpcodeboxDir;
    }

}

<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;
use Dompdf\Dompdf;
use Dompdf\Options;

class Create_Pdf
{
    public $name = "create_pdf";
    private $base;
    private $settings;

    public function run($form)
    {
        $this->base = $form;
        $this->settings = $form->get_settings();

        $result = "";

        $forms_helper = new FormsHelper();
        $form_settings = $form->get_settings();
        $template_type = isset($form_settings['createPdfTemplateType']) ? $form_settings['createPdfTemplateType'] : 'file';
        $template = isset($form_settings['createPdfHtmlTemplate']) ? $form_settings['createPdfHtmlTemplate'] : '';
        $template_filename = isset($form_settings['createPdfFileTemplate']) ? $form->get_form_field_by_id($form_settings['createPdfFileTemplate']) : false;
        $show_pdf_after_submission = isset($form_settings['createPdfShowDownloadAfterSubmission']) ? $form_settings['createPdfShowDownloadAfterSubmission'] : false;
        $add_to_media_library = isset($form_settings['createPdfAddToMediaLibrary']) ? $form_settings['createPdfAddToMediaLibrary'] : false;
        $paper_size = isset($form_settings['createPdfPaperSize']) ? $form_settings['createPdfPaperSize'] : 'A4';
        $paper_orientation = isset($form_settings['createPdfOrientation']) ? $form_settings['createPdfOrientation'] : 'portrait';

        if (($template_type == "html" && !$template)) {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'error',
                    'message' => 'No PDF template provided',
                ]
            );
            return false;
        }

        // Handle Paper Size. 
        // If Paper Size starts with "[", the user passed an array for a custom size. 
        // We need to split the string and get the values.
        if (strpos($paper_size, '[') === 0) {
            $paper_size = str_replace(['[', ']'], '', $paper_size);
            $paper_size = explode(',', $paper_size);
            $paper_size = array_map('trim', $paper_size);
        }

        $template = $this->handle_template($template_type, $template_filename, $template, $form);

        if (!$template) {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'error',
                    'message' => 'PDF template not found',
                ]
            );
            return false;
        }

        $pdf_path = $this->create_pdf($template, $paper_size, $paper_orientation);

        if (!$pdf_path) {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'error',
                    'message' => 'PDF creation failed',
                ]
            );
            return false;
        }

        $result = $pdf_path;

        if (!$result) {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'error',
                    'message' => 'PDF creation failed',
                ]
            );
            return false;
        }

        $form->update_live_pdf_url($result);

        if ($add_to_media_library) {
            $attachment_id = $this->upload_pdf_to_media_library($form);

            if ($attachment_id) {
                $form->update_live_pdf_id($attachment_id);
            }
        }

        $form->set_result(
            [
                'action' => $this->name,
                'type'   => 'success',
                'showPdf' => $show_pdf_after_submission ? true : false,
                'pdfUrl' => $result,
            ]
        );

        return $result;
    }

    /**
     * Upload the PDF to the media library
     * @param string $path
     * @return int
     */
    public function upload_pdf_to_media_library($form)
    {
        $path = $form->get_live_pdf_path();
        $url = $form->get_live_pdf_url();

        // Check if the file exists
        if (!file_exists($path)) {
            return false;
        }

        // We upload the pdf to the media library using the same $url
        $upload_dir_info = wp_upload_dir();

        $wp_filetype = wp_check_filetype(basename($url), null);

        $attachment = array(
            'guid'           => $url,
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($url)),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        $attachment_id = wp_insert_attachment($attachment, $path);

        if (!$attachment_id) {
            return false;
        }

        return $attachment_id;
    }

    /**
     * Handle the template
     * @param string $template
     * @param object $form
     * @return string
     */
    private function handle_template($template_type, $template_filename, $template, $form)
    {
        // HTML Template (from Builder)
        if ($template_type == "html") {
            $template = $form->get_form_field_by_id($template);
            return $template;
        }

        if (!$template_filename) {
            $template_path = BRICKSFORGE_PATH . '/includes/templates/pdf/default.html';
        } else {
            // File Template (path: /uploads/bricksforge/pdf/templates/{$template_filename})
            $template_path = WP_CONTENT_DIR . '/uploads/bricksforge/pdf/templates/' . $template_filename;
        }

        if (!file_exists($template_path)) {
            return false;
        }

        $template = file_get_contents($template_path);

        if (!$template) {
            return false;
        }

        $template = $form->get_form_field_by_id($template);

        return $template;
    }

    /**
     * Create the PDF
     * @param string $template
     * @return boolean
     */
    private function create_pdf($template, $paper_size, $paper_orientation)
    {
        require_once(BRICKSFORGE_PATH . '/includes/vendor/dompdf/autoload.php');

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($template);
        $dompdf->setPaper($paper_size, $paper_orientation);
        $dompdf->render();

        $output = $dompdf->output();

        if (!$output) {
            return false;
        }

        $pdf_path = $this->handle_pdf_upload($output);

        if (!$pdf_path) {
            return false;
        }

        return $pdf_path;
    }

    /**
     * Handle the PDF upload
     * @param string $pdf
     * @return string
     */
    private function handle_pdf_upload($pdf)
    {
        // Input validation for the PDF content
        // Ensure $pdf is not empty and is a valid PDF content
        if (empty($pdf) || strpos($pdf, '%PDF-') !== 0) {
            // Log error or handle it accordingly
            return false;
        }

        // We upload the pdf to /uploads/bricksforge/pdf/
        $upload_dir_info = wp_upload_dir();
        // Ensuring the upload directory retrieval is successful
        if ($upload_dir_info['error']) {
            // Log the error or handle it accordingly
            return false;
        }
        $upload_dir = $upload_dir_info['basedir'] . '/bricksforge/pdf/files/';

        // Security: Avoid directory traversal & validate directory path
        // Realpath will return false if the directory doesn't exist
        $real_upload_dir = realpath($upload_dir);
        $base_dir = realpath($upload_dir_info['basedir']);
        if ($real_upload_dir === false || strpos($real_upload_dir, $base_dir) !== 0) {
            // Attempt to create the directory if it doesn't exist
            if (!mkdir($upload_dir, 0755, true) && !is_dir($upload_dir)) {
                // Log error or handle it accordingly
                return false;
            }
        }

        // Check again after attempting to create directory
        if (!is_dir($upload_dir)) {
            // Log error or handle it accordingly
            return false;
        }

        // Generate a unique filename for the PDF to avoid any naming conflicts
        $pdf_name = 'pdf_' . time() . '_' . bin2hex(random_bytes(8)) . '.pdf';
        $user_pdf_name = isset($this->settings["createPdfName"]) ? $this->base->get_form_field_by_id($this->settings["createPdfName"]) : false;

        if ($user_pdf_name) {
            $pdf_name = $user_pdf_name . '-' . time() . '.pdf';
        }

        $pdf_path = $upload_dir . $pdf_name;

        $this->base->update_live_pdf_path($pdf_path);

        $pdf_url = $upload_dir_info['baseurl'] . '/bricksforge/pdf/files/' . $pdf_name;

        // Write the PDF content to the file
        $success = file_put_contents($pdf_path, $pdf);
        if (!$success) {
            // Log error or handle it accordingly
            return false;
        }

        // Return the path to the uploaded PDF
        return $pdf_url;
    }
}

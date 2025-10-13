<?php

namespace Bricksforge\ProForms\Actions;

class Email
{
    public $name = "email";
    /**
     * User login
     *
     * @since 1.0
     */
    public function run($form)
    {


        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();

        // Email To
        if (isset($form_settings['emailTo']) && $form_settings['emailTo'] === 'custom' && !empty($form_settings['emailToCustom'])) {
            $recipients = $form->get_form_field_by_id($form_settings['emailToCustom']);

            $recipients = explode(',', $recipients);

            $recipients = array_map('trim', $recipients);

            $recipients = array_filter($recipients, 'is_email');
        }

        // Dynamic Email
        if (isset($form_settings['emailTo']) && $form_settings['emailTo'] === 'dynamic') {
            $recipients = [];

            $email_addresses = isset($form_settings['emailToDynamic']) && is_array($form_settings['emailToDynamic']) ? $form_settings['emailToDynamic'] : [];

            if (!empty($email_addresses)) {
                foreach ($email_addresses as $instance) {
                    $email = $form->get_form_field_by_id($instance['email']);
                    $form_field = $form->get_form_field_by_id($instance['formField']);
                    $condition = isset($instance['condition']) ? $instance['condition'] : '==';
                    $form_value = $form->get_form_field_by_id($instance['formValue']);

                    switch ($condition) {
                        case '==':
                            if ($form_field == $form_value) {
                                $recipients[] = $email;
                            }
                            break;
                        case '!=':
                            if ($form_field != $form_value) {
                                $recipients[] = $email;
                            }
                            break;
                        case '>':
                            if ($form_field > $form_value) {
                                $recipients[] = $email;
                            }
                            break;
                        case '>=':
                            if ($form_field >= $form_value) {
                                $recipients[] = $email;
                            }
                            break;
                        case '<':
                            if ($form_field < $form_value) {
                                $recipients[] = $email;
                            }
                            break;
                        case '<=':
                            if ($form_field <= $form_value) {
                                $recipients[] = $email;
                            }
                            break;
                        case 'contains':
                            if (strpos($form_field, $form_value) !== false) {
                                $recipients[] = $email;
                            }
                            break;
                        case 'not_contains':
                            if (strpos($form_field, $form_value) === false) {
                                $recipients[] = $email;
                            }
                            break;
                        case 'starts_with':
                            if (strpos($form_field, $form_value) === 0) {
                                $recipients[] = $email;
                            }
                            break;
                        case 'ends_with':
                            if (substr($form_field, -strlen($form_value)) === $form_value) {
                                $recipients[] = $email;
                            }
                            break;
                        case 'empty':
                            if ($form_field === '') {
                                $recipients[] = $email;
                            }
                            break;
                        case 'not_empty':
                            if ($form_field !== '') {
                                $recipients[] = $email;
                            }
                            break;
                        case 'exists':
                            if (!is_null($form_field)) {
                                $recipients[] = $email;
                            }
                            break;
                        case 'not_exists':
                            if (is_null($form_field)) {
                                $recipients[] = $email;
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        if (empty($recipients)) {
            $recipients = get_option('admin_email');
        }

        // Email subject
        $subject = isset($form_settings['emailSubject']) ? $form->get_form_field_by_id($form_settings['emailSubject']) : sprintf(esc_html__('%s: New contact form message', 'bricksforge'), get_bloginfo('name'));

        // Email message
        $message = '';
        if (!empty($form_settings['emailContent'])) {
            $message = $form->get_form_field_by_id($form_settings['emailContent'], null, null, null, null, true, null, true);

            // Only add line breaks in case the user didn't add an HTML template
            $message = isset($form_settings['htmlEmail']) && is_string($form_settings['emailContent']) && strpos($message, '<html') === false ? nl2br($message) : $message;
        }

        $email = [
            'to'      => $recipients,
            'subject' => isset($subject) ? $subject : '',
            'message' => $message,
        ];

        // Email headers
        $headers = [];

        // Header: 'From'
        $from_email = !empty($form_settings['fromEmail']) ? $form->get_form_field_by_id($form_settings['fromEmail']) : false;

        if ($from_email) {
            $from_name = !empty($form_settings['fromName']) ? $form->get_form_field_by_id($form_settings['fromName']) : false;

            $headers[] = $from_name ? "From: $from_name <$from_email>" : "From: $from_email";
        }

        // Header: 'Content-Type'
        if (isset($form_settings['htmlEmail'])) {
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
        }

        // Header: 'Bcc'
        if (isset($form_settings['emailBcc'])) {
            $headers[] = sprintf('Bcc: %s', $form->get_form_field_by_id($form_settings['emailBcc']));
        }

        // Header: 'Reply-To' email address
        $reply_to_email_address = !empty($form_settings['replyToEmail']) ? $form->get_form_field_by_id($form_settings['replyToEmail']) : '';

        if ($reply_to_email_address) {
            $headers[] = sprintf('Reply-To: %s', $reply_to_email_address);
        } else {
            // Use first valid email address found in submitted form data as 'Reply-To' email address (use for confirmation email too @since 1.7.2)
            foreach ($form_fields as $key => $value) {
                if (is_string($value) && is_email($value)) {
                    $headers[]              = sprintf('Reply-To: %s', $value);
                    $reply_to_email_address = $value;
                    break;
                }
            }
        }

        // Add attachments if exist
        $attachments = [];
        if (!isset($form_settings['emailIgnoreAttachments']) || !$form_settings['emailIgnoreAttachments']) {
            foreach ($form_files as $input_name => $files) {
                foreach ($files as $file) {
                    $attachments[] = $file['file'];
                }
            }
        }

        if (isset($form_settings['createPdfAddAsAttachment']) && $form_settings['createPdfAddAsAttachment']) {
            $pdf_path = $form->get_live_pdf_path();

            if ($pdf_path) {
                $attachments[] = $pdf_path;
            }
        }

        // STEP: Send the email
        $email_sent = wp_mail($email['to'], $email['subject'], $email['message'], $headers, $attachments);

        // STEP: Send confirmation email to submitted email address (@since 1.7.2)
        $confirmation_email_content = isset($form_settings['confirmationEmailContent']) ? $form->get_form_field_by_id($form_settings['confirmationEmailContent']) : false;

        if ($confirmation_email_content && $reply_to_email_address) {

            $confirmation_email_to      = isset($form_settings['confirmationEmailTo']) ? $form->get_form_field_by_id($form_settings['confirmationEmailTo']) : $reply_to_email_address;
            $confirmation_email_subject = isset($form_settings['confirmationEmailSubject']) ? $form->get_form_field_by_id($form_settings['confirmationEmailSubject']) : get_bloginfo('name') . ': ' . esc_html__('Thank you for your message', 'bricks');

            // Header: 'From'
            $confirmation_from_name  = isset($form_settings['confirmationFromName']) ? $form->get_form_field_by_id($form_settings['confirmationFromName']) : get_bloginfo('name');
            $confirmation_from_email = isset($form_settings['confirmationFromEmail']) ? $form->get_form_field_by_id($form_settings['confirmationFromEmail']) : get_option('admin_email');

            $confirmation_email_headers = ["From: $confirmation_from_name <$confirmation_from_email>"];

            // Add X-Confirmation-Email header
            $confirmation_email_headers[] = 'X-Confirmation-Email: true';

            if (isset($form_settings['confirmationEmailHTML'])) {
                $confirmation_email_headers[] = 'Content-Type: text/html; charset=UTF-8';
            }

            // Send confirmation email
            $confirmation_sent = wp_mail(
                $confirmation_email_to,
                $confirmation_email_subject,
                $confirmation_email_content,
                $confirmation_email_headers
            );
        }

        // Error
        if (!$email_sent) {
            $form->set_result(
                [
                    'action'  => $this->name,
                    'type'    => 'error',
                    'message' => !empty($form_settings['emailErrorMessage']) ? bricks_render_dynamic_data($form_settings['emailErrorMessage']) : '',
                    'content' => $message,
                ],
            );
        } else {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'success',
                ]
            );
        }
    }
}

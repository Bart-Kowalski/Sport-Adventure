<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Webhook
{
    public $name = "webhook";

    public function run($form)
    {
        $forms_helper = new FormsHelper();

        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $form_id = $form->get_form_id();

        $webhooks = isset($form_settings['pro_forms_post_action_webhooks']) ? $form_settings['pro_forms_post_action_webhooks'] : array();

        if (empty($webhooks)) {
            return;
        }

        $results = array();

        foreach ($webhooks as $webhook) {
            $debug_show_response_in_console = isset($webhook['debug_show_response_in_console']) ? $webhook['debug_show_response_in_console'] : false;

            if ($debug_show_response_in_console && isset($webhook['url']) && !empty($webhook['url'])) {
                $results[] = [
                    'url' => $webhook['url'],
                    'response' => $this->send_webhook($webhook, $form_fields, $form)
                ];
            } else {
                $this->send_webhook($webhook, $form_fields, $form);
            }
        }

        return $results;
    }

    private function send_webhook($webhook, $form_data, $form)
    {
        $url = isset($webhook['url']) ? $webhook['url'] : '';
        $method = isset($webhook['method']) ? strtoupper($webhook['method']) : 'POST';
        $contentType = isset($webhook['content_type']) ? $webhook['content_type'] : 'json';
        $data = isset($webhook['data']) ? $webhook['data'] : array();
        $headers = isset($webhook['headers']) ? $webhook['headers'] : array();
        $needs_hmac = isset($webhook['add_hmac']) ? $webhook['add_hmac'] : false;
        $hmac_secret = isset($webhook['hmac_key']) ? $webhook['hmac_key'] : '';
        $hmac_header_name = isset($webhook['hmac_header_name']) ? $webhook['hmac_header_name'] : 'HMAC';

        $webhook_data = array();

        if (empty($url)) {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'error',
                    'message' => __('Error updating webhook.', 'bricksforge'),
                ]
            );
        }

        foreach ($data as $d) {
            $keys = explode('.', $form->get_form_field_by_id($d['key'], null, null, null, null, false)); // Split key into components
            $value = $form->get_form_field_by_id($d['value'], null, null, null, null, false);
            $currentArray = &$webhook_data;

            $lastKeyIndex = count($keys) - 1;

            if ($value == "[]") {
                $value = [];
            }

            if ($value == "{}") {
                $value = new \stdClass();
            }

            // If value starts with [ and ends with ], it's an array
            if (is_string($value) && substr($value, 0, 1) == '[' && substr($value, -1) == ']') {
                $value = json_decode($value, true);
            }

            // If value is numeric, convert it to a float
            if (is_numeric($value)) {
                $value = (float)$value;
            }

            foreach ($keys as $index => $key) {
                $match = [];
                $isArray = preg_match('/(.*)(\[(\d+)\])/', $key, $match);

                if ($isArray) {
                    $key = $match[1]; // The key name
                    $arrayIndex = (int)$match[3]; // The array index
                }

                if ($index === $lastKeyIndex) { // If this is the last key
                    if ($isArray) {
                        if (!isset($currentArray[$key]) || !is_array($currentArray[$key])) {
                            $currentArray[$key] = [];
                        }
                        $currentArray[$key][$arrayIndex] = $value;
                    } else {
                        $currentArray[$key] = $value;
                    }
                    break;
                }

                if (!isset($currentArray[$key]) || (!is_array($currentArray[$key]) && !$isArray)) {
                    $currentArray[$key] = [];
                }
                if ($isArray && !isset($currentArray[$key][$arrayIndex])) {
                    $currentArray[$key][$arrayIndex] = [];
                }

                if ($isArray) {
                    $tempArray = &$currentArray[$key];
                    $currentArray = &$tempArray[$arrayIndex];
                } else {
                    $currentArray = &$currentArray[$key];
                }
            }
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $contentType == 'json' ? json_encode($webhook_data) : http_build_query($webhook_data));

        // Default headers
        $webhook_headers = $contentType == 'json' ? array('Content-Type:application/json') : array('Content-Type:application/x-www-form-urlencoded');

        foreach ($headers as $header) {
            $key = $form->get_form_field_by_id($header['key'], null, null, null, null, false);
            $value = $form->get_form_field_by_id($header['value'], null, null, null, null, false);
            $webhook_headers[] = "$key: $value";
        }

        if ($needs_hmac && !empty($hmac_secret)) {
            // HMac Secret Key
            $secret_key = $hmac_secret;

            // Generate HMAC
            $hmac_payload = $contentType == 'json' ? json_encode($webhook_data) : http_build_query($webhook_data);
            $hmac = hash_hmac('sha256', $hmac_payload, $hmac_secret);

            $webhook_headers[] = "$hmac_header_name: $hmac";
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $webhook_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'error',
                    'message' => __('Error updating webhook.' . curl_error($ch), 'bricksforge'),
                ]
            );
        }

        curl_close($ch);

        // Action: 'bricksforge/pro_forms/webhook_sent'
        do_action('bricksforge/pro_forms/webhook_sent', $result);

        if ($result === false) {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'error',
                    'message' => __('Error updating webhook.' . curl_error($ch), 'bricksforge'),
                ]
            );
        } else {
            $form->set_result(
                [
                    'action' => $this->name,
                    'type'   => 'success',
                ]
            );
        }

        // If $result is a string, we return it as it is
        if (is_string($result)) {
            return $result;
        }

        return json_decode($result, true);
    }
}

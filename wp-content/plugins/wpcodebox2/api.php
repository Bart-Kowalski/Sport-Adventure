<?php

if (!defined('ABSPATH')) {
    exit;
}

define('WPCB2_REMOTE_URL', 'https://api.wpcodebox.com');

add_action('admin_init', function () {

    if (!isset($_GET['wpcb2_route'])) {
        return;
    }
    if(!current_user_can('manage_options')) {
        return;
    }

    $router = new \Wpcb2\Http\Router();

    $router->map('GET', '/acs/snippets', [new \Wpcb2\Actions\GetSnippets(), 'execute']);
    $router->map('GET', '/acs/snippets/[i:id]', [new \Wpcb2\Actions\GetSnippet(), 'execute']);
    $router->map('GET', '/acs/snippets/get_condition_data', [new \Wpcb2\Actions\GetConditionData(), 'execute']);
    $router->map('GET', '/acs/settings', [new \Wpcb2\Actions\GetSettings(), 'execute']);
    $router->map('POST', '/acs/convert_to_condition_builder/[i:id]', [new \Wpcb2\Actions\ConvertSnippetToConditionBuilder(), 'execute']);
    $router->map('POST', '/acs/snippets/[i:id]', [new \Wpcb2\Actions\UpdateSnippet(), 'execute']);
    $router->map('POST', '/acs/snippets', [new \Wpcb2\Actions\CreateSnippet(), 'execute']);
    $router->map('POST', '/acs/snippets_create_from_cloud', [new \Wpcb2\Actions\CreateSnippetFromCloud(), 'execute']);
    $router->map('POST', '/acs/folder', [new \Wpcb2\Actions\CreateFolder(), 'execute']);
    $router->map('GET', '/acs/settings', [new \Wpcb2\Actions\GetSettings(), 'execute']);
    $router->map('POST', '/acs/settings', [new \Wpcb2\Actions\UpdateSettings(), 'execute']);
    $router->map('POST', '/acs/folders_create_from_cloud', [new \Wpcb2\Actions\CreateFolderFromCloud(), 'execute']);
    $router->map('POST', '/acs/snippets/[i:id]/switch_dev_mode', [new \Wpcb2\Actions\SwitchDevMode(), 'execute']);
    $router->map('POST', '/acs/update_snippet_order', [new \Wpcb2\Actions\UpdateSnippetOrder(), 'execute']);
    $router->map('POST', '/acs/folder_state/[i:id]', [new \Wpcb2\Actions\UpdateFolderState(), 'execute']);
    $router->map('POST', '/acs/snippets_delete/[i:id]', [new \Wpcb2\Actions\DeleteSnippet(), 'execute']);
    $router->map('POST', '/acs/snippet_toggles', [new \Wpcb2\Actions\SaveSnippetToggles(), 'execute']);
    $router->map('GET', '/acs/download_plugin', [new \Wpcb2\Actions\DownloadPlugin(), 'execute']);
    $router->map('POST', '/acs/generate_plugin', [new \Wpcb2\Actions\GeneratePlugin(), 'execute']);
    $router->map('POST', '/acs/change_fp_plugin_status', [new \Wpcb2\Actions\ChangeFPStatus(), 'execute']);


    $router->map('POST', '/acs/saved_to_cloud/[i:id]', function ($id) {

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

        $remote_id = $data['remote_id'];

		$snippetRepostory = new \Wpcb2\Repository\SnippetRepository();
		$snippetRepostory->updateSnippet($id, [
			'savedToCloud' => true,
			'remoteId' => $remote_id
		]);

        echo json_encode([]);
        die;
    });

	$router->map('POST', '/acs/disconnect_from_cloud/[i:id]', function ($id) {

		$snippetRepostory = new \Wpcb2\Repository\SnippetRepository();
		$snippetRepostory->updateSnippet($id, [
			'savedToCloud' => false,
			'remoteId' => 0
		]);

		echo json_encode([]);
		die;
	});

    $router->map('POST', '/acs/deleted_from_cloud/[i:id]', function ($id) {

		$snippetRepostory = new \Wpcb2\Repository\SnippetRepository();

		$snippetAlreadyExists = $snippetRepostory->findSnippetByRemoteId($id);
        if ($snippetAlreadyExists) {

			$snippetId = $snippetAlreadyExists['id'];
			$snippetRepostory->updateSnippet($snippetId, [
				'savedToCloud' => 0,
				'remoteId' => 0
				]);

			echo json_encode([]);
			die;

        }
    });

    $router->map('POST', '/acs/folder_deleted_from_cloud/[i:id]', function ($id) {

		$remoteFolderId = $id;
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);


		$snippetRepostory = new \Wpcb2\Repository\SnippetRepository();
		$folder = $snippetRepostory->findFolderByRemoteId($remoteFolderId);


        if ($folder) {

			$snippetRepostory->updateFolder($folder['id'], [
				'savedToCloud' => false,
				'remoteId' => false
			]);

            if(isset($data['children'])) {
                foreach($data['children'] as $child) {

					$snippet = $snippetRepostory->findSnippetByRemoteId($child['id']);

                    if($snippet) {
                        $snippetRepostory->updateSnippet($snippet['id'], [
							'savedToCloud' => false,
							'remoteId' => 0
							]);
                    }
                }
            }
        }
    });

    $router->map('POST', '/acs/folder_saved_to_cloud/[i:id]', function ($id) {

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

        $remote_id = $data['remote_id'];

		$snippetRepostory = new \Wpcb2\Repository\SnippetRepository();
		$snippetsInFolder = $snippetRepostory->getSnippetsInFolder($id);

		if(is_array($snippetsInFolder)) {
			foreach ($snippetsInFolder as $snippet) {

				$snippetRepostory->updateSnippet($snippet['id'], [
					'savedToCloud' => true,
					'remoteId' => $data['remote_snippet_ids'][$snippet['id']]
				]);
			}
		}

		$snippetRepostory->updateFolder($id, [
			'savedToCloud' => true,
			'remoteId' => $remote_id
			]);

		echo json_encode([]);
        die;
    });



    $router->map('POST', '/acs/folder/[i:id]', function ($id) {


        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

		$manager = new \Wpcb2\FunctionalityPlugin\Manager(false);
		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
		$folder = $snippetRepository->getFolder($id);
		$oldName = $folder['name'];

		$snippetsInFolder = $snippetRepository->getSnippetsInFolder($id);

		foreach($snippetsInFolder as $snippet ) {
			$manager->deleteSnippet($snippet['id']);
		}

		$snippetRepository->updateFolder($id,[
			'name' => $data['name']
		]);

		$manager->renameFolder($oldName, $data['name']);

		foreach ($snippetsInFolder as $snippet) {
			$manager->saveSnippet($snippet['id']);
		}


        echo json_encode(['newName' => $data['name']]);

        die;
    });


    $router->map('POST', '/acs/snippet_folder', function () {

        $response = array();

        $data = file_get_contents("php://input");
        $data = json_decode($data, true);

		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
        $snippetId = intval($data['snippet_id']);

		$snippet = $snippetRepository->getSnippet($data['snippet_id']);

        if($snippet['folderId'] === $data['folder_id']) {
            return;
        }

        if($snippet) {
			$manager = new \Wpcb2\FunctionalityPlugin\Manager(false);
			$manager->deleteSnippet($snippet['id']);

			$snippetRepository->updateSnippet($snippetId, [
				'folderId' => $data['folder_id'],
				'snippet_order' => -1
			]);

			$manager->saveSnippet($snippet['id']);
        }

        echo json_encode($response);
        die;
    });


    $router->map('POST', '/acs/folders_delete/[i:id]', function ($id) {

		$id = intval($id);
		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();

        $response = array();

		$folder = $snippetRepository->getFolder($id);

		// Delete all snippets in folder from the FP
        $child_snippets = $snippetRepository->getSnippetsInFolder($id);

        $fp = new \Wpcb2\FunctionalityPlugin\Manager(false);

        foreach($child_snippets as $child_snippet) {
            $fp->deleteSnippet($child_snippet['id']);
        }

		$fp->deleteFolder($folder['name']);

		$snippetRepository->deleteFolder($id);
        echo json_encode([]);
        die;
    });


    $router->map('POST', '/acs/snippets/[i:id]/enable', function ($id) {

		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
		$snippet = $snippetRepository->getSnippet($id);
		$enabled = $snippet['enabled'];

        if ($enabled) {
            $enabled = 0;
        } else {
            $enabled = 1;
        }

        $fp = new \Wpcb2\FunctionalityPlugin\Manager(false);
        $fp->updateStatus($id, $enabled);

		$snippetRepository->updateSnippet($id, [
			'enabled' => $enabled
		]);

        die;

    });

    $router->map('POST', '/acs/snippets/[i:id]/disable', function ($id) {


        $fp = new \Wpcb2\FunctionalityPlugin\Manager(false);
        $fp->disableSnippet($id);

		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
		$snippetRepository->updateSnippet($id, [
			'enabled' => 0
		]);

        die;

    });

    $router->map('POST', '/acs/snippets/[i:id]/refresh_token', function ($id) {


        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);
        $token = sha1(uniqid().wp_salt().$token);

		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
		$snippetRepository->updateSnippet($id, [
			'secret' => $token
		]);

		$manager = new \Wpcb2\FunctionalityPlugin\Manager(false);
		$manager->saveSnippet($id);
        echo json_encode(['url' => get_site_url() . '?wpcb_token=' . $token]);
        die;

    });


    $router->map('POST', '/acs/snippets/[i:id]/run', function ($id) {


		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
		$snippet = $snippetRepository->getSnippet($id);

        if ($snippet['runType'] !== 'once') {
            return;
        }

		$code = $snippet['code'];

        $pos = strpos($code, '<?php');
        if ($pos !== false) {
            $code = substr_replace($code, '', $pos, strlen('<?php'));
        }

        try {
            eval($code);
        } catch (\Throwable $e) {
            $response['error'] = $e->getMessage();
            echo json_encode($response);
            die;
        }

        die;

    });

    $router->map('POST', '/acs/snippets/[i:id]/clear_error', function ($id) {

		$snippetRepository = new \Wpcb2\Repository\SnippetRepository();
		$snippetRepository->updateSnippet($id, [
			'error' => 0,
			'errorMessage' => 0,
			'errorTrace' => 0
		]);

        echo json_encode([]);
        die;
    });



    // Condition builder routes
    $router->map('GET', '/acs/posts', function () {

        $response = [];

        $posts = get_posts([
            'numberposts' => -1,
            'post_type' => 'any'
        ]);


        foreach ($posts as $post) {
            $response[] = [
                'value' => $post->ID,
                'label' => $post->post_title
            ];
        }

        echo json_encode($response);
        die;
    });

    // Condition builder routes
    $router->map('GET', '/acs/taxonomies', function () {

        $response = [];

        $taxonomies = get_taxonomies([], 'objects');


        foreach ($taxonomies as $taxonomy) {

            $response[] = [
                'value' => $taxonomy->name,
                'label' => $taxonomy->label
            ];
        }

        echo json_encode($response);
        die;
    });

    // Condition builder routes
    $router->map('GET', '/acs/taxonomy/terms/[*:taxonomy]', function ($taxonomy) {

        $response = [];

        $terms = get_terms($taxonomy, array(
            'hide_empty' => false,
        ));


        foreach ($terms as $term) {

            $response[] = [
                'value' => $term->term_id,
                'label' => $term->name
            ];
        }

        echo json_encode($response);
        die;
    });


    $router->map('GET', '/acs/post_types', function () {
        $post_types = get_post_types([
            'public' => true
        ]);

        echo json_encode($post_types);

    });

    $router->map('GET', '/acs/users', function () {

        $response = [];

        $users = get_users();

        foreach ($users as $user) {

            $response[] = [
                'value' => $user->ID,
                'label' => $user->user_nicename
            ];
        }

        echo json_encode($response);

    });

    $router->map('GET', '/acs/user_roles', function () {

        $response = [];

        $user_roles = get_editable_roles();

        foreach ($user_roles as $role_name => $role_details) {

            $response[] = [
                'value' => $role_name,
                'label' => $role_details['name']
            ];
        }

        echo json_encode($response);

    });

    if (isset($_GET['wpcb2_route'])) {

        if (!function_exists('getallheaders')) {
            function getallheaders()
            {
                $headers = [];
                foreach ($_SERVER as $name => $value) {
                    if (substr($name, 0, 5) == 'HTTP_') {
                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
                return $headers;
            }
        }

        $headers = array_change_key_case(getallheaders(), CASE_LOWER);

        $nonce = $headers['x-wpcb-authorization'];

        if (!wp_verify_nonce($nonce, 'wpcb-api-nonce')) {
            die('Unauthorized request');
        }

        if (!current_user_can('manage_options')) {
            die('Unauthorized');
        }

        // match current request url
        $match = $router->match($_GET['wpcb2_route'], $_SERVER['REQUEST_METHOD']);

        // call closure or throw 404 status
        if ($match && is_callable($match['target'])) {
            call_user_func_array($match['target'], $match['params']);
        } else {
            // no route was matched
            header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        }

        die;
    }

}, 0);

<?php

namespace Wpcb2\Repository;


class SnippetRepository
{
	private $wpdb;

	public function __construct()
	{

		global $wpdb;
		$this->wpdb = $wpdb;
	}

	public function getQuickActionsSnippets()
	{
		$snippets = $this->wpdb->get_results('SELECT * FROM ' . $this->wpdb->prefix . 'wpcb_snippets WHERE addToQuickActions = 1 ORDER BY snippet_order', ARRAY_A);
		return $snippets;
	}

	public function getPartials()
	{
		$query = "SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE codeType = 'scssp' ORDER BY snippet_order";

		$partials = $this->wpdb->get_results($query, ARRAY_A);

		if (is_array($partials) && !empty($partials)) {
			$return = [];
			foreach ($partials as $partial) {
				$return[] = "'" . $partial['title'] . "'";
			}

			return $return;
		} else {
			return false;
		}
	}

	public function getPartialsAndIds()
	{
		$query = "SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE codeType = 'scssp' ORDER BY snippet_order";

		$partials = $this->wpdb->get_results($query, ARRAY_A);

		if (is_array($partials) && !empty($partials)) {
			$return = [];
			foreach ($partials as $partial) {
				$folder_name = false;

				if ($partial['folder'] != 0) {
					$folder = $this->getFolder($partial['folder']);
					if($folder) {
						$folder_name = $folder['title'];
					}
				}

				if ($folder_name) {
					$return[$partial['id']] = "'" . $partial['folder'] . '/' . $partial['title'] . "'";
				} else {
					$return[$partial['id']] = "'" . $partial['title'] . "'";
				}
			}

			return $return;
		} else {
			return false;
		}
	}

	public function getSnippetsThatDefineFunction($functionName) {

		$query = "SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE code LIKE %s";
		$query = $this->wpdb->prepare($query, '%' . $this->wpdb->esc_like($functionName) . '%');
		$snippet = $this->wpdb->get_row($query, ARRAY_A);
		return $snippet;
	}

	public function getExternalRunSnippetsBySecret($secret)
	{
		if(!$secret) {
			return false;
		}

		if(!ctype_alnum($secret)) {
			return false;
		}
		$query = $this->wpdb->prepare("SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE runType = 'external' AND secret = %s", $secret);
		return $this->wpdb->get_results($query, ARRAY_A);


	}

	public function getAllSnippetsQuery()
	{
		global $wpdb;

		$snippets = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wpcb_snippets WHERE 1 ORDER BY snippet_order", ARRAY_A);

		return $snippets;
	}

	public function getSnippetsToExecute()
	{

		global $wpdb;

		$snippets = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "wpcb_snippets WHERE enabled = 1 AND runType='always' AND codeType != 'txt' ORDER BY priority", ARRAY_A);

		return $snippets;
	}

	public function getSnippet($snippetId)
	{
		$snippetId = intval($snippetId);

		$query = "SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE id = %d";
		$query = $this->wpdb->prepare($query, $snippetId);
		$snippet = $this->wpdb->get_row($query, ARRAY_A);

		if(isset($snippet['folderId']) && $snippet['folderId'] != 0) {
			$snippet['folder'] = $this->getFolder($snippet['folderId']);
		}

		return $snippet;
	}

	public function updateSnippet($snippetId, $snippetData)
	{
		$snippetId = intval($snippetId);

		$snippetData = $this->convertArraysToJson($snippetData);

		return $this->wpdb->update($this->wpdb->prefix . 'wpcb_snippets', $snippetData, array('id' => $snippetId));

	}

	public function updateFolder($folderId, $folderData)
	{
		$folderId = intval($folderId);
		$this->wpdb->update($this->wpdb->prefix . 'wpcb_folders', $folderData, array('id' => $folderId));

	}

	public function createSnippet($snippetData)
	{
		$snippetData = $this->convertArraysToJson($snippetData);
		$this->wpdb->insert($this->wpdb->prefix . 'wpcb_snippets', $snippetData);

		return $this->wpdb->insert_id;
	}

	public function createFolder($dolderData)
	{
		$this->wpdb->insert($this->wpdb->prefix . 'wpcb_folders', $dolderData);
		$folderId = $this->wpdb->insert_id;

		return $folderId;
	}

	public function getSnippetsInFolder($folderId)
	{
		$folderId = intval($folderId);

		$query = "SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE folderId = %d ORDER BY snippet_order";
		$query = $this->wpdb->prepare($query, $folderId);
		$snippets = $this->wpdb->get_results($query, ARRAY_A);

		return $snippets;
	}


	public function getFolders()
	{
		return $this->wpdb->get_results("SELECT * FROM {$this->wpdb->prefix}wpcb_folders", ARRAY_A);
	}

	public function getFolder($folderId)
	{
		$folderId = intval($folderId);
		$query = "SELECT * FROM {$this->wpdb->prefix}wpcb_folders WHERE id = %d";
		$query = $this->wpdb->prepare($query, $folderId);

		return $this->wpdb->get_row($query, ARRAY_A);
	}

	public function isFolder($folderId)
	{
		if ($this->getFolder($folderId)) {
			return true;
		} else {
			return false;
		}
	}

	function deleteFolder($folderId)
	{
		$folderId = intval($folderId);

		$query = "DELETE FROM {$this->wpdb->prefix}wpcb_snippets WHERE folderId = %d";
		$query = $this->wpdb->prepare($query, $folderId);
		$this->wpdb->query($query);

		$query = "DELETE FROM {$this->wpdb->prefix}wpcb_folders WHERE id = %d";
		$query = $this->wpdb->prepare($query, $folderId);
		$this->wpdb->query($query);

		return [];
	}

	function findSnippetByRemoteId($remoteId)
	{
		$remoteId = intval($remoteId);

		$query = "SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE remoteId = %d";
		$query = $this->wpdb->prepare($query, $remoteId);
		return $this->wpdb->get_row($query, ARRAY_A);
	}

	public function findFolderByRemoteId($remoteId)
	{
		$remoteId = intval($remoteId);

		$query = "SELECT * FROM {$this->wpdb->prefix}wpcb_folders WHERE remoteId = %d";
		$query = $this->wpdb->prepare($query, $remoteId);
		return $this->wpdb->get_row($query, ARRAY_A);
	}

	public function deleteSnippet($snippetId)
	{
		$snippetId = intval($snippetId);

		$query = "DELETE FROM {$this->wpdb->prefix}wpcb_snippets WHERE id = %d";
		$query = $this->wpdb->prepare($query, $snippetId);
		$this->wpdb->query($query);

		return true;
	}

	public function getSnippetsThatUsePartial($partialName)
	{
		$partialName = '%' . $this->wpdb->esc_like("@use '" . $partialName . "';") . '%';

		$query = $this->wpdb->prepare("SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE original_code LIKE %s", $partialName);

		$snippets = $this->wpdb->get_results($query, ARRAY_A);

		return $snippets;
	}

	public function getSnippetByTitle($title)
	{
		$query = $this->wpdb->prepare("SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE title = %s", $title);
		$snippet = $this->wpdb->get_row($query, ARRAY_A);

		return $snippet;
	}

	public function getSnippetByTitleAndFolderId($title, $folderId)
	{
		$folderId = intval($folderId);
		$query = $this->wpdb->prepare("SELECT * FROM {$this->wpdb->prefix}wpcb_snippets WHERE title = %s AND folderId = %s", $title, $folderId);
		$snippet = $this->wpdb->get_row($query, ARRAY_A);

		return $snippet;
	}

	/**
	 * @param $snippetData
	 * @return false|mixed|string
	 */
	public function convertArraysToJson($snippetData)
	{
		foreach ($snippetData as $key => $data) {
			if (is_array($data)) {
				$snippetData[$key] = json_encode($data);
			}
		}
		return $snippetData;
	}

	public function getUniqueTitle($title, $folderId)
	{
		if($this->getSnippetByTitleAndFolderId($title, $folderId)) {

			while($this->getSnippetByTitleAndFolderId($title, $folderId)) {
				$title = $title . '_1';
			}
		}

		return $title;

	}


}

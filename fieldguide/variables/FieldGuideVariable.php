<?php
namespace Craft;

class FieldGuideVariable {

	public function getBlockTypes($matrixFieldId)
	{
		$blockTypes = craft()->matrix->getBlockTypesByFieldId($matrixFieldId);

		$blockTypesList = array();

		foreach($blockTypes as $blockType) {

			array_push($blockTypesList, array(
				'name' => $blockType->getAttribute('name'),
				'handle' => $blockType->getAttribute('handle'),
				'fields' => $blockType->getFields()
			));
		}

		return $blockTypesList;
	}

	public function getUserFieldGroup() {
		return craft()->fields->getLayoutByType('User');
	}

	public function getUserGroups()
	{
		$groups = craft()->userGroups->getAllGroups();
		$returnGroups = array();
		foreach ($groups as $group) {
			$returnGroups[$group->id] = $group;
		}

		return $returnGroups;
	}

	public function getCategoryGroups()
	{
		$groups = craft()->categories->getAllGroups();
		$returnGroups = array();
		foreach ($groups as $group) {

			$returnGroups[$group->id] = array(
				'handle' => $group->handle,
				'name' => $group->name,
				'structureId' => $group->structureId,
				'fieldLayoutId' => $group->fieldLayoutId
			);
		}

		return $returnGroups;
	}

	public function getFieldLayoutById($id)
	{
		return craft()->fields->getLayoutById($id);
	}

	public function getCategoryGroupBySourceString($string)
	{
		$groups = $this->getCategoryGroups();
		if(strpos($string, ":") !== false) {
			$string = substr($string, strpos($string, ':') + 1);
		}

		return $groups[$string];
	}

	public function getAllAssetSources() {
		$groups = craft()->assetSources->getAllSources();
		$returnGroups = array();
		foreach ($groups as $group) {

			$returnGroups[$group->id] = array(
				'handle' => $group->handle,
				'name' => $group->name,
				'fieldLayoutId' => $group->fieldLayoutId
			);
		}

		return $returnGroups;

		// return craft()->assetSources->getAllSources();
	}

	public function getAllGlobalSets() {
		$groups = craft()->globals->getAllSets();
		$returnGroups = array();
		foreach ($groups as $group) {

			$returnGroups[$group->id] = array(
				'handle' => $group->handle,
				'name' => $group->name,
				'fieldLayoutId' => $group->fieldLayoutId
			);
		}

		return $returnGroups;
	}

	public function getAssetFolderBySourceString($string) {

		if(strpos($string, ":") !== false) {
			$string = substr($string, strpos($string, ':') + 1);
		}

		return craft()->assetSources->getSourceById($string)->attributes;

	}

	public function getSectionBySourceString($string) {

		if(strpos($string, ":") !== false) {
			$string = substr($string, strpos($string, ':') + 1);
		}

		return craft()->sections->getSectionById($string)->attributes;

	}

	public function getUserGroupBySourceString($string) {

		if(strpos($string, ":") !== false) {
			$string = substr($string, strpos($string, ':') + 1);
		}

		return craft()->userGroups->getGroupById($string)->attributes;

	}


	public function getUnusedFieldIds()
	{
		// all field ids
		$query = craft()->db->createCommand();
		$allFieldIds = $query
			->select('craft_fields.id')
			->from('fields')
			->order('craft_fields.id')
			->queryAll();
		$allFieldIds = self::array_flatten($allFieldIds);

		// used field ids
		$query = craft()->db->createCommand();
		$query->distinct = true;
		$usedFieldIds = $query
			->select('craft_fieldlayoutfields.fieldId')
			->from('fieldlayoutfields')
			->order('craft_fieldlayoutfields.fieldId')
			->queryAll();
		$usedFieldIds = self::array_flatten($usedFieldIds);

		// unused field ids
		$unusedFieldIds = array_diff($allFieldIds, $usedFieldIds);

		return $unusedFieldIds;
	}

	private function array_flatten($arr) {
		$arr = array_values($arr);
		while (list($k,$v)=each($arr)) {
			if (is_array($v)) {
				array_splice($arr,$k,1,$v);
				next($arr);
			}
		}
		return $arr;
	}

}
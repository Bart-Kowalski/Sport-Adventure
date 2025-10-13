<?php

namespace Wpcb2\ConditionBuilder;


use Wpcb2\Repository\SnippetRepository;

class ShouldExecute
{
    public function shouldExecutePreCheck($snippet) {

        $enabled = $snippet['enabled'];
        $runType = $snippet['runType'];

        if(!$enabled) {
            return false;
        }

        if($runType === 'once' || $runType === 'external') {
            return false;
        }

        return true;
    }

    public function shouldExecute($snippetId)
    {

		$snippetRepository = new SnippetRepository();
		$snippetData = $snippetRepository->getSnippet($snippetId);

		$conditions = json_decode($snippetData['conditions'], true);

        if ($conditions) {
			$conditionBuilder = new ConditionBuilder($conditions);

			$result = $conditionBuilder->is_satisfied();

			return $result;
		}

        return true;

    }

    public static function should_execute($snippetId) {
        $shouldExecute = new ShouldExecute();
        return $shouldExecute->shouldExecute($snippetId);
    }

}

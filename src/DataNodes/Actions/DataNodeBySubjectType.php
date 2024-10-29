<?php

namespace Pipetic\Bundle\DataNodes\Actions;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\Entities\FindRecord;
use Nip\Records\Record;
use Pipetic\Bundle\Utility\PipeticModels;

/**
 *
 */
class DataNodeBySubjectType extends Action
{
    use FindRecord;

    protected Record $pipeSubject;

    protected string $type;

    public function __construct()
    {
    }

    public static function for($subject, $type)
    {
        $action = static::make();
        $action->setPipeSubject($subject);
        $action->type = $type;

        return $action;
    }

    public function setPipeSubject($subject)
    {
        $this->pipeSubject = $subject;
    }

    protected function findParams(): array
    {
        return [
            'where' => [
                ['subject = ?', $this->pipeSubject->getManager()->getMorphName()],
                ['subject_id = ?', $this->pipeSubject->id],
                ['type = ?', $this->type],
            ],
        ];
    }

    protected function orCreateData($data)
    {
        $data['subject'] = $this->pipeSubject->getManager()->getMorphName();
        $data['subject_id'] = $this->pipeSubject->id;
        $data['type'] = $this->type;
        return $data;
    }

    protected function generateRepository(): \Nip\Records\AbstractModels\RecordManager
    {
        return PipeticModels::dataNodes();
    }
}

<?php

namespace Pipetic\Bundle\PipeSubject\ModelsRelated\HasSubject;

use Nip\Records\Record;

/**
 */
trait HasSubjectRecordTrait
{

    protected ?string $subject = null;

    protected ?int $subject_id = null;

    public function populateFromSubjectRecord(Record $record): void
    {
        $this->subject_id = $record->id;
        $this->subject = $record->getManager()->getMorphName();
        $this->getRelation('PipeSubject')->setResults($record);
    }

    public function getPipeSubject(): \Nip\Records\AbstractModels\Record
    {
        return $this->getRelation('PipeSubject')->getResults();
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string|null $subject
     */
    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return int|null
     */
    public function getSubjectId(): ?int
    {
        return $this->subject_id;
    }

    /**
     * @param int|null $subject_id
     */
    public function setSubjectId(?int $subject_id): void
    {
        $this->subject_id = $subject_id;
    }
}

<?php

namespace Pipetic\Bundle\Droplets\Actions\Finder;

use Bytic\Actions\Action;
use Bytic\Actions\Behaviours\Entities\FindRecords;
use Bytic\Actions\Behaviours\HasSubject\HasSubject;
use Pipetic\Bundle\Droplets\Actions\Behaviours\HasDestinationAttribute;
use Pipetic\Bundle\Droplets\Actions\Behaviours\HasRefAttribute;
use Pipetic\Bundle\Droplets\Actions\Behaviours\HasSourceAttribute;
use Pipetic\Bundle\Droplets\Statuses\Pending;
use Pipetic\Bundle\Droplets\Actions\Behaviours\HasRepository;

class DropletsFindBy extends Action
{
    use FindRecords;
    use HasSubject;
    use HasRepository;
    use HasDestinationAttribute;
    use HasSourceAttribute;
    use HasRefAttribute;

    protected function findParams(): array
    {
        $params = [];
        $params['where'] = [];

        $subject = $this->getSubject();
        if ($subject) {
            $params['where'][] = ['subject = ?', $subject->getManager()->getMorphName()];
            $params['where'][] = ['subject_id = ?', $subject->id];
        }
        $source = $this->getSource();
        if ($source) {
            $params['where'][] = ['source = ?', $source];
        }
        $destination = $this->getDestination();
        if ($destination) {
            $params['where'][] = ['destination = ?', $destination];
        }
        $refs = $this->getRef();
        if ($refs) {
            $params['where'][] = ['ref = ?', $refs];
        }
        return $params;
    }
}

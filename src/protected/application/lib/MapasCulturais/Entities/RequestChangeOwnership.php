<?php
namespace MapasCulturais\Entities;

use Doctrine\ORM\Mapping as ORM;
use MapasCulturais\App;

/**
 * @property \MapasCulturais\Entities\Agent $destination The new owner of the origin
 *
 * @ORM\Entity
 * @ORM\entity(repositoryClass="MapasCulturais\Repository")
 */
class RequestChangeOwnership extends Request{
    const TYPE_GIVE = 'give';
    const TYPE_REQUEST = 'request';

    function getRequestDescription() {
        return App::i()->txt('Request for change the ownership of the ') . strtolower($this->origin->getEntityType());
    }

    function getType(){
        return $this->metadata['type'];
    }

    function setDestination(\MapasCulturais\Entity $agent){
        $this->metadata['type'] = $agent->owner->canUser('@control') ? self::TYPE_REQUEST : self::TYPE_GIVE;

        parent::setDestination($agent);
    }

    function _doApproveAction() {
        $entity = $this->origin;
        $entity->owner = $this->destination;
        $entity->save();
    }

    protected function canUserCreate($user) {
        return $this->getType() === self::TYPE_REQUEST ?
                $this->destination->canUser('@control', $user) : $this->origin->owner->canUser('@control', $user);
    }

    protected function canUserApprove($user){
        if($this->getType() === self::TYPE_REQUEST)
            return $this->origin->owner->canUser('@control', $user);
        else
            return $this->destination->canUser('@control', $user);
    }

    protected function canUserReject($user){
        if($this->getType() === self::TYPE_REQUEST)
            return $this->origin->owner->canUser('@control', $user);
        else
            return $this->destination->canUser('@control', $user) || $this->origin->ownerUser->canUser('@control');
    }
}
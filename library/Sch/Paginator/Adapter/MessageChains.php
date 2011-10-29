<?php

class Sch_Paginator_Adapter_MessageChains extends Sch_Paginator_Adapter_Doctrine
{

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $_queryBuilder;

    public function __construct(\Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        $this->_queryBuilder = $queryBuilder;
    }

    /**
     * @param $offset
     * @param $itemsCount
     * @return array
     */
    public function getItems($offset, $itemsCount)
    {
        $chains = parent::getItems($offset, $itemsCount);
        foreach ($chains as /** @var \Entities\MessagesChain $chain */&$chain) {
            if (null === $chain->getUnreadMessagesCount()) {
                $unreadMessagesCount = $this->_getRepository()
                        ->getUnreadMessagesCount($chain->getTo()->getId(), $chain->getId(), $chain->getFrom()->getId());
                $chain->setUnreadMessagesCount((int)$unreadMessagesCount);
            }
            if (null === $chain->getLastMessageReadDate()) {
                $message = $this->_getRepository()
                        ->getLastReadMessage($chain->getFrom()->getId(), $chain->getId(), $chain->getTo()->getId());
                if ($message) {
                    $chain->setLastMessageReadDate($message->getDateRead());
                }
            }
        }

        return $chains;
    }

    /**
     * @return \Repository\Messages
     */
    protected function _getRepository()
    {
        return $this->_queryBuilder
                            ->getEntityManager()
                            ->getRepository('Entities\Message');
    }

}

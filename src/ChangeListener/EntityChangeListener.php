<?php


namespace ChangeListener;

use App\Entity\Horodatage;
use Doctrine\ORM\Event\OnFlushEventArgs;

abstract class EntityChangeListener
{
    private $user;

    /**
     * EntityChangeListener constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    protected function horadatage($classe, $nomClasse, $args)
    {
        if($this->user != null) {
            $em = $args->getEntityManager();
            $uow = $em->getUnitOfWork();

            foreach ($uow->getScheduledEntityInsertions() as $entity) {
                if ($entity instanceof $classe) {
                    $horodatage = new Horodatage();
                    $userObj = $em->getRepository('App:Utilisateur');

                    $user = $userObj->findOneBy(['email' => $this->user]);
                    $horodatage->setUtilisateur($user);
                    $horodatage->setNomEntite(get_class($entity));
                    $id = QueryEntity::getMaxId($em, $nomClasse);
                    if (isset($id[0])) {
                        $horodatage->setIdEntite($id[0]->getId() + 1);
                    } else {
                        $horodatage->setIdEntite(1);
                    }
                    $horodatage->setDate(new \DateTime());
                    $horodatage->setAction('Insertion');

                    $em->persist($horodatage);

                    $uow->computeChangeSet($em->getClassMetadata(get_class($horodatage)), $horodatage);
                }
            }

            foreach ($uow->getScheduledEntityDeletions() as $entity) {
                if ($entity instanceof $classe) {
                    $horodatage = new Horodatage();
                    $userObj = $em->getRepository('App:Utilisateur');

                    $user = $userObj->findOneBy(['email' => $this->user]);
                    $horodatage->setUtilisateur($user);
                    $horodatage->setNomEntite(get_class($entity));
                    $horodatage->setIdEntite($entity->getId());
                    $horodatage->setDate(new \DateTime());
                    $horodatage->setAction('Suppression');

                    $em->persist($horodatage);

                    $uow->computeChangeSet($em->getClassMetadata(get_class($horodatage)), $horodatage);
                }
            }
        }
    }

    abstract protected function onFlush(OnFlushEventArgs $args);
}
# Horodatage Symfony

## Installation 
Executez la commande suivante : 

```composer require adgauvry/s-entity-listener```

## Configuration
Il faut ajouter dans services.yaml : 
```yaml
    application_backend.event_listener:
        class: App\Service\MaClasseHorodatage
        tags:
            - { name: doctrine.event_listener, event: onFlush }
        arguments:
            - "@=service('security.token_storage').getToken().getUser()"
```

Il faut également avoir une entité "Utilisateur" qui doit comporter un champ "email" et une entité "Horodatage" qui doit comporter une jointure ManyToOne avec "Utilisateur" et les champs "nomEntite", "idEntite", "date", "action" et leur getter/setter correspondant.
<details><summary>Voir un exemple d'entité Horodatage</summary>
<p>

```php
<?php


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HorodatageRepository")
 */
class Horodatage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomEntite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idEntite;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getNomEntite(): ?string
    {
        return $this->nomEntite;
    }

    public function setNomEntite(string $nomEntite): self
    {
        $this->nomEntite = $nomEntite;

        return $this;
    }

    public function getIdEntite(): ?string
    {
        return $this->idEntite;
    }

    public function setIdEntite(?string $idEntite): self
    {
        $this->idEntite = $idEntite;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }
}
```
</p>
</details>

## Usage
Pour pouvoir utiliser l'horodatage, il faut faire hériter une classe par EntityChangeListener. Il faut ensuite implémeter la méthode onFlush.

```php
class MaClasseHorodatage extends EntityChangeListener  
{       
    public function onFlush(OnFlushEventArgs $args)  
    {
        parent::horadatage(MaClasse::class,"MaClasse",$args);   
    }  
}
```

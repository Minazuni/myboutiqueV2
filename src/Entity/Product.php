<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderDetails::class)]
    private Collection $orderDetails;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Comment::class)]
    private Collection $comments;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /*
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }*/

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetails>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): static
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setProduct($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getProduct() === $this) {
                $orderDetail->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    public function getAverageComment()
    {
        $somme = 0;
        $totalComments = count($this->comments);

        // Vérifier si le produit a des avis
        if ($totalComments > 0) {
            foreach ($this->comments as $comment) {
                $somme += $comment->getRating();
            }
            return $somme / $totalComments;
        } else {
            return 0; // Retourner 0 si le produit n'a pas d'avis
        }
    }
    public function isProductFromUser(User $user)
    {
        // Parcours de chaque commande de l'utilisateur
        foreach ($user->getOrders() as $orders) {
            // Parcours de chaque détail de commande dans une commande
            foreach ($orders->getOrderDetails() as $orderDetail) {
                // Vérification si le produit dans le détail de commande correspond à ce produit
                // et si la commande est confirmée (statut == true)
                if ($orderDetail->getProduct()->getId() == $this->id && $orders->getStatut()) {
                    // Si c'est le cas, retourne le produit
                    return $orderDetail->getProduct();
                }
            }
        }
        // Si le produit n'est pas trouvé dans les commandes de l'utilisateur, retourne null
        return null;
    }
    public function getCommentFromUser(User $user)
    {
        // Parcours de chaque commentaire associé à ce produit
        foreach ($this->comments as $comment) {
            // Vérifie si l'utilisateur du commentaire correspond à l'utilisateur passé en paramètre
            if ($comment->getUser() === $user) {
                // Si c'est le cas, retourne le commentaire
                return $comment;
            }
        }
        // Si aucun commentaire de cet utilisateur n'est trouvé, retourne null
        return null;
    }
}

<?php
class User {
    private $id;
    private $nom;
    private $prenom;
    private $dateNaissance;
    private $email;
    private $numTelephone;
    private $password;
    private $image;
    private $genre;
    private $localisation;
    private $createdAt;
    private $confirmPassword;
    private $roles;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    public function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst(str_replace('_', '', $key));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): self { $this->id = $id; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }

    public function getDateNaissance(): ?string { return $this->dateNaissance; }
    public function setDateNaissance(string $dateNaissance): self { $this->dateNaissance = $dateNaissance; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }

    public function getNumTelephone(): ?string { return $this->numTelephone; }
    public function setNumTelephone(string $numTelephone): self { $this->numTelephone = $numTelephone; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): self { 
        $this->password = password_hash($password, PASSWORD_BCRYPT); 
        return $this; 
    }

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getGenre(): ?string { return $this->genre; }
    public function setGenre(string $genre): self { $this->genre = $genre; return $this; }

    public function getLocalisation(): ?string { return $this->localisation; }
    public function setLocalisation(string $localisation): self { $this->localisation = $localisation; return $this; }

    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function setCreatedAt(string $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function getConfirmPassword(): ?string { return $this->confirmPassword; }
    public function setConfirmPassword(string $confirmPassword): self { $this->confirmPassword = $confirmPassword; return $this; }

    public function getRoles(): ?string { return $this->roles; }
    public function setRoles(string $roles): self { $this->roles = $roles; return $this; }

    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->password);
    }
}
?>

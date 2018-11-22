<?php
declare(strict_types=1);

namespace App\Form\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @Assert\NotBlank(message = "Please enter your current password")
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     * @var string
     */
    private $currentPassword;

    /**
     * @Assert\NotBlank(message = "Please enter a new password")
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @return string
     */
    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    /**
     * @param string $currentPassword
     * @return ChangePassword
     */
    public function setCurrentPassword(string $currentPassword): ChangePassword
    {
        $this->currentPassword = $currentPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return ChangePassword
     */
    public function setPassword(string $password): ChangePassword
    {
        $this->password = $password;

        return $this;
    }
}

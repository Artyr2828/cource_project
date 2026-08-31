<?php
namespace App\Service;
use App\Entity\User;
use App\DTO\RegisterUserRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

final class RegistrationService {

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher,
        private JWTTokenManagerInterface $jwt,
        private UserRepository $userRepository
    ){}

    public function register(RegisterUserRequest $dto): string{
        $this->ensureUserDoesNotExist($dto->email);
        $user = $this->createEntityUser($dto);
        $this->sendToDatabase($user);
        $token = $this->jwt->create($user);
        return $token;
    }

    private function createEntityUser(RegisterUserRequest $dto): User {
        $user = new User();
        $user->setEmail($dto->email);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $dto->password));
        return $user;
    }

    private function ensureUserDoesNotExist(string $email): void {
        $existingUser = $this->userRepository->findOneBy(['email'=>$email]);
        if ($existingUser !== null){
          throw new ConflictHttpException("User already exists");
        }
    }

    private function sendToDatabase(User $user): void {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}

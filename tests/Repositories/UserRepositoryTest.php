<?php

namespace Tests\Repositories;

use PHPUnit\Framework\TestCase;
use App\Repositories\UserRepository;
use Doctrine\ORM\EntityManager;
use App\Entities\User;
use Dotenv\Dotenv;
use Core\Database\DB;

/**
 * @internal
 *
 * @coversNothing
 */
class UserRepositoryTest extends TestCase
{
    private EntityManager $entityManager;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);

        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();

        $this->entityManager = DB::Connection();
    }

public function testGeneratePasswordResetToken(): void
{
    $email = 'admin@mailinator.com';
    $repository = $this->entityManager->getRepository(User::class);

    $token = $repository->generatePasswordResetToken($email);
    $this->assertIsString($token);
    $user = $repository->findOneBy(['email' => $email]);
    $this->assertEquals($token, $user->reset_token);
    $this->assertNotEmpty($user->reset_token_date);
}

public function testChangePasword(): void
{
    $email = 'admin@mailinator.com';
    $repository = $this->entityManager->getRepository(User::class);
    $token = $repository->generatePasswordResetToken($email);
    $data = [
        'password' => 'P@ssword2',
        'password_confirm' => 'P@ssword2',
    ];
    $result = $repository->changePassword($token, $data);
    $this->assertNull($result);
}
}

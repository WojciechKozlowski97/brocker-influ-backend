<?php

namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Entity\UserActivation;
use App\Repository\UserRepository;
use App\Service\EmailService;
use App\Service\EmailServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailServiceTest extends TestCase
{
    private MailerInterface $mailer;
    private UrlGeneratorInterface $urlGenerator;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private ParameterBagInterface $params;
    private EmailServiceInterface $emailService;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->params = $this->createMock(ParameterBagInterface::class);

        $this->emailService = new EmailService(
            $this->mailer,
            $this->urlGenerator,
            $this->userRepository,
            $this->entityManager,
            $this->params
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testSendActivationEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $userActivation = new UserActivation();
        $userActivation->setEmailVerificationToken('123456');
        $user->setUserActivation($userActivation);

        $this->urlGenerator->expects($this->once())
            ->method('generate')
            ->with(
                'api_activate_account',
                [
                    'token' => '123456',
                    'email' => 'test@example.com'
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
            ->willReturn('https://example.com/activate?token=123456&email=test@example.com');

        $this->params->expects($this->once())
            ->method('get')
            ->with('mailer_from')
            ->willReturn('no-reply@example.com');

        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Email $email) {
                $this->assertSame('no-reply@example.com', $email->getFrom()[0]->getAddress());
                $this->assertSame('test@example.com', $email->getTo()[0]->getAddress());
                $this->assertSame('Activate your account', $email->getSubject());
                $this->assertStringContainsString(
                    'https://example.com/activate?token=123456&email=test@example.com',
                    $email->getHtmlBody()
                );
                return true;
            }));

        $this->emailService->sendActivationEmail($user);
    }

    /**
     * @throws Exception
     */
    public function testActivateAccountSuccess(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $userActivation = new UserActivation();
        $userActivation->setEmailVerificationToken('123456');
        $user->setUserActivation($userActivation);

        $this->userRepository->expects($this->once())
            ->method('findOneByEmailAndActivationToken')
            ->with('test@example.com', '123456')
            ->willReturn($user);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->emailService->activateAccount('test@example.com', '123456');
        $this->assertTrue($user->getIsVerified());
    }

    public function testActivateAccountFailure(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User not found or activation token is invalid.');

        $this->userRepository->expects($this->once())
            ->method('findOneByEmailAndActivationToken')
            ->with('test@example.com', '123456')
            ->willReturn(null);

        $this->emailService->activateAccount('test@example.com', '123456');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testSendSuccessResetPasswordEmail(): void
    {
        $this->params->expects($this->once())
            ->method('get')
            ->with('mailer_from')
            ->willReturn('no-reply@example.com');

        $this->mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Email $email) {
                $this->assertSame('no-reply@example.com', $email->getFrom()[0]->getAddress());
                $this->assertSame('test@example.com', $email->getTo()[0]->getAddress());
                $this->assertSame('Activate your account', $email->getSubject());
                $this->assertStringContainsString('Password reset successfully', $email->getHtmlBody());
                return true;
            }));

        $this->emailService->sendSuccessResetPasswordEmail('test@example.com');
    }
}

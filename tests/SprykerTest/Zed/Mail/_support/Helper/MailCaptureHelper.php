<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\Mail\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollection;
use Spryker\Zed\Mail\MailConfig;
use Spryker\Zed\Mail\MailDependencyProvider;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;

/**
 * Collects the mails a request would send instead of handing them to a real provider, so operations
 * whose Zed leg mails — customer registration under double opt-in, an email-change verification —
 * can run on the docker-free host lane.
 *
 * The lane has no mail transport to give them. The configured provider renders its body through the
 * `renderer` application service, which only a Yves or Zed application registers in the global
 * container; from a Glue request it is null and the send dies with
 * `Call to a member function hasExtension() on null`.
 *
 * The seam is the provider collection rather than the transport: everything above it — the mail
 * type builders, the recipient and subject resolution, the whole `MailFacade` path — still runs for
 * real, and what the test asserts on is the `MailTransfer` the stack actually produced. What a
 * captured mail does *not* prove is rendering and delivery.
 *
 * Enable it alongside {@see \SprykerTest\Shared\Testify\Helper\DependencyHelper}, which carries the
 * binding, in any suite whose resource can reach a mail.
 */
class MailCaptureHelper extends Module
{
    use DependencyHelperTrait;

    protected CapturingMailProviderPlugin $capturingMailProviderPlugin;

    public function _before(TestInterface $test): void
    {
        $this->capturingMailProviderPlugin = new CapturingMailProviderPlugin();

        // Replaces the whole collection rather than adding to it: the handler sends through every
        // provider accepting the mail type, so a real one left in place would still be reached.
        $this->getDependencyHelper()->setDependency(
            MailDependencyProvider::MAIL_PROVIDER_COLLECTION,
            (new MailProviderCollection())->addProvider(
                $this->capturingMailProviderPlugin,
                MailConfig::MAIL_TYPE_ALL,
            ),
        );
    }

    /**
     * Makes every further send in the running test throw, so a resource declaring a send-failure
     * response can be driven into it. Flipping the registered provider rather than swapping the
     * collection is what makes this work after a fixture has already mailed - see
     * {@see \SprykerTest\Zed\Mail\Helper\CapturingMailProviderPlugin}.
     */
    public function arrangeFailingMailTransport(): void
    {
        $this->capturingMailProviderPlugin->failNextSends();
    }

    /**
     * @return array<\Generated\Shared\Transfer\MailTransfer>
     */
    public function getSentMails(): array
    {
        return $this->capturingMailProviderPlugin->getSentMails();
    }

    public function findSentMailByType(string $mailType): ?MailTransfer
    {
        foreach ($this->getSentMails() as $mailTransfer) {
            if ($mailTransfer->getType() === $mailType) {
                return $mailTransfer;
            }
        }

        return null;
    }
}

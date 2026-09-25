<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\Mail\Helper;

use Generated\Shared\Transfer\MailTransfer;
use RuntimeException;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailProviderPluginInterface;

/**
 * Keeps every mail handed to it, so {@see \SprykerTest\Zed\Mail\Helper\MailCaptureHelper} can
 * hand them back to the test.
 *
 * It can also be flipped into failing the next send, because a resource whose contract declares a
 * send failure has to be driven into one. Swapping the provider collection mid-test cannot do that:
 * the Zed container is built on the first mail a test sends, and a fixture that mails - activating
 * an email factor, say - builds it before the test arranges anything.
 *
 * Deliberately not an `AbstractPlugin`: the kernel resolves a plugin's factory from its namespace,
 * and this one lives outside `Spryker\Zed\Mail`.
 */
class CapturingMailProviderPlugin implements MailProviderPluginInterface
{
    /**
     * @var array<\Generated\Shared\Transfer\MailTransfer>
     */
    protected array $sentMails = [];

    protected bool $isFailing = false;

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        if ($this->isFailing) {
            throw new RuntimeException(sprintf('Mail transport unavailable for "%s".', (string)$mailTransfer->getType()));
        }

        $this->sentMails[] = $mailTransfer;
    }

    public function failNextSends(): void
    {
        $this->isFailing = true;
    }

    /**
     * @return array<\Generated\Shared\Transfer\MailTransfer>
     */
    public function getSentMails(): array
    {
        return $this->sentMails;
    }
}

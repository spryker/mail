<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\Mail\Helper;

use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailProviderPluginInterface;

/**
 * Keeps every mail handed to it, so {@see \SprykerTest\Zed\Mail\Helper\MailCaptureHelper} can
 * hand them back to the test.
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

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        $this->sentMails[] = $mailTransfer;
    }

    /**
     * @return array<\Generated\Shared\Transfer\MailTransfer>
     */
    public function getSentMails(): array
    {
        return $this->sentMails;
    }
}

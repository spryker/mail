<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business\Exception;

use Exception;

class SwiftMailerRemovedException extends Exception
{
    protected const string MESSAGE_TEMPLATE = <<<'TXT'
%s is no longer functional.

`swiftmailer/swiftmailer` is abandoned and has been removed from `spryker/mail`. The classes of the
SwiftMailer integration are still shipped so that existing type declarations and plugin
registrations keep resolving, but the integration itself is gone.

Migrate to `spryker/symfony-mailer`: register
\Spryker\Zed\SymfonyMailer\Communication\Plugin\Mail\SymfonyMailerProviderPlugin on
\Spryker\Zed\Mail\MailDependencyProvider::MAIL_PROVIDER_COLLECTION and move the SMTP settings to
\Spryker\Zed\SymfonyMailer\SymfonyMailerConfig.

If you genuinely still need SwiftMailer, pin `spryker/mail:~4.13.0` - the last version that ships a
working integration.
TXT;

    /**
     * @param string $member Fully qualified class name, optionally suffixed with `::method()`.
     *
     * @return self
     */
    public static function forMember(string $member): self
    {
        return new self(sprintf(static::MESSAGE_TEMPLATE, $member));
    }
}

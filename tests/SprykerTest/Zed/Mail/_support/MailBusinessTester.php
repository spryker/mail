<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Mail;

use Codeception\Actor;
use Spryker\Zed\Mail\Dependency\Facade\MailToLocaleFacadeBridge;
use Spryker\Zed\Mail\Dependency\Facade\MailToLocaleFacadeInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class MailBusinessTester extends Actor
{
    use _generated\MailBusinessTesterActions;

    /**
     * @return \Spryker\Zed\Mail\Dependency\Facade\MailToLocaleFacadeInterface
     */
    public function getLocaleFacade(): MailToLocaleFacadeInterface
    {
        return new MailToLocaleFacadeBridge($this->getLocator()->locale()->facade());
    }
}

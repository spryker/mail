<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Mail\Business\Model\Provider;

use Codeception\Test\Unit;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollection;
use Spryker\Zed\Mail\Business\Model\Provider\MailProviderCollectionGetInterface;
use Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Mail
 * @group Business
 * @group Model
 * @group Provider
 * @group MailProviderCollectionTest
 * Add your own group annotations below this line
 */
class MailProviderCollectionTest extends Unit
{
    /**
     * @var string
     */
    public const TYPE_A = 'type a';

    /**
     * @var string
     */
    public const TYPE_B = 'type b';

    /**
     * @var string
     */
    public const MAIL_TYPE_ALL = '*';

    public function testAddProviderWithOneAcceptedTypeWillReturnFluentInterface(): void
    {
        $mailProviderCollection = $this->getMailProviderCollection();
        $mailProviderMock = $this->getMailProviderMock();

        $this->assertInstanceOf(MailProviderCollection::class, $mailProviderCollection->addProvider($mailProviderMock, $this->getAcceptedType()));
    }

    public function testAddProviderWithMultipleAcceptedTypesWillReturnFluentInterface(): void
    {
        $mailProviderCollection = $this->getMailProviderCollection();
        $mailProviderMock = $this->getMailProviderMock();

        $this->assertInstanceOf(MailProviderCollection::class, $mailProviderCollection->addProvider($mailProviderMock, $this->getAcceptedTypes()));
    }

    public function testGetProviderWillReturnProviderIfOneByTypeNameInCollection(): void
    {
        $mailProviderCollection = $this->getMailProviderCollectionWithTypeAProvider();
        $mailProviderPlugins = $mailProviderCollection->getProviderForMailType(static::TYPE_A);

        $this->assertCount(1, $mailProviderPlugins);
    }

    public function testGetProviderWillReturnProviderWhichIsRegisteredForAllMailTypes(): void
    {
        $mailProviderCollection = $this->getMailProviderCollectionWithTypeAProvider();
        $mailProviderPlugins = $mailProviderCollection->getProviderForMailType(static::TYPE_A);

        $this->assertCount(1, $mailProviderPlugins);
    }

    public function testGetProviderWillReturnEmptyArrayWhenNoProviderForGivenMailTypeInCollection(): void
    {
        $mailProviderCollection = $this->getMailProviderCollectionWithTypeAProvider();
        $mailProviderPlugins = $mailProviderCollection->getProviderForMailType(static::TYPE_B);

        $this->assertCount(0, $mailProviderPlugins);
    }

    protected function getMailProviderCollection(): MailProviderCollection
    {
        $mailProviderCollection = new MailProviderCollection();

        return $mailProviderCollection;
    }

    protected function getMailProviderCollectionWithTypeAProvider(): MailProviderCollectionGetInterface
    {
        $mailProviderMock = $this->getMailProviderMock();
        $mailProviderCollection = new MailProviderCollection();
        $mailProviderCollection->addProvider($mailProviderMock, static::TYPE_A);

        return $mailProviderCollection;
    }

    protected function getMailProviderCollectionWithProviderForAllMailTypes(): MailProviderCollectionGetInterface
    {
        $mailProviderMock = $this->getMailProviderMock();
        $mailProviderCollection = new MailProviderCollection();
        $mailProviderCollection->addProvider($mailProviderMock, static::MAIL_TYPE_ALL);

        return $mailProviderCollection;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface
     */
    protected function getMailProviderMock(): MailProviderPluginInterface
    {
        $mailProviderMock = $this->getMockBuilder(MailProviderPluginInterface::class)->getMock();

        return $mailProviderMock;
    }

    protected function getAcceptedTypes(): array
    {
        return [
            static::TYPE_A,
            static::TYPE_B,
        ];
    }

    protected function getAcceptedType(): string
    {
        return static::TYPE_A;
    }
}

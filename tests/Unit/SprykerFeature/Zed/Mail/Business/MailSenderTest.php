<?php


namespace Unit\SprykerFeature\Zed\Mail\Business;

use Generated\Shared\Transfer\MailAttachmentTransfer;
use Generated\Shared\Transfer\MailHeaderTransfer;
use Generated\Shared\Transfer\MailMailTransfer;
use Generated\Shared\Transfer\MailRecipientTransfer;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Mail\Business\MandrillMailSender;

/**
 * @group Zed
 * @group Business
 * @group Mail
 * @group MailSenderTest
 * @see https://mandrillapp.com/api/docs/messages.php.html#method-send-template
 */
class MailSenderTest extends \PHPUnit_Framework_TestCase
{

    public function testSendMail()
    {
        $locator = Locator::getInstance();

        $mandrillMock = $this->getMock('\\Mandrill', [], ['MOCK_API_KEY']);
        $mandrillMessenger = $this->getMock('\\Mandrill_Messages', [], [$mandrillMock]);
        $mandrillMock->messages = $mandrillMessenger;
        $inclusionHandler = $this->getMock('\\SprykerFeature\\Zed\\Mail\\Business\\InclusionHandlerInterface');

        $textFilePath = __DIR__ . '/testfile.txt';
        $imageFilePath = __DIR__ . '/spryker-logo.png';
        $inclusionHandler->expects($this->any())->method('guessType')->will($this->returnValueMap(
            [
                [$textFilePath, 'text/plain'],
                [$imageFilePath,'image/png']
            ]
        ));
        $inclusionHandler->expects($this->any())->method('encodeBase64')->will($this->returnValueMap(
            [
                [$textFilePath, 'VGVzdCBGaWxlIENvbnRlbnQ='],
                [$imageFilePath,'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']
            ]
        ));
        $mockResult = [
            [
                'email' => 'testrecipient1@testmail.com',
                'status' => 'sent',
                'reject_reason' => '',
                '_id' => 'messageId1'
            ],
            [
                'email' => 'testrecipient2@testmail.com',
                'status' => 'rejected',
                'reject_reason' => 'hard-bounce',
                '_id' => 'messageId2'
            ]
        ];

        $mailSender = new MandrillMailSender($mandrillMock, $inclusionHandler);

        $mailTransfer = new MailMailTransfer($locator);
        $mailTransfer
            ->setTemplateName('test_template')
            ->setTemplateContent(['templatekey' => 'templatevalue'])
            ->setSubject('test subject')
            ->setFromEmail('testfrom@testmail.com')
            ->setFromName('Test From Name')
            ->addRecipient(
                (new MailRecipientTransfer())
                ->setEmail('testrecipient1@testmail.com')
                ->setName('Recipient 1')
                ->setMergeVars(['recipient1Var' => 'recipient1Content'])
            )
            ->addRecipient(
                (new MailRecipientTransfer())
                    ->setEmail('testrecipient2@testmail.com')
                    ->setName('Recipient 2')
                    ->setType('cc')
                    ->setMetadata(['perRecipient1' => 'anotherValue'])
            )
            ->addHeader(
                (new MailHeaderTransfer())
                    ->setKey('Reply-To')
                    ->setValue('testfrom2@testmail.com')
            )
            ->setImportant(true)
            ->setTrackOpens(true)
            ->setTrackClicks(true)
            ->setAutoText(true)
            ->setAutoHtml(true)
            ->setInlineCss(true)
            ->setUrlStripQueryString(true)
            ->setPreserveRecipients(true)
            ->setViewContentLink(true)
            ->setBccAddress('somebcc@mail.com')
            ->setTrackingDomain('aDomain')
            ->setSigningDomain('bDomain')
            ->setReturnPathDomain('cDomain')
            ->setMerge(true)
            ->setMergeLanguage('handlebars')
            ->setGlobalMergeVars(['globalMergeVar1' => 'globalMergeVarContent'])
            ->setTags(['tag1', 'tag2'])
            ->setSubAccount('subAccountId')
            ->setGoogleAnalyticsDomains(['dDomain'])
            ->setGoogleAnalyticsCampaign('campaign')
            ->setMetadata(['global1' => 'someValue'])
            ->addAttachment(
                (new MailAttachmentTransfer())
                    ->setFileName($textFilePath)
                    ->setDisplayName('ATestfile')
                )
            ->addImage(
                (new MailAttachmentTransfer())
                    ->setFileName($imageFilePath)
                    ->setDisplayName('logo')
            )
            ->setAsync(true)
            ->setIpPool('Test Ip Pool')
            ->setSendAt(new \DateTime('2010-10-10 01:10:10'))
        ;

        $mandrillMessenger->expects($this->once())->method('sendTemplate')->with(
            $this->equalTo('test_template'),
            $this->equalTo(
                [
                    ['name' => 'templatekey', 'content' => 'templatevalue']
                ]
            ),
            $this->equalTo(
                [
                    'subject' => 'test subject',
                    'from_email' => 'testfrom@testmail.com',
                    'from_name' => 'Test From Name',
                    'to' => [
                        [
                            'email' => 'testrecipient1@testmail.com',
                            'name' => 'Recipient 1',
                            'type' => 'to'
                        ],
                        [
                            'email' => 'testrecipient2@testmail.com',
                            'name' => 'Recipient 2',
                            'type' => 'cc'
                        ]
                    ],
                    'headers' => [
                        'Reply-To' => 'testfrom2@testmail.com'
                    ],
                    'important' => true,
                    'track_opens' => true,
                    'track_clicks' => true,
                    'auto_text' => true,
                    'auto_html' => true,
                    'inline_css' => true,
                    'url_strip_qs' => true,
                    'preserve_recipients' => true,
                    'view_content_link' => true,
                    'bcc_address' => 'somebcc@mail.com',
                    'tracking_domain' => 'aDomain',
                    'signing_domain' => 'bDomain',
                    'return_path_domain' => 'cDomain',
                    'merge' => true,
                    'merge_language' => 'handlebars',
                    'global_merge_vars' => [
                        [
                            'name' => 'globalMergeVar1',
                            'content' => 'globalMergeVarContent'
                        ]
                    ],
                    'merge_vars' => [
                        [
                            'rcpt' => 'testrecipient1@testmail.com',
                            'vars' => [
                                [
                                    'name' => 'recipient1Var',
                                    'content' => 'recipient1Content'
                                ]
                            ]
                        ]
                    ],
                    'tags' => ['tag1', 'tag2'],
                    'subaccount' => 'subAccountId',
                    'google_analytics_domains' => ['dDomain'],
                    'google_analytics_campaign' => 'campaign',
                    'metadata' => [
                        'global1' => 'someValue'
                    ],
                    'recipient_metadata' => [
                        [
                            'rcpt' => 'testrecipient2@testmail.com',
                            'values' => [
                                'perRecipient1' => 'anotherValue'
                            ]
                        ]
                    ],
                    'attachments' => [
                        [
                            'type' => 'text/plain',
                            'name' => 'ATestfile',
                            'content' => 'VGVzdCBGaWxlIENvbnRlbnQ='
                        ]
                    ],
                    'images' => [
                        [
                            'type' => 'image/png',
                            'name' => 'logo',
                            'content' => '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'
                        ]
                    ]
                ]
            ),
            $this->equalTo(true),
            $this->equalTo('Test Ip Pool'),
            $this->equalTo('2010-10-10 01:10:10')
        )->will($this->returnValue($mockResult));

        $result = $mailSender->sendMail($mailTransfer);

        $this->assertEquals($mockResult, $result);
    }
}

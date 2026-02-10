<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\Tester\Environment;
use Contributte\Tester\Toolkit;
use Minetro\Imap\ImapReader;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

if (!extension_loaded('imap')) {
	Environment::skip('Requires ext-imap');
}

Toolkit::test(function (): void {
	Assert::same('ALL', ImapReader::CRITERIA_ALL);
	Assert::same('ANSWERED', ImapReader::CRITERIA_ANSWERED);
	Assert::same('BCC', ImapReader::CRITERIA_BCC);
	Assert::same('BEFORE', ImapReader::CRITERIA_BEFORE);
	Assert::same('BODY', ImapReader::CRITERIA_BODY);
	Assert::same('CC', ImapReader::CRITERIA_CC);
	Assert::same('DELETED', ImapReader::CRITERIA_DELETED);
	Assert::same('FLAGGED', ImapReader::CRITERIA_FLAGGED);
	Assert::same('FROM', ImapReader::CRITERIA_FROM);
	Assert::same('KEYWORD', ImapReader::CRITERIA_KEYWORD);
	Assert::same('NEW', ImapReader::CRITERIA_NEW);
	Assert::same('OLD', ImapReader::CRITERIA_OLD);
	Assert::same('ON', ImapReader::CRITERIA_ON);
	Assert::same('RECENT', ImapReader::CRITERIA_RECENT);
	Assert::same('SEEN', ImapReader::CRITERIA_SEEN);
	Assert::same('SINCE', ImapReader::CRITERIA_SINCE);
	Assert::same('SUBJECT', ImapReader::CRITERIA_SUBJECT);
	Assert::same('TEXT', ImapReader::CRITERIA_TEXT);
	Assert::same('TO', ImapReader::CRITERIA_TO);
	Assert::same('UNANSWERED', ImapReader::CRITERIA_UNANSWERED);
	Assert::same('UNDELETED', ImapReader::CRITERIA_UNDELETED);
	Assert::same('UNFLAGGED', ImapReader::CRITERIA_UNFLAGGED);
	Assert::same('UNKEYWORD', ImapReader::CRITERIA_UNKEYWORD);
	Assert::same('UNSEEN', ImapReader::CRITERIA_UNSEEN);
});

<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\Tester\Environment;
use Contributte\Tester\Toolkit;
use InvalidArgumentException;
use Minetro\Imap\ImapMessage;
use stdClass;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

if (!extension_loaded('imap')) {
	Environment::skip('Requires ext-imap');
}

function createStructure(?string $charset = 'UTF-8', int $encoding = 0): stdClass
{
	$param = new stdClass();
	$param->attribute = 'charset';
	$param->value = $charset;

	$structure = new stdClass();
	$structure->parameters = [$param];
	$structure->encoding = $encoding;

	return $structure;
}

function createHeaders(): stdClass
{
	$headers = new stdClass();
	$headers->subject = 'Test Subject';
	$headers->from = 'sender@example.com';
	$headers->to = 'recipient@example.com';

	return $headers;
}

// Tests

Toolkit::test(function (): void {
	$headers = createHeaders();
	$structure = createStructure();
	$body = ['Section 0', 'Section 1'];

	$message = new ImapMessage(1, $headers, $structure, $body);

	Assert::same(1, $message->getNumber());
	Assert::type(stdClass::class, $message->getHeaders());
	Assert::same($structure, $message->getStructure());
	Assert::same($body, $message->getBody());
	Assert::same(2, $message->countBodies());
});

Toolkit::test(function (): void {
	$message = new ImapMessage(42, createHeaders(), createStructure(), ['body0', 'body1', 'body2']);

	Assert::same('body0', $message->getBodySection(0));
	Assert::same('body1', $message->getBodySection(1));
	Assert::same('body2', $message->getBodySection(2));
});

Toolkit::test(function (): void {
	$message = new ImapMessage(1, createHeaders(), createStructure(), ['only']);

	Assert::exception(function () use ($message): void {
		$message->getBodySection(5);
	}, InvalidArgumentException::class, 'Section #5 not found.');
});

Toolkit::test(function (): void {
	$message = new ImapMessage(1, createHeaders(), createStructure('UTF-8'), []);

	Assert::same('UTF-8', $message->getBodyCharset());
});

Toolkit::test(function (): void {
	$structure = new stdClass();
	$structure->parameters = [];
	$structure->encoding = 0;

	$message = new ImapMessage(1, createHeaders(), $structure, []);

	Assert::null($message->getBodyCharset());
});

Toolkit::test(function (): void {
	$structure = createStructure('UTF-8', 0);
	$message = new ImapMessage(1, createHeaders(), $structure, ['Hello World']);

	Assert::same('Hello World', $message->getBodySectionText(0, 0));
});

Toolkit::test(function (): void {
	$message = new ImapMessage(1, createHeaders(), createStructure(), []);

	Assert::same(0, $message->countBodies());
});

Toolkit::test(function (): void {
	Assert::same('\\Seen', ImapMessage::FLAG_SEEN);
	Assert::same('\\Answered', ImapMessage::FLAG_ANSWERED);
	Assert::same('\\Flagged', ImapMessage::FLAG_FLAGGED);
	Assert::same('\\Deleted', ImapMessage::FLAG_DELETED);
	Assert::same('\\Draft', ImapMessage::FLAG_DRAFT);
});

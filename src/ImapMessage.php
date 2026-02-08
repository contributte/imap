<?php declare(strict_types = 1);

namespace Minetro\Imap;

use InvalidArgumentException;
use stdClass;

/**
 * Imap Message
 */
class ImapMessage
{

	// Message flags
	public const FLAG_SEEN = '\\Seen';
	public const FLAG_ANSWERED = '\\Answered';
	public const FLAG_FLAGGED = '\\Flagged';
	public const FLAG_DELETED = '\\Deleted';
	public const FLAG_DRAFT = '\\Draft';

	private int $number;

	private stdClass $headers;

	private stdClass $structure;

	/** @var array<string|false> */
	private array $body = [];

	/**
	 * @param array<string|false> $body
	 */
	public function __construct(int $number, stdClass $headers, stdClass $structure, array $body)
	{
		$this->number = $number;
		$this->headers = $this->utf8($headers);
		$this->structure = $structure;
		$this->body = $body;
	}

	public function getNumber(): int
	{
		return $this->number;
	}

	public function getHeaders(): stdClass
	{
		return $this->headers;
	}

	public function getStructure(): stdClass
	{
		return $this->structure;
	}

	/**
	 * @return array<string|false>
	 */
	public function getBody(): array
	{
		return $this->body;
	}

	/**
	 * @throws InvalidArgumentException
	 */
	public function getBodySection(int $section): string|false
	{
		if ($section > count($this->body) || !isset($this->body[$section])) {
			throw new InvalidArgumentException('Section #' . $section . ' not found.');
		}

		return $this->body[$section];
	}

	/**
	 * Returns text of the e-mail converted to utf-8.
	 */
	public function getBodySectionText(int $section, ?int $encoding = null): string
	{
		$text = (string) $this->getBodySection($section);
		$encoding ??= (int) (isset($this->structure->parts[$section]) ? $this->structure->parts[$section]->encoding : $this->structure->encoding);

		$etext = match ($encoding) {
			0 => $text, // 7BIT
			1 => quoted_printable_decode((string) imap_8bit($text)), // 8BIT
			2 => (string) imap_binary($text), // BINARY
			3 => (string) imap_base64($text), // BASE64
			4 => quoted_printable_decode($text), // QUOTED-PRINTABLE
			default => $text, // OTHER / UNKNOWN
		};

		$charset = $this->getBodyCharset();

		if ($charset === null) {
			$detected = mb_detect_encoding($etext, mb_detect_order(), true);
			$charset = $detected !== false ? $detected : null;
		}

		if ($charset === null) {
			return $etext;
		}

		$result = iconv($charset, 'UTF-8//TRANSLIT', $etext);

		return $result !== false ? $result : $etext;
	}

	/**
	 * Returns charset defined in e-mail headers.
	 */
	public function getBodyCharset(): ?string
	{
		foreach ($this->structure->parameters as $pair) {
			if (isset($pair->attribute) && $pair->attribute === 'charset') {
				return $pair->value; // @phpstan-ignore property.notFound
			}
		}

		return null;
	}

	public function countBodies(): int
	{
		return count($this->body);
	}

	private function utf8(stdClass $data): stdClass
	{
		$json = json_encode($data);

		if ($json === false) {
			return $data;
		}

		/** @var array<mixed> $array */
		$array = json_decode($json, true);
		$array = $this->utf8Recursive($array);

		$result = json_decode((string) json_encode($array));

		if ($result instanceof stdClass) {
			return $result;
		}

		return $data;
	}

	/**
	 * @param array<mixed> $array
	 * @return array<mixed>
	 */
	private function utf8Recursive(array $array): array
	{
		$result = [];

		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result[$key] = $this->utf8Recursive($value);
			} elseif (is_string($value)) {
				$result[$key] = imap_utf8($value);
			} else {
				$result[$key] = $value;
			}
		}

		return $result;
	}

}

[![](https://heatbadger.now.sh/github/readme/contributte/imap/)](https://heatbadger.now.sh/github/readme/contributte/imap/)

<p align=center>
    <a href="https://github.com/contributte/imap/actions"><img src="https://badgen.net/github/checks/contributte/imap/master?cache=300"></a>
    <a href="https://coveralls.io/r/contributte/imap"><img src="https://badgen.net/coveralls/c/github/contributte/imap?cache=300"></a>
    <a href="https://packagist.org/packages/minetro/imap"><img src="https://badgen.net/packagist/dm/minetro/imap"></a>
    <a href="https://packagist.org/packages/minetro/imap"><img src="https://badgen.net/packagist/v/minetro/imap"></a>
</p>
<p align=center>
    <a href="https://packagist.org/packages/minetro/imap"><img src="https://badgen.net/packagist/php/minetro/imap"></a>
    <a href="https://github.com/contributte/imap"><img src="https://badgen.net/github/license/contributte/imap"></a>
    <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
    <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
    <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
    Website <a href="https://contributte.org">contributte.org</a> | Contact <a href="https://f3l1x.io">f3l1x.io</a> | Twitter <a href="https://twitter.com/contributte">@contributte</a>
</p>

Lightweight IMAP reader for PHP applications with helpers for reading messages, searching mailboxes, and managing message flags.

## Versions

| State  | Version | Branch   | PHP     |
|--------|---------|----------|---------|
| dev    | `^1.2`  | `master` | `>=8.2` |
| stable | `^1.1`  | `master` | `>=8.2` |

## Installation

Install the package using [Composer](https://getcomposer.org).

```bash
composer require minetro/imap
```

## Usage

### Reading Emails

```php
use Minetro\Imap\ImapReader;

$reader = new ImapReader('{yourdomain.cz:143/imap}INBOX', $username, $password);
$emails = $reader->read(ImapReader::CRITERIA_UNSEEN);

// Iterate all emails
foreach ($emails as $email) {

    // Iterate all email parts
    for ($i = 0; $i < $email->countBodies(); $i++) {

        // Get text (encoded with right encoding)
        $text = $email->getBodySectionText($i);

        echo $text;
    }
}
```

### Search Criteria

You can use the following search criteria constants:

| Constant | Description |
|----------|-------------|
| `ImapReader::CRITERIA_ALL` | All messages |
| `ImapReader::CRITERIA_UNSEEN` | Unread messages |
| `ImapReader::CRITERIA_SEEN` | Read messages |
| `ImapReader::CRITERIA_NEW` | New messages |
| `ImapReader::CRITERIA_RECENT` | Recent messages |
| `ImapReader::CRITERIA_ANSWERED` | Answered messages |
| `ImapReader::CRITERIA_UNANSWERED` | Unanswered messages |
| `ImapReader::CRITERIA_DELETED` | Deleted messages |
| `ImapReader::CRITERIA_UNDELETED` | Not deleted messages |
| `ImapReader::CRITERIA_FLAGGED` | Flagged messages |
| `ImapReader::CRITERIA_UNFLAGGED` | Unflagged messages |

### Message Flags

You can set or unset flags on messages:

```php
use Minetro\Imap\ImapMessage;

// Mark message as seen
$reader->flag($messageNumber, ImapMessage::FLAG_SEEN);

// Mark message as flagged
$reader->flag($messageNumber, ImapMessage::FLAG_FLAGGED);

// Unmark message
$reader->unflag($messageNumber, ImapMessage::FLAG_SEEN);
```

Available flags:

| Constant | Flag |
|----------|------|
| `ImapMessage::FLAG_SEEN` | `\Seen` |
| `ImapMessage::FLAG_ANSWERED` | `\Answered` |
| `ImapMessage::FLAG_FLAGGED` | `\Flagged` |
| `ImapMessage::FLAG_DELETED` | `\Deleted` |
| `ImapMessage::FLAG_DRAFT` | `\Draft` |

### Proxy IMAP Functions

The `ImapReader` class supports proxying any `imap_*` function through the magic `__call` method:

```php
// Calls imap_status($connection, ...)
$reader->status($mailbox, SA_ALL);
```

## Development

See [how to contribute](https://contributte.org) to this package. This package is currently maintained by these authors.

<a href="https://github.com/f3l1x">
    <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

---

Consider to [support](https://contributte.org/partners.html) **contributte** development team. Also thank you for using this package.

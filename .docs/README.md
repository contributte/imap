# Documentation

## Setup

Install the package using [Composer](https://getcomposer.org).

```bash
composer require minetro/imap
```

## Usage

### Reading emails

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

### Search criteria

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

### Message flags

You can set or unset flags on messages:

```php
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

### Proxy IMAP functions

The `ImapReader` class supports proxying any `imap_*` function through the magic `__call` method:

```php
// Calls imap_status($connection, ...)
$reader->status($mailbox, SA_ALL);
```

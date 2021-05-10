<?php

declare(strict_types=1);


namespace App\Models\Message\Events;

use App\Models\Message\Doctrine\Message;
use App\Models\Message\VO\MessageContent;
use App\Models\User\Doctrine\User;

class MessageWasEdited extends MessageEvent
{
    private User $editor;

    private MessageContent $oldContent;

    private MessageContent $newContent;

    public function __construct(Message $entity, User $editor, MessageContent $oldContent, MessageContent $newContent)
    {
        parent::__construct($entity);

        $this->oldContent = $oldContent;
        $this->newContent = $newContent;
        $this->editor = $editor;
    }

    public static function with(Message $message, User $editor, MessageContent $oldContent, MessageContent $newContent): self
    {
        return new self($message, $editor, $oldContent, $newContent);
    }

    public function getOldContent(): MessageContent
    {
        return $this->oldContent;
    }

    public function getNewContent(): MessageContent
    {
        return $this->newContent;
    }

    public function getEditor(): User
    {
        return $this->editor;
    }

    public function NAME(): string
    {
        return 'message_edited';
    }
}

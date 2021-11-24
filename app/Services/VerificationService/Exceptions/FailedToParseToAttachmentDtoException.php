<?php

namespace App\Services\VerificationService\Exceptions;

use Exception;

class FailedToParseToAttachmentDtoException extends Exception
{
    public string $defaultMessage = 'Failed to parse array to Attachment DTO Object.';
}